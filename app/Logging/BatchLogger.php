<?php

namespace App\Logging;

use Carbon\Carbon;
use Log;
use Mail;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Sentry\State\HubInterface;
use Slack;
use App\Managers\UtilManager;

/**
 * バッチ用ロガー
 */
class BatchLogger
{
    private $logger;
    private $batchName;
    private $targetTable;
    private $targetFile;
    private $pid;
    private $siteName;
    private $envName;
    private $errors = [];

    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     */
    public function __construct($batchName, $logPath, $targetTable = null, $targetFile = null)
    {
        $formatter = new LineFormatter(null, 'Y-m-d H:i:s', false, true);
        $formatter->includeStacktraces(true);

        $handler = new StreamHandler(storage_path('logs/') . $logPath);
        $handler->setFormatter($formatter);

        $this->logger = new Logger($batchName);
        $this->logger->pushHandler($handler);

        $this->batchName = $batchName;
        $this->targetTable = $targetTable;
        $this->targetFile = $targetFile;
        $this->pid = getmypid();
        $this->siteName = strtoupper(config('ini.SITE_NAME'));

        $this->utilMgr = new UtilManager();
        $this->envName = $this->utilMgr->getEnvName();
    }

    /**
     * DEBUGログ出力
     *
     * @param strings $message メッセージ
     */
    public function debug($message)
    {
        $this->logger->debug("{$this->pid}: {$message}");
    }

    /**
     * INFOログ出力
     *
     * @param strings $message メッセージ
     */
    public function info($message)
    {
        $this->logger->info("{$this->pid}: {$message}");
    }

    /**
     * NOTICEログ出力
     *
     * @param strings $message メッセージ
     */
    public function notice($message)
    {
        $this->logger->notice("{$this->pid}: {$message}");
    }

    /**
     * WARNINGログ出力
     *
     * @param strings $message メッセージ
     */
    public function warning($message)
    {
        $this->logger->warning("{$this->pid}: {$message}");
    }

    /**
     * ERRORログ出力
     *
     * @param strings $message メッセージ
     * @param bool $laravelLog laravel.log出力フラグ
     */
    public function error($message, $laravelLog = false)
    {
        $this->logger->error("{$this->pid}: {$message}");

        $this->errors[] = $message;

        if ($laravelLog) {
            // logmonで拾われる様にlaravel.logの方へも出力
            Log::error("{$this->batchName}: {$this->pid}: {$message}");
        }
    }

    /**
     * エラー件数を返す
     *
     * @return int エラー件数
     */
    public function countError()
    {
        return count($this->errors);
    }

    /**
     * Slackエラー通知
     *
     * @param strings $channel Slack通知チャンネル
     */
    public function notifyErrorToSlack($channel)
    {
        // 環境毎の通知判定
        if (!$this->utilMgr->isErrorNotify()) {
            return;
        }

        $text = implode("\n", $this->errors);

        $message = <<<EOS
```
{$text}
```
EOS;

        // Slackへ通知
        Slack::channel($channel)->send($message);
    }

    /**
     * Sentryエラー通知
     */
    public function notifyErrorToSentry()
    {
        $data = [
            'batch_name' => $this->batchName,
            'pid' => $this->pid,
            'date' => Carbon::now()->format('Y-m-d H:i:s'),
            'target_table' => $this->targetTable,
            'target_file' => $this->targetFile,
            'errors' => $this->errors,
        ];

        // sentryにエラー通知
        $hub = app(HubInterface::class);
        if ($hub) {
            $hub->captureException(new \Exception(print_r($data, true)));
        }
    }
}
