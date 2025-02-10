<?php

namespace App\Services;

use App\Mail\MailSender;
use App\Managers\UtilManager;
use Mail;
use Slack;

/**
 * 通知系のサービス
 */
class NotificationService
{

    private $utilManager;

    public function __construct()
    {
        $this->utilManager = new UtilManager();
    }

    /**
     * Slackエラー通知
     * 簡易メッセージ送信ですがExceptionのtraceを出力したい場合は引数に追加するなりしてください。
     *
     * @param string $channel Slack通知チャンネル
     * @param string $msg
     * @return void
     */
    public function sendSlack(string $channel, string $msg): void
    {
        // 環境毎の通知判定
        if (!$this->utilManager->isErrorNotify()) {
            return;
        }

        // Slackへ通知
        Slack::channel($channel)->send('```' . $msg . '```');
    }

    /**
     * exception発生しない処理で簡易メッセージをBacklogへ通知
     * exceptionが発生する処理はなるべくExceptionを握りつぶさないで
     * \App\Exceptions\Handlerへthrowしてください
     * @param string $msg
     * @return void
     */
    public function sendBacklog(string $msg): void
    {
        // 環境毎の通知判定
        if (!$this->utilManager->isErrorNotify()) {
            return;
        }
        $envName = $this->utilManager->getEnvName();
        $siteName = strtoupper(config('ini.SITE_NAME'));

        $subject = "【{$envName}】【{$siteName}】エラー通知";

        // Backlogへ通知(課題登録)
        Mail::to(config('mail.backlog_mail'))->send(new MailSender(
            [
                'from'      => config('mail.admin_mail'),
                'from_name' => 'エラー通知',
                'subject'   => $subject,
                'template'  => 'mails.error_mail',
            ],
            [
                'message' => $msg,
            ]
        ));
    }
}
