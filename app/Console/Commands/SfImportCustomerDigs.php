<?php

namespace App\Console\Commands;

use App\Console\Commands\CommandUtility;
use App\Logging\BatchLogger;
use App\Managers\SfCustomerManager;
use App\Managers\SfManager;
use App\Model\MasterEntryCourseMst;
use App\Model\WoaCustomerDigs;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Omniphx\Forrest\Exceptions\SalesforceException;

/**
 * 掘起しSF連携
 */
class SfImportCustomerDigs extends Command
{
    use CommandUtility;

    //新卒・既卒判定年齢
    const JUDGE_AGE = 23;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SfImportCustomerDigs {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '掘起しSF連携';

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

        $this->woaCustomerDigs = new WoaCustomerDigs();
        $this->masterEntryCourse = new MasterEntryCourseMst();
        $this->sfMgr = new SfManager();
        $this->sfCustomerMgr = new SfCustomerManager();
    }

    /**
     * 即時連携実行
     *
     * @return void
     */
    public static function startId($id)
    {
        self::asyncCommand("SfImportCustomerDigs {$id}");
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
        $rows = $this->woaCustomerDigs->getSfLinkData($id);
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
                $logTable = "(select * from woa_customer_digs where id = {$row->id}\G)";
                $sfFlag = 1;
                $sfIdReal = null;
                // SFインポートデータ作成
                $import = $this->makeImportData($row);

                if (!empty($import)) {
                    $sfIdReal = $import['sfIdReal'];
                    // SF更新
                    $this->sfMgr->update('kjb_customer__c', $import['sfId'], $import['import']);
                } else {
                    // 更新対象が無い場合エラー
                    $sfFlag = 2;
                }
            } catch (SalesforceException $e) {
                $msg = json_decode($e->getMessage());
                if (is_null($msg)) {
                    // json_decodeに失敗した場合はデコードしない
                    $this->logger->error("SalesforceException平文: 更新失敗 ID: {$row->id}: {$e->getMessage()}\n" . $logTable);
                    $this->logger->info("{$e->getFile()}: {$e->getLine()}\n{$e->getTraceAsString()}");
                } else {
                    // json_decodeできた場合
                    $this->logger->error("SalesforceException配列: 更新失敗 ID: {$row->id}:\n" . print_r($msg, true) . "\n" . $logTable);
                }
                $sfFlag = 2;
            } catch (\Exception $e) {
                $this->logger->error("更新失敗 ID: {$row->id}: {$e->getMessage()}: {$e->getFile()}:{$e->getLine()}\n{$e->getTraceAsString()}\n{$logTable}", true);
                $sfFlag = 2;
            } finally {
                if (!empty($sfIdReal)) {
                    $this->logger->info("ID: {$row->id} 求職者の所有者がメール配信除外キューの為、求職者の実際の SFID: {$sfIdReal} に変更しました");
                }
                // SF連携フラグ更新
                $this->woaCustomerDigs->updateSfFlag($row->id, $sfFlag, $sfIdReal);
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

        // SFの+9時間の形式へ変換
        $updateDate = Carbon::parse($row->update_date)->toW3cString();

        // 更新日時
        $import['update_date__c'] = $updateDate;

        // 最終登録日時
        $import['lastEntryDateTime__c'] = $updateDate;

        // 希望就業形態
        if (!empty($row->req_emp_type)) {
            $import['req_emp_type__c'] = $row->req_emp_type;
        }

        // 入職希望時期
        if (!empty($row->req_date)) {
            $import['req_date__c'] = $row->req_date;
        }

        // 退職意向
        if (!empty($row->retirement_intention)) {
            $import['KC_retirement_intention__c'] = $row->retirement_intention;
        }

        // 登録経路
        $listRoute = config('ini.listRoute');
        if (isset($row->entry_route) && isset($listRoute[$row->entry_route])) {
            $import['entry_course_category__c'] = $listRoute[$row->entry_route];
        }

        // 登録カテゴリ
        $import['entry_category__c'] = $this->masterEntryCourse->getEntryCategory($row->action_first);

        // SFから求職者情報を取得
        $sfCustomer = $this->getSfCustomer($row);
        if (empty($sfCustomer)) {
            return [];
        }

        // 測定用項目２ (既卒新卒)
        $graduationLicense = '既卒';

        if (!empty($sfCustomer['graduationYear']) && preg_match("/20[0-9]{2}/", $sfCustomer['graduationYear'])) {
            //卒業予定年の4月までは新卒
            $graduationDate = substr($sfCustomer['graduationYear'], 0, 4) . '-05-01 00:00:00';
            $graduationLicense = $graduationDate > $row->regist_date ? '新卒' : '既卒';
        } elseif (!empty($sfCustomer['age']) && $sfCustomer['age'] < self::JUDGE_AGE) {
            // 「年齢」が22歳以下の場合：新卒
            $graduationLicense = '新卒';
        }
        $import['tmp_value_2__c'] = $graduationLicense;


        // 測定用項目4 (保有資格)
        if (!empty($sfCustomer['license'])) {
            $nationalLicense = '';
            if (preg_match('/^(?=.*あん摩マッサージ指圧師)/', $sfCustomer['license'])) {
                $nationalLicense = 'あん摩マッサージ指圧師';
            } elseif (preg_match('/^(?=.*柔道整復師)/', $sfCustomer['license'])) {
                $nationalLicense = '柔道整復師';
            } elseif (preg_match('/^(?=.*鍼灸師)/', $sfCustomer['license'])) {
                $nationalLicense = '鍼灸師';
            } else {
                $nationalLicense = '整体師';
            }
            $import['tmp_value_4__c'] = $nationalLicense;
        }

        // 電子認証まで行っている求職者の基本情報は更新しない
        if ($sfCustomer['nonWebUpdateFlag']) {
            unset($import['mail__c']);
        }

        return [
            'sfId'     => $sfCustomer['sfId'],
            'sfIdReal' => $sfCustomer['sfIdReal'],
            'import'   => $import,
        ];
    }

