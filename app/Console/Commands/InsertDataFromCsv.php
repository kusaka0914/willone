<?php

namespace App\Console\Commands;

use App\Logging\BatchLogger;
use App\Managers\MasterManager;
use Illuminate\Console\Command;
use SplFileObject;

/**
 * 汎用CSVデータ登録
 */
class InsertDataFromCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsertDataFromCsv {iniFilePath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '汎用CSVデータ登録';

    private $csvPath;
    private $tableName;
    private $createSql;
    private $columns;
    private $fromEncoding;
    private $sysDateSetFlg;
    private $logger;
    private $masterMgr;

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

        // 引数取得とiniファイルパス生成
        $iniFilePath = app_path() . '/Console/ini/' . $this->argument('iniFilePath');

        // iniファイル読込み
        require_once $iniFilePath;

        // iniファイル設定値チェック
        if (empty($csvPath) || empty($tableName) || empty($createSql) || empty($columns)) {
            throw new \Exception("{$className}: iniファイル設定値読込み失敗: {$this->iniFilePath}");
        }

        // iniファイルから読込んだ設定値をセット
        $this->csvPath = $csvPath;
        $this->tableName = $tableName;
        $this->createSql = $createSql;
        $this->columns = $columns;
        $this->fromEncoding = $fromEncoding ?? 'SJIS-win';
        $this->sysDateSetFlg = $sysDateSetFlg ?? true;

        $this->logger = new BatchLogger($className, "{$tableName}.log", $tableName, $csvPath);
        $this->masterMgr = new MasterManager();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // 初期化
        $this->init();

        $this->logger->info('処理開始');
        $isSuccess = false;
        try {
            // CSVデータ登録処理
            $isSuccess = $this->insertDataFromCsv();
        } catch (\Exception $e) {
            $this->logger->error("{$e->getMessage()}: {$e->getFile()}:{$e->getLine()}\n{$e->getTraceAsString()}", true);
        }

        // Sentryエラー通知
        if ($this->logger->countError() > 0) {
            $this->logger->notifyErrorToSentry();
        }

        $this->logger->info('処理終了');

