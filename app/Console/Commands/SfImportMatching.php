<?php

namespace App\Console\Commands;

use App\Console\Commands\CommandUtility;
use App\Logging\BatchLogger;
use App\Managers\SfCustomerManager;
use App\Managers\SfManager;
use App\Managers\SfOrderManager;
use App\Model\WoaMatching;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Omniphx\Forrest\Exceptions\SalesforceException;

/**
 * マッチングSF連携
 */
class SfImportMatching extends Command
{
    use CommandUtility;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SfImportMatching {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'マッチングSF連携';

    private $logger;
    private $conf;
    private $slackChannel;
    private $woaCustomer;
    private $masterEntryCourse;
    private $sfMgr;
    private $sfCustomerMgr;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 初期化
     *
     * @return void
     */
    private function init()
    {
        $className = class_basename(get_class());
        $classNameSnake = Str::snake($className);
        $this->logger = new BatchLogger($className, "{$classNameSnake}.log");

        $this->conf = config("batch.{$classNameSnake}");
        $this->slackChannel = "{$classNameSnake}_error";

        $this->woaMatching = new WoaMatching();
        $this->sfMgr = new SfManager();
        $this->sfOrderMgr = new SfOrderManager();
        $this->sfCustomerMgr = new SfCustomerManager();
    }

    /**
     * 即時連携実行
     *
     * @return void
     */
    public static function startId($id)
    {
        self::asyncCommand("SfImportMatching {$id}");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 初期化
        $this->init();

        $this->logger->info('処理開始');

        // 引数取得
        $id = $this->argument('id');
        if (!empty($id)) {
            $this->logger->info("ID: {$id}");
        }

        // 連携対象データ取得
        $rows = $this->woaMatching->getSfLinkData($id);
        if ($rows->isEmpty()) {
            $this->logger->info('処理終了: 連携対象無し');

            return 0;
        }

        // SFインポート
        $this->importSf($rows);

        // Slackエラー通知
        if ($this->logger->countError() > 0) {
            $this->logger->notifyErrorToSlack($this->slackChannel);
        }

        $this->logger->info('処理終了');

        return 0;
    }

    /**
     * SFインポート
     *
     * @param array $rows 連携対象データリスト
     * @return void
     */
    private function importSf($rows)
    {
        foreach ($rows as $row) {
            try {
                $logTable = "(select * from woa_matching where id = {$row->id}\G)";
                // 黒本経由等でsfidがない場合、sfidを追加する
                if (empty($row->salesforce_id)) {
                    $sfCustomer = $this->sfCustomerMgr->getSfCustomerFromRegistrationHistory($row->customer_id);
                    $row->salesforce_id = $sfCustomer->salesforce_id;
                    $this->logger->info("黒本経由対象SFID：{$sfCustomer->salesforce_id}");
                }

                $sfFlag = 0;
                // SFインポートデータ作成
                $import = $this->makeImportData($row);

                if (!empty($import)) {
                    // SF更新
                    $result = $this->sfMgr->insert('kjb_matching__c', $import['import']);
                    $sfFlag = 1;
                    // SF連携の戻り値判定
                    $sfFlag = $this->chkSFInsert($result, $sfFlag, $logTable);
                } else {
                    // 更新対象が無い場合エラー
                    $sfFlag = 2;
                }
            } catch (SalesforceException $e) {
                $msg = json_decode($e->getMessage());
                if (is_null($msg)) {
                    // json_decodeに失敗した場合はデコードしない
                    $this->logger->error("SalesforceException平文: 連携失敗 ID: {$row->id}: {$e->getMessage()}\n" . $logTable);
                    $this->logger->info("{$e->getFile()}: {$e->getLine()}\n{$e->getTraceAsString()}");
                } else {
                    // json_decodeできた場合
                    $this->logger->error("SalesforceException配列: 連携失敗 ID: {$row->id}:\n" . print_r($msg, true) . "\n" . $logTable);
                }
                $sfFlag = 2;
            } catch (\Exception $e) {
                $this->logger->error("連携失敗 ID: {$row->id}: {$e->getMessage()}: {$e->getFile()}:{$e->getLine()}\n{$e->getTraceAsString()}\n{$logTable}");
                $sfFlag = 2;
            } finally {
                // SF連携フラグ更新
                $this->woaMatching->updateSfFlag($row->id, $sfFlag);
            }
        }
    }

    /**
     * SFインポートデータ作成
     *
     * @param array $row 連携対象データ
     * @return array インポートデータ
     */
    protected function makeImportData($row)
    {
        // 設定ファイルから連携項目をセット
        $import = [];
        foreach ($this->conf['link_columns'] as $key => $val) {
            $import[$key] = $row->$val;
        }

        // オーダーIDがSFに存在するか確認
        $orderId = $this->checkExistsOrder($row);
        if (empty($orderId)) {
            return [];
        }

        // コメディカルマッチング案件名(空文字を渡さなければSF側で自動生成してくれるのでダミー文字を渡す)
        $import['Name'] = 'DUMMY_STRING';

        // SFから求職者情報を取得
        $sfCustomer = $this->getSfCustomer($row);
        if (empty($sfCustomer)) {
            return [];
        }

        // 求職者の所有者がCP（005頭）の時は、実際のSFIDを保持
        if (substr($sfCustomer['ownerId'], 0, 3) == '005') {
            $import['matching_Owner_ID__c'] = $sfCustomer['ownerId'];
        }

        // 受注までのステータスと作成元アクション
        $entryStatus = $this->replaceEntryStatus($row->entry_status);
        $import['jyutyumadenosatus__c'] = $import['CreateBasedAction__c'] = $entryStatus;

        // 獲得経路
        $import['kakutokukeiro__c'] = $this->replaceMailMgzFlag($row->via_mailmaga_flag);

        return [
            'import' => $import,
        ];
    }

    /**
     * オーダーIDがSFに存在するか確認
     *
     * @param object $row 連携データ
     * @return string オーダーID
     */
    private function checkExistsOrder($row)
    {
        $logTable = "(select * from woa_matching where id = {$row->id}\G)";
        // SFからオーダー情報を取得
        $sfOrder = $this->sfOrderMgr->getOrderById($row->order_salesforce_id);

        if (empty($sfOrder)) {
            $this->logger->error("連携失敗 ID: {$row->id}: コメディカルオーダーオブジェクトに SFID: {$row->order_salesforce_id} が存在しない、または、複数存在します。 $logTable");

            return '';
        }

        return $sfOrder->id;
    }

    /**
     * SFから求職者情報を取得
     *
     * @param array $row 連携対象データ
     * @return array SF求職者情報
     */
    private function getSfCustomer($row)
    {
        $logTable = "(select * from woa_matching where id = {$row->id}\G)";

        // SFから求職者情報を取得
        $sfCustomer = $this->sfCustomerMgr->getCustomerById($row->salesforce_id);
        if (empty($sfCustomer)) {
            $this->logger->error("連携失敗 ID: {$row->id}: コメディカル求職者オブジェクトに SFID: {$row->salesforce_id} が存在しない、または、複数存在します。 $logTable");

            return [];
        }

        // 取得したSF求職者の所有者がメール配信除外キューの時エラーにする
        if (substr($sfCustomer->owner, 0, 15) == $this->sfCustomerMgr::SFID_MAIL_DELIVERY_EXCLUSION_QUEUE) {
            $this->logger->error("連携失敗 ID: {$row->id}: 所有者がメール配信除外キューです $logTable");

            return [];
        }

        return [
            'ownerId' => $sfCustomer->owner,
        ];
    }

    /**
     * 受注までのステータスの文字列化
     *
     * @param int $entryStatus 受注までのステータスID
     * @return string 受注までのステータス名
     */
    private function replaceEntryStatus($entryStatus)
    {
        switch ($entryStatus) {
            case "0":
                return "求人エントリー";
            case "1":
                return "求人詳細情報問合せ";
            default:
                return "";
        }
    }

    /**
     * メルマガ経由登録フラグの文字列化
     *
     * @param int $mailmgzFlag メルマガ経由登録フラグ
     * @return string 獲得経路
     */
    private function replaceMailMgzFlag($mailmgzFlag)
    {
        switch ($mailmgzFlag) {
            case "1":
                return "メルマガ";
            default:
                return "";
        }
    }

    /**
     * SF連携の戻り値判定
     *
     * @param array|null $result
     * @param integer $sfFlag
     * @param string $logTable
     * @return integer
     */
    private function chkSFInsert(?array $result, int $sfFlag, string $logTable): int
    {
        if (empty($result['success'])) {
            $sfFlag = 2;
            $this->logger->info(var_export($result, true));
            $this->logger->error("SF連携のForrestが戻り値で失敗を返しました。ログを確認してください。\n" . $logTable);
        }

        return $sfFlag;
    }
}