    /**
     * SFから求職者情報を取得
     *
     * @param array $row 連携対象データ
     * @return array SF求職者情報
     */
    private function getSfCustomer($row)
    {
        $logTable = "(select * from woa_customer_digs where id = {$row->id}\G)";
        // SFから求職者情報を取得
        $sfCustomer = $this->sfCustomerMgr->getCustomerById($row->salesforce_id);
        if (empty($sfCustomer)) {
            $this->logger->error("更新失敗 ID: {$row->id}: コメディカル求職者オブジェクトに SFID: {$row->salesforce_id} が存在しません $logTable");

            return [];
        }

        $sfId = $sfCustomer->id;
        $sfIdReal = null;
        $license = $sfCustomer->license;
        $nonWebUpdateFlag = $sfCustomer->non_web_update_flag;
        $age = $sfCustomer->age;
        $graduationYear = $sfCustomer->graduation_year;

        // 取得したSF求職者の所有者がメール配信除外キューの時は、メールアドレスか電話番号でSF求職者IDを再検索
        if (substr($sfCustomer->owner, 0, 15) == $this->sfCustomerMgr::SFID_MAIL_DELIVERY_EXCLUSION_QUEUE) {
            if ((empty($row->mail) && empty($row->tel)) || empty($row->web_customer_id)) {
                $this->logger->error("更新失敗 ID: {$row->id}: メールアドレスまたは電話番号、Web求職者IDが存在しません $logTable");

                return [];
            }

            $sfCustomer = $this->sfCustomerMgr->getCustomerByMailOrTel($row->mail, $row->tel, $row->web_customer_id);

            if (empty($sfCustomer)) {
                $this->logger->error("更新失敗 ID: {$row->id}: コメディカル求職者オブジェクトに対象データが存在しません $logTable");

                return [];
            } elseif ($sfCustomer->count() > 1) {
                $this->logger->error("更新失敗 ID: {$row->id}: コメディカル求職者オブジェクトに対象データが複数存在しています $logTable");

                return [];
            }

            $sfCustomer = $sfCustomer[0];

            // 再検索した値をセット
            $sfId = $sfCustomer->id;
            $sfIdReal = $sfCustomer->id;
            $license = $sfCustomer->license;
            $nonWebUpdateFlag = $sfCustomer->non_web_update_flag;
            $age = $sfCustomer->age;
            $graduationYear = $sfCustomer->graduation_year;
        }

        return [
            'sfId'             => $sfId,
            'sfIdReal'         => $sfIdReal,
            'license'          => $license,
            'nonWebUpdateFlag' => $nonWebUpdateFlag,
            'age'              => $age,
            'graduationYear'   => $graduationYear,
        ];
    }
}