        return $isSuccess ? Command::SUCCESS : Command::FAILURE;
    }

    /**
     * CSVデータ登録処理
     * @return bool
     */
    private function insertDataFromCsv(): bool
    {
        // CSVファイル存在チェック
        $s3 = \Storage::disk('s3');
        if (!$s3->exists($this->csvPath)) {
            $this->logger->error("[S3]CSVファイル存在チェック失敗: {$this->csvPath}");

            return false;
        }

        $this->logger->info("[S3]CSVファイル存在チェック成功: {$this->csvPath}");

        // CSVファイル取得
        $csvFile = $s3->get($this->csvPath);

        // CSVファイルの文字コードがUTF-8以外の場合、UTF-8へ変換
        if ($this->fromEncoding != 'UTF-8') {
            $csvFile = mb_convert_encoding($csvFile, 'UTF-8', $this->fromEncoding);
        }

        // 直接S3のファイルをCSV読込みする事は出来ない為、取得したS3のファイルをローカルの一時ファイルへ書込む
        $temp = tmpfile();
        $meta = stream_get_meta_data($temp);
        fwrite($temp, $csvFile);

        // CSVファイル読込み
        $fileObj = new SplFileObject($meta['uri']);
        $fileObj->setFlags(
            SplFileObject::READ_CSV |
            SplFileObject::READ_AHEAD |
            SplFileObject::SKIP_EMPTY
        );

        // CSVファイルデータ件数チェック
        $count = 0;
        foreach ($fileObj as $idx => $lineData) {
            // ヘッダー、空行はスキップ
            if ($idx == 0 || empty($lineData)) {
                continue;
            }
            $count++;
        }

        if ($count < 1) {
            $this->logger->error("CSVファイルデータ件数チェック失敗: データ件数: {$count}: {$this->csvPath}");

            return false;
        }

        $this->logger->info("CSVファイルデータ件数チェック成功: データ件数: {$count}");

        // 一時テーブル削除
        $tmpTableName = 'tmp_' . $this->tableName;
        $result = $this->masterMgr->dropTableIfExists($tmpTableName);

        // 一時テーブル作成
        $tmpCreateSql = str_replace($this->tableName, $tmpTableName, $this->createSql);
        $result = $this->masterMgr->execStatement($tmpCreateSql);
        if (!$result) {
            $this->logger->error("一時テーブル作成失敗: {$tmpTableName}");

            return false;
        }

        $this->logger->info("一時テーブル作成成功: {$tmpTableName}");

        $lineNo = 0;
        foreach ($fileObj as $idx => $lineData) {
            $lineNo++;

            // ヘッダー、空行はスキップ
            if ($idx == 0 || is_null($lineData[0])) {
                continue;
            }

            // 項目数チェック
            $columnsCount = count($this->columns);
            if (count($lineData) != $columnsCount) {
                $this->logger->error("CSVファイル項目数チェック失敗: {$lineNo} 行目の項目数が {$columnsCount} ではありません");

                return false;
            }

            $this->logger->info("CSVファイル項目数チェック成功: {$lineNo} 行目");

            // 文字数チェック
            $rowData = [];
            foreach ($lineData as $k => $v) {
                $limit = $this->columns[$k]['limit'];
                $nameCsv = $this->columns[$k]['name_csv'];
                $nameDb = $this->columns[$k]['name_db'];

                if ($limit > 0 && mb_strlen($v) > $limit) {
                    $this->logger->error("CSVファイル文字数チェック失敗: {$lineNo} 行目の {$nameCsv} が {$limit} 文字を超えています");

                    return false;
                }

                $rowData[$nameDb] = trim($v);
            }

            $this->logger->info("CSVファイル文字数チェック成功: {$lineNo} 行目");

            // ----------------------------------------------------------
            // テーブル毎に値の整形、追加が必要な場合はここに書く
            // master_consultant_mst用の処理
            if ($this->tableName === 'master_consultant_mst') {
                $tmpArr = [];
                foreach ($rowData as $fieldName => $value) {
                    if ($fieldName == 'url_id' && $value == '') {
                        // URL_IDが無ければランダムで８文字生成
                        $tmpArr[$fieldName] = $this->makeRandStr();
                    } else {
                        $tmpArr[$fieldName] = $value;
                    }
                }
                $rowData = $tmpArr;
                $rowData['short_sms_id'] = substr(sha1($rowData['sms_id']), 0, 5);

                // 不要カラム削除
                unset($rowData["cjb_sort"], $rowData["njb_sort"], $rowData["ptot_sort"]);
            }
            // ----------------------------------------------------------

            // データ登録
            $result = $this->masterMgr->insertMasterMst($tmpTableName, $rowData, $this->sysDateSetFlg);
            if (!$result) {
                $this->logger->error("一時テーブルデータ登録失敗: {$lineNo} 行目");

                return false;
            }

            $this->logger->info("一時テーブルデータ登録成功: {$lineNo} 行目");
        }

        // 既存テーブルをバックアップテーブルにリネーム
        $oldTableName = 'old_' . $this->tableName;

        // バックアップテーブル削除
        $result = $this->masterMgr->dropTableIfExists($oldTableName);

        // 既存テーブルリネーム
        $result = $this->masterMgr->renameTable($this->tableName, $oldTableName);
        if (!$result) {
            $this->logger->error("既存テーブルリネーム失敗: {$this->tableName} => {$oldTableName}");

            return false;
        }

        $this->logger->info("既存テーブルリネーム成功: {$this->tableName} => {$oldTableName}");

        // 一時テーブルを正式テーブルにリネーム
        $result = $this->masterMgr->renameTable($tmpTableName, $this->tableName);
        if (!$result) {
            $this->logger->error("一時テーブルリネーム失敗: {$tmpTableName} => {$this->tableName}");

            return false;
        }

        $this->logger->info("一時テーブルリネーム成功: {$tmpTableName} => {$this->tableName}");

        // 書き込み成功したCSVファイルをリネームする
        $oldCsvPath = "{$this->csvPath}.old";
        $result = true;
        if ($s3->exists($oldCsvPath)) {
            // リネーム後のファイルが存在する場合は削除
            $result = $s3->delete($oldCsvPath);
        }
        if ($result) {
            // リネーム後のファイルが存在しない または 削除が成功したらリネーム実行
            $result = $s3->move($this->csvPath, $oldCsvPath);
        }
        if (!$result) {
            // 削除、もしくはリネームが失敗した場合
            $this->logger->error("CSVファイルリネーム失敗: {$this->csvPath} => {$oldCsvPath}");

            return false;
        }

        $this->logger->info("CSVファイルリネーム成功: {$this->csvPath} => {$oldCsvPath}");

        return true;
    }

    /**
     * ランダムな英数字の文字列を生成する
     *
     * @param int $length 生成する文字列の長さ（デフォルトは8文字）
     * @return string 生成されたランダムな英数字の文字列
     */
    private function makeRandStr($length = 8): string
    {
        $str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
        $rStr = "";
        $max_count = count($str) -1;
        for ($i = 0; $i < $length; $i++) {
            $rStr .= $str[mt_rand(0, $max_count)];
        }

        return $rStr;
    }
}
