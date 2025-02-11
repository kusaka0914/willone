<?php

namespace App\Console\Commands;

use App\Console\Commands\CommandUtility;
use App\Logging\BatchLogger;
use App\Managers\SfManager;
use App\Model\EmployInquiry;
use App\Model\MasterAddr1Mst;
use App\Model\MasterEntryCourseMst;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Omniphx\Forrest\Exceptions\SalesforceException;

/**
 * メール引合SF連携
 */
class SfImportAccountMail extends Command
{
    use CommandUtility;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SfImportAccountMail {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'メール引合SF連携';

    private $logger;
    private $conf;
    private $slackChanne;
    private $employInquiry;
    private $masterAddr1;
    private $masterAddr2;
    private $masterEntryCourse;
    private $sfMgr;
    private $prefNames;

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

        $this->employInquiry = new EmployInquiry();
        $this->masterAddr1 = new MasterAddr1Mst();
        $this->masterEntryCourse = new MasterEntryCourseMst();
        $this->sfMgr = new SfManager();
    }

    /**
     * 即時連携実行
     *
     * @return void
     */
    public static function startId($id)
    {
        self::asyncCommand("SfImportAccountMail {$id}");
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
        $rows = $this->employInquiry->getSfLinkDataForAccountMail($id);
        if ($rows->isEmpty()) {
            $this->logger->info('処理終了: 連携対象無し');

            return 0;
        }

        // 住所マスター取得
        $this->prefNames = $this->masterAddr1->getPrefNames();

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
                $logTable = "(select * from employ_inquiry where id = {$row->id}\G)";
                $sfFlag = 1;
                // SFインポートデータ作成
                $import = $this->makeImportData($row);
                // SF登録
                $result = $this->sfMgr->insert('mail_lead__c', $import);
                // SF連携の戻り値判定
                $sfFlag = $this->chkSFInsert($result, $sfFlag, $logTable);
            } catch (SalesforceException $e) {
                $msg = json_decode($e->getMessage());
                if (is_null($msg)) {
                    // json_decodeに失敗した場合はデコードしない
                    $this->logger->error("SalesforceException平文: 登録失敗 ID: {$row->id}: {$e->getMessage()}\n" . $logTable);
                    $this->logger->info("{$e->getFile()}: {$e->getLine()}\n{$e->getTraceAsString()}");
                } else {
                    // json_decodeできた場合
                    $this->logger->error("SalesforceException配列: 登録失敗 ID: {$row->id}:\n" . print_r($msg, true) . "\n" . $logTable);
                }
                $sfFlag = 2;
            } catch (\Exception $e) {
                $this->logger->error("登録失敗 ID: {$row->id}: {$e->getMessage()}: {$e->getFile()}:{$e->getLine()}\n{$e->getTraceAsString()}\n$logTable", true);
                $sfFlag = 2;
            } finally {
                // SF連携フラグ更新
                $this->employInquiry->updateSfFlag($row->id, $sfFlag);
            }
        }
    }

    /**
     * SFインポートデータ作成
     *
     * @param array $row 連携対象データ
     * @return array インポートデータ
     */
    private function makeImportData($row)
    {
        // 配信停止と事業所メルマガ登録で連携項目の読み分け
        if (!empty($row->stop_reason)) {
            $linkColumns = $this->conf['stop_link_columns'];
            $type = '配信停止';
        } else {
            $linkColumns = $this->conf['link_columns'];
            $type = '引合';
        }

        // 設定ファイルから連携項目をセット
        $import = [];
        foreach ($linkColumns as $key => $val) {
            $import[$key] = $row->$val;
        }

        // 引合種別
        $import['hikiaishubetsu__c'] = $type;

        // サイト名
        $import['service_mc__c'] = strtoupper(config('ini.SITE_NAME'));

        // 都道府県
        $import['state__c'] = $this->prefNames[$row->addr1] ?? null;

        // メール引合日時 SFの+9時間の形式へ変換
        $import['hikiai_datetime__c'] = Carbon::parse($row->regist_date)->toW3cString();

        // お問合せ内容
        if (!empty($row->inquiry)) {
            $inquiryConf = config('ini.INQUIRY');
            // コード値を文字列に変換
            $import['Inquiry__c'] = $this->convertCodeToString($row->inquiry, $inquiryConf);
        }

        // 電話希望時間
        if (!empty($row->tel_time_id)) {
            $reqTelTimeConf = config('ini.REQ_CALL_TIME');
            // コード値を文字列に変換
            $import['tel_time__c'] = $this->convertCodeToString($row->tel_time_id, $reqTelTimeConf);
        }

        return $import;
    }

    /**
     * 区切り文字で連結されたコード値を文字列に変換
     *
     * @param string $code コード値
     * @param array $codeConf コード設定値 (key: コード値, val: 文字列)
     * @param string $delimiter 区切り文字
     * @return string コード値変換文字列
     */
    private function convertCodeToString($code, $codeConf, $delimiter = ';')
    {
        $codes = explode($delimiter, $code);
        $codeStrs = [];
        foreach ($codes as $val) {
            if (isset($codeConf[$val])) {
                $codeStrs[] = $codeConf[$val];
            }
        }

        return implode($delimiter, $codeStrs);
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
