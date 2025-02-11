<?php

return [
    // SF連携項目
    'link_columns' => [
        'kjb_customer__c' => 'salesforce_id', // 求職者
        'kjb_opportunity__c' => 'order_salesforce_id', // コメディカルオーダー
        'action__c' => 'action', // アクションパラメータ
        'action_first__c' => 'action_first', // アクションパラメータ（最初）
        'kakutokukeiro__c' => 'via_mailmaga_flag', // 獲得経路
    ],
];
