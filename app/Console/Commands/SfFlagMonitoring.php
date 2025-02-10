<?php

namespace App\Console\Commands;

use App\Logging\BatchLogger;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Slack;

/**
 * SF連携フラグ監視
 */
class SfFlagMonitoring extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SfFlagMonitoring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SF連携フラグ監視';

    private $logger;
    private $slackChannel;

    // SF連携フラグ監視テーブル
    private $tables = [
        'woa_customer',
        'woa_customer_digs',
        'woa_matching',
    ];

    // ヘッダー
    private $headers = [
        'table_name',
        'update_time',
        'salesforce_flag_NOT_0',
        'salesforce_flag_2',
        'salesforce_flag_0',
    ];

    // 区切り文字
    private $rowDelimiter = "\n";
    private $colDelimiter = "\t";

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

        // ヘッダー
        $contents[] = implode($this->colDelimiter, $this->headers);

        // SF連携フラグ集計
        foreach ($this->tables as $table) {
            $aggregateSfFlag = $this->getAggregateSfFlag($table);
            $contents = array_merge($contents, $aggregateSfFlag);
        }

        $text = implode($this->rowDelimiter, $contents);

        // メッセージ生成
        $message = $this->makeMessage($text);

        // Slack通知
        Slack::channel($this->slackChannel)->send($message);

        $this->logger->info('処理終了');

        return 0;
    }

    /**
     * SF連携フラグ集計
     *
     * @param string $table テーブル名
     * @return array 集計結果
     */
    private function getAggregateSfFlag($table)
    {
        $builder = DB::table("{$table}")
            ->selectRaw("'{$table}' AS table_name")
            ->selectRaw("'ALL' AS update_time")
            ->selectRaw("CONCAT(SUM(CASE WHEN salesforce_flag <> 0 AND salesforce_flag <> 2 THEN 1 ELSE 0 END)) AS salesforce_flag_not_0")
            ->selectRaw("CONCAT(SUM(CASE WHEN salesforce_flag = 2 THEN 1 ELSE 0 END)) AS salesforce_flag_2")
            ->selectRaw("CONCAT(SUM(CASE WHEN salesforce_flag = 0 THEN 1 ELSE 0 END)) AS salesforce_flag_0");

        $rows = DB::table("{$table}")
            ->selectRaw("'{$table}' AS table_name")
            ->selectRaw("DATE_FORMAT(update_date, '%m-%d') AS update_time")
            ->selectRaw("CONCAT(SUM(CASE WHEN salesforce_flag <> 0 AND salesforce_flag <> 2 THEN 1 ELSE 0 END)) AS salesforce_flag_not_0")
            ->selectRaw("CONCAT(SUM(CASE WHEN salesforce_flag = 2 THEN 1 ELSE 0 END)) AS salesforce_flag_2")
            ->selectRaw("CONCAT(SUM(CASE WHEN salesforce_flag = 0 THEN 1 ELSE 0 END)) AS salesforce_flag_0")
            ->whereRaw("update_date > (CURDATE() - INTERVAL 6 DAY)")
            ->groupByRaw("update_time")
            ->union($builder)
            ->get();

        if (empty($rows)) {
            return [];
        }

        $result = [];
        foreach ($rows as $row) {
            $result[] = implode($this->colDelimiter, (array) $row);
        }

        return $result;
    }

    /**
     * メッセージ生成
     *
     * @param string $text 通知内容
     * @return string メッセージ
     */
    private function makeMessage($text)
    {
        $message = <<<EOS
各テーブルの[salesforce_flag]の値の集計しています。

【レポートの見方】
・直近１週間分のステータスを集計しています。
・各項目の内容
[salesforce_flag_NOT_0] = 値が0,2以外（連携成功数）
[salesforce_flag_2] = 値が2（連携エラー数）
[salesforce_flag_0] = 値が0（未連携）

！！！注意！！！
連携エラー[2]エラーが残っていたり、未連携[0]データが大量に残っていたら
サイトの状況を確認するようにしてください。

```
{$text}
```
EOS;

        return $message;
    }
}
