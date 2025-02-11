<?php

return [
    'memory_limit'     => '512M',
    'job_type_mapping' => [
        "1"  => "柔道整復師（管理）",
        "2"  => "柔道整復師",
        "3"  => "鍼灸師",
        "4"  => "あん摩ﾏｯｻｰｼﾞ指圧師",
        "5"  => "国資学生（柔道整復）",
        "6"  => "国資学生（鍼灸）",
        "7"  => "国資学生（あん摩ﾏｯｻｰｼﾞ指圧）",
        "8"  => "整体",
        "9"  => "ｶｲﾛﾌﾟﾗｸﾀｰ",
        "10" => "ｱﾛﾏｾﾗﾋﾟｽﾄ",
        "11" => "ﾘﾌﾚｸｿﾛｼﾞｽﾄ",
        "12" => "ﾀｲ古式ｾﾗﾋﾟｽﾄ",
        "13" => "ﾛﾐﾛﾐｾﾗﾋﾟｽﾄ",
        "99" => "その他",
    ],
    'employ_mapping'   => [
        "1" => "正社員",
        "2" => "アルバイト･パート",
    ],
    // FTPアップロード関連の設定を追加
    'ftp_upload' => [
        'indeed' => [
            'file_path' => 'feed/WOA_indeed.xml',
            'ftp_type'  => 'indeed_woa',
        ],
        'stanby' => [
            'file_path' => 'feed/WOA_stanby_org.xml',
            'ftp_type'  => 'stanby_woa',
        ],
        'stanby_ad' => [
            'file_path' => 'feed/WOA_stanby_ad.xml',
            'ftp_type'  => 'stanby_ad_woa',
        ],
        'kyujinbox' => [
            'file_path' => 'feed/WOA_Kyujinbox.xml',
            'ftp_type'  => 'kyujinbox_woa',
        ],
    ],
];
