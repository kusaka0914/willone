<?php

return [
    // Webhook URL
    'url' => env('SLACK_URL'),

    'default' => 'notify_test',

    // チャンネル設定
    'channels' => [
        'notify_test' => [
            'username' => '【WOA】Slack通知テスト',
            'channel' => '#jinzai-notify-test',
        ],
        's3_error' => [
            'username' => '【WOA】S3 Error',
            'channel' => '#jinzai-notify-woa',
        ],
        'sf_import_customer_error' => [
            'username' => '【WOA】求職者SF連携エラー',
            'channel' => '#jinzai-notify-woa',
        ],
        'sf_import_customer_digs_error' => [
            'username' => '【WOA】掘起しSF連携エラー',
            'channel' => '#jinzai-notify-woa',
        ],
        'sf_import_matching_error' => [
            'username' => '【WOA】マッチングSF連携エラー',
            'channel' => '#jinzai-notify-woa',
        ],
        'sf_import_customer_update_error' => [
            'username' => '【WOA】求職者更新SF連携エラー',
            'channel' => '#jinzai-notify-woa',
        ],
        'sf_import_account_mail_error' => [
            'username' => '【WOA】メール引合SF連携エラー',
            'channel' => '#jinzai-notify-woa',
        ],
        'sf_flag_monitoring' => [
            'username' => '【WOA】SF連携フラグレポート',
            'channel' => '#jinzai-notify-woa',
        ],
        'sf_flag_alert_notifier' => [
            'username' => '【WOA】SF連携フラグAlert',
            'channel' => '#jinzai-failed-web',
        ],
        'entry_controller_error' => [
            'username' => '【WOA】新規登録エラー',
            'channel'  => '#jinzai-notify-woa',
        ],
        'reentry_fin_controller_error' => [
            'username' => '【WOA】 求職者掘起し登録エラー',
            'channel'  => '#jinzai-notify-woa',
        ],
        'reentry_regist_contact_time_api_error' => [
            'username' => '【WOA】 掘起しの連絡希望時間帯の登録',
            'channel'  => '#jinzai-notify-woa',
        ],
        'job_detail_digs_api_error' => [
            'username' => '【WOA】 モーダル掘起し登録API',
            'channel'  => '#jinzai-notify-woa',
        ],
        'generate_report' => [
            'username' => '【WOA】登録数',
            'channel'  => '#jinzai-notify-woa',
        ],
        'web_customer_tracking_mapping_error' => [
            'username' => '【WOA】 woa_customer_tracking_mappingsレコード作成失敗',
            'channel'  => '#jinzai-notify-woa',
        ],
    ],
];
