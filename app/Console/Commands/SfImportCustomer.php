<?php

namespace App\Console\Commands;

use App\Console\Commands\CommandUtility;
use App\Logging\BatchLogger;
use App\Managers\SfCustomerManager;
use App\Managers\SfManager;
use App\Model\MasterAddr1Mst;
use App\Model\MasterAddr2Mst;
use App\Model\MasterConsultantMst;
use App\Model\MasterEntryCourseMst;
use App\Model\WoaCustomer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Omniphx\Forrest\Exceptions\SalesforceException;

/**
 * 求職者SF連携
 */
class SfImportCustomer extends Command
{
    use CommandUtility;

    //新卒・既卒判定年齢
    const JUDGE_AGE = 23;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SfImportCustomer {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '求職者SF連携';

    private $logger;
    private $conf;
    private $slackChanne;
    private $woaCustomer;
    private $masterAddr1;
    private $masterAddr2;
    private $masterEntryCourse;
    private $sfMgr;
    private $prefNames;
    private $cityNames;

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

        $this->woaCustomer = new WoaCustomer();
        $this->masterAddr1 = new MasterAddr1Mst();
        $this->masterAddr2 = new MasterAddr2Mst();
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
        self::asyncCommand("SfImportCustomer {$id}");
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
        $rows = $this->woaCustomer->getSfLinkData($id);
        if ($rows->isEmpty()) {
            $this->logger->info('処理終了: 連携対象無し');

            return 0;
        }

        // 住所マスター取得
        $this->prefNames = $this->masterAddr1->getPrefNames();
        $this->cityNames = $this->masterAddr2->getCityNames();

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
                $logTable = "(select * from woa_customer where id = {$row->id}\G)";
                $sfFlag = 1;
                // SFインポートデータ作成
                $import = $this->makeImportData($row);

                // 必須項目チェック
                if ($this->checkRequireColumn($import)) {
                    // SF登録
                    $result = $this->sfMgr->insert('kjb_customer__c', $import);
                    // SF連携の戻り値判定
                    $sfFlag = $this->chkSFInsert($result, $sfFlag, $logTable);
                } else {
                    // 必須項目無しはエラー
                    $sfFlag = 2;
                }
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
                $this->logger->error("登録失敗 ID: {$row->id}: {$e->getMessage()}: {$e->getFile()}:{$e->getLine()}\n{$e->getTraceAsString()}\n{$logTable}", true);
                $sfFlag = 2;
            } finally {
                // SF連携フラグ更新
                $this->woaCustomer->updateSfFlag($row->id, $sfFlag);
            }
        }
    }

    /**
     * SFインポートデータ作成
     *
     * @param object $row 連携対象データ
     * @return array インポートデータ
     */
    private function makeImportData($row)
    {
        // 設定ファイルから連携項目をセット
        $import = [];
        foreach ($this->conf['link_columns'] as $key => $val) {
            $import[$key] = $row->$val;
        }

        // サイト名
        $import['web_site__c'] = strtoupper(config('ini.SITE_NAME'));

        // 都道府県
        $import['addr1__c'] = $this->prefNames[$row->addr1] ?? null;

        // 市区町村
        $import['addr2__c'] = $this->cityNames[$row->addr2] ?? null;

        // SFの+9時間の形式へ変換
        $updateDate = Carbon::parse($row->update_date)->toW3cString();

        // 更新日時
        $import['update_date__c'] = $updateDate;

        // 最終登録日時
        $import['lastEntryDateTime__c'] = $updateDate;

        // 生年
        if (!empty($row->birth)) {
            $import['birth__c'] = Carbon::parse($row->birth)->toDateString();
        }

        // 登録カテゴリ
        if (!empty($row->entry_category_manual)) {
            $entryCategory = $row->entry_category_manual;
        } else {
            $entryCategory = $this->masterEntryCourse->getEntryCategory($row->action_first);
        }
        $import['entry_category__c'] = $entryCategory;

        // 測定用項目１ (転職意欲)
        if (!empty($row->service_usage_intention)) {
            $import['tmp_value_1__c'] = $row->service_usage_intention;
        }
        // 測定用項目２ (既卒新卒)
        $graduationLicense = '既卒';
        if ($row->graduation_year == 'その他') {
            // 黒本ユーザー対応
            $import['KC_graduation_year__c'] = '不明';
            if (!empty($row->birth) && Carbon::parse($row->birth)->age < self::JUDGE_AGE) {
                // 「年齢」が22歳以下の場合：新卒
                $graduationLicense = '新卒';
            }
        } elseif ($row->graduation_year == '既卒') {
            // 黒本ユーザー対応
            $import['KC_graduation_year__c'] = '';
        } elseif (!empty($row->graduation_year) && preg_match("/20[0-9]{2}/", $row->graduation_year)) {
            //卒業予定年の4月までは新卒
            $graduationDate = substr($row->graduation_year, 0, 4) . '-05-01 00:00:00';
            $graduationLicense = $graduationDate > $row->regist_date ? '新卒' : '既卒';
        } elseif (!empty($row->birth) && Carbon::parse($row->birth)->age < self::JUDGE_AGE) {
            // 「年齢」が22歳以下の場合：新卒
            $graduationLicense = '新卒';
        }
        $import['tmp_value_2__c'] = $graduationLicense;
        // 測定用項目4 (保有資格)
        if (!empty($row->license)) {
            $nationalLicense = '';
            if (preg_match('/^(?=.*あん摩マッサージ指圧師)/', $row->license)) {
                $nationalLicense = 'あん摩マッサージ指圧師';
            } elseif (preg_match('/^(?=.*柔道整復師)/', $row->license)) {
                $nationalLicense = '柔道整復師';
            } elseif (preg_match('/^(?=.*鍼灸師)/', $row->license)) {
                $nationalLicense = '鍼灸師';
            } else {
                $nationalLicense = '整体師';
            }
            $import['tmp_value_4__c'] = $nationalLicense;
        }

        // 連携元顧客IDがWebに登録されている時
        if (!empty($row->src_customer_id)) {
            $entryNote = trim($row->src_site_name) . 'ID：' . trim($row->src_customer_id);
            if (!empty($row->entry_memo)) {
                // 応募情報（entry_memo）に値が設定されている場合は、先頭にカンマ付きで付与
                $entryNote = trim($row->entry_memo) . ', ' . $entryNote;
            }
            // 登録時情報備考（ejbtourokujijouhou__c）にデータ連携
            $import['ejbtourokujijouhou__c'] = $entryNote;
        }

        $import = $this->setReferred($row, $import);

        return $import;
    }

    /**
     * 必須項目チェック
     *
     * @param array $import インポートデータ
     * @return bool チェック結果
     */
    private function checkRequireColumn($import)
    {
        foreach ($this->conf['require_columns'] as $val) {
            if (empty($import[$val])) {
                $this->logger->error("登録失敗 ID: {$import['web_customer_id__c']}: {$val}が取得できませんでした (select * from woa_customer where id = {$import['web_customer_id__c']}\G)");

                return false;
            }
        }

        return true;
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

    /**
     * friend_referralのデータをSF連携用に設定
     *
     * @param object $row
     * @param array $import
     * @return array
     */
    private function setReferred(object $row, array $import): array
    {

        // 求職者ID紹介CPID・氏名
        if (!empty($row->cp_sms_id)) {
            $import['KC_referredCPID__c'] = substr($row->cp_sms_id, 0, 18);
            $resultConsultant = (new MasterConsultantMst)->findBySmsId(substr($row->cp_sms_id, 0, 15));
            if ($resultConsultant) {
                $import['KC_referredCPName__c'] = $resultConsultant->name ?? '紹介CPID不正';
            }
        }

        // 求職者ID紹介者設定
        // 'KC_referredUserName__c'は"設定ファイルから連携項目をセット"の処理で事前に設定されている
        if ($row->referral_salesforce_id) {
            $referralSfId18Length = substr($row->referral_salesforce_id, 0, 18);
            $import['KC_referredUserId__c'] = $referralSfId18Length;
            // 事前に設定されてない場合はSFから取得
            if (empty($import['KC_referredUserName__c'])) {
                $referralSfCustomer = (new SfCustomerManager)->getCustomerById($referralSfId18Length);
                if (!empty($referralSfCustomer)) {
                    if (!$referralSfCustomer->delete_personal_info_flag) {
                        $import['KC_referredUserName__c'] = $referralSfCustomer->name_kan;
                    }
                }
            }
        }

        return $import;
    }
}
