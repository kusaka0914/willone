<?php

namespace App\Console\Commands;

use App\Logging\BatchLogger;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Slack;

/**
 * SF連携フラグアラート通知
 */
class SfFlagAlertNotifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SfFlagAlertNotifier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SF連携フラグアラート通知';

    private $logger;
    private $slackChanne;

    // SF連携フラグ監視テーブル
    private $tableNames = [
        'employ_inquiry',
    ];

    // 区切り文字
    private $colDelimiter = "\t";
    private $rowDelimiter = "\n";

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

        $this->slackChannel = $classNameSnake;
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

        // 通知内容生成
        $text = $this->buildText();

        // 通知する情報がなければ終了する
        if (empty($text)) {
            $this->logger->info('処理終了: 通知対象無し');
            return 0;
        }

        // メッセージ生成
        $message = $this->buildMessage($text);

        // Slack通知
        Slack::channel($this->slackChannel)->send($message);

        $this->logger->info('処理終了');

        return 0;
    }

    /**
     * 通知内容生成
     *
     * @return string 通知内容
     */
    private function buildText()
    {
        // 通知内容初期化
        $texts = [];
        // 通知内容を生成する（2：連携エラー）
        $texts[] = $this->extractContent(2);
        // 通知内容を生成する（0：未連携）
        $texts[] = $this->extractContent(0);

        // 通知内容を結合する
        $text = implode($this->rowDelimiter, $texts);

        // 通知内容がなければ空を返す
        if (empty(str_replace($this->rowDelimiter, '', $text))) {
            return '';
        }

        return $text;
    }

    /**
     * 通知内容生成
     *
     * @param int $sfFlag SF連携フラグ
     * @return string 通知内容
     */
    private function extractContent($sfFlag)
    {
        $contents = [];

        $contents[] = '```';

        // タイトル
        $contents[] = $this->buildTitle($sfFlag);

        // ヘッダー
        $contents[] = $this->buildHeader();

        // クエリ
        $query = $this->buildQuery($sfFlag);

        // クエリ実行
        $result = DB::select($query);

        // 該当データがなければ空を返す
        if (empty($result)) {
            return '';
        }

        foreach ($result as $row) {
            $contents[] = implode($this->colDelimiter, (array) $row);
        }

        $contents[] = '```';

        return implode($this->rowDelimiter, $contents);
    }

    /**
     * タイトル生成
     *
     * @param int $sfFlag SF連携フラグ
     * @return string タイトル
     */
    private function buildTitle($sfFlag)
    {
        $sfFlagTable = [
            0 => '========   　未連携　 （salesforce_flag = 0）   ========',
            1 => '========   　連携済　 （salesforce_flag = 1）   ========',
            2 => '========   連携エラー （salesforce_flag = 2）   ========',
        ];

        return $sfFlagTable[$sfFlag];
    }

    /**
     * ヘッダー生成
     *
     * @return string ヘッダー
     */
    private function buildHeader()
    {
        $columnNames = [
            'テーブル名',
            'ID',
            '更新日時',
        ];

        return implode($this->colDelimiter, $columnNames);
    }

    /**
     * メッセージ生成
     *
     * @param string $text 通知内容
     * @return string メッセージ
     */
    private function buildMessage($text)
    {
        $dbName = env('DB_DATABASE');

        $message = <<<EOS
WEBからSalesforceのデータ連携で要確認データが存在しています。
【{$dbName}】の以下のデータを確認ください。
対応チーム `Sirius`
{$text}
EOS;

        return $message;
    }

    /**
     * クエリ生成
     *
     * @param int $sfFlag SF連携フラグ
     * @return string クエリ
     */
    private function buildQuery($sfFlag)
    {
        $queries = [];

        foreach ($this->tableNames as $tableName) {
            // SELECT句
            $columns = [
                "'{$tableName}' as table_name",
                'id',
                'update_date',
            ];
            $selectSql = implode(', ', $columns);

            $queries[] = <<<QUERY
(
SELECT
    {$selectSql}
FROM
    {$tableName}
WHERE
    salesforce_flag = {$sfFlag}
)
QUERY;
        }

        return implode("{$this->rowDelimiter}UNION{$this->rowDelimiter}", $queries);
    }
}
