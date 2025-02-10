<?php

return [
    // SF連携項目
    'link_columns'    => [
        'web_customer_id__c'           => 'id', // Webサイト求職者ID
        'Name'                         => 'name_kan', // 氏名
        'name_cana__c'                 => 'name_cana', // フリガナ
        'addr3__c'                     => 'addr3', // 市区町村以下
        'tel__c'                       => 'tel', // 電話番号①
        'mail__c'                      => 'mail', // メールアドレス①
        'license_web__c'               => 'license', // 保有資格
        'req_emp_type__c'              => 'req_emp_type', // ★希望就業形態
        'req_date__c'                  => 'req_date', // 入職希望時期（WEB）
        'KC_retirement_intention__c'   => 'retirement_intention', // 退職意向
        'KC_graduation_year__c'        => 'graduation_year', // 卒業予定年
        'ejbtourokuosusumekyuujinn__c' => 'entry_order',
        'action__c'                    => 'action', // アクションパラメータ
        'action_first__c'              => 'action_first', // アクションパラメータ（最初）
        'template_id__c'               => 'template_id', // テンプレート番号
        'ip__c'                        => 'ip', // IP帯域
        'ua__c'                        => 'ua', // ユーザエージェント
        'ejbtourokujijouhou__c'        => 'entry_memo',
        'AgreementTermsOfService__c'   => 'agreement_flag',
        'KC_CooperationSource__c'      => 'src_site_name',
        'KC_referredUserName__c'       => 'referral_name', // friend_referral.referral_name 求職者ID紹介者氏名
        'tenkyokahi__c'                => 'moving_flg', // 転居可否
    ],

    // SF連携必須項目
    'require_columns' => [
        'Name',
        'name_cana__c',
        'birth__c',
        'addr1__c',
        'addr2__c',
        'tel__c',
        'license_web__c',
    ],
];
