<?php

return [
    // SF連携項目
    'link_columns' => [
        'last_name__c' => 'name_kan', // 担当者名
        'company__c' => 'company_name', // 会社名
        'phone__c' => 'tel', // 電話番号
        'Email__c' => 'mail', // Email
        'Description__c' => 'inquiry_detail', // フリーコメント(お問い合わせ内容詳細)
        'odw__c' => 'order_tel_contact', // オーダー架電窓口
        'shubetu__c' => 'shubetu', // 原稿種別
        'send_address__c' => 'sent_address', // 送信先メールアドレス
        'tel_time_note__c' => 'tel_time_note', // 電話希望時間その他
        'action__c' => 'action1', // アクション
        'boshu_shikaku__c' => 'boshu_shikaku', // 募集資格
    ],
    'stop_link_columns' => [
        'Email__c' => 'mail', // Email
        'odw__c' => 'order_tel_contact', // オーダー架電窓口
        'shubetu__c' => 'shubetu', // 原稿種別
        'teishi_riyuu_sl__c' => 'stop_reason', // 配信停止理由
        'send_address__c' => 'sent_address', // 送信先メールアドレス
        'action__c' => 'action1', // アクション
    ],
];
