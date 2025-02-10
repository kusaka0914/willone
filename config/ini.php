<?php

return [

    // 'SITE_ID' => '',
    'SITE_NAME'                      => 'woa',
    'SITE_MEISYOU'                   => 'ウィルワンエージェント',

    // Return-Path
    //    'ENVELOPE_FROM_MAIL' => 'mail_error+ejb_regist_mail@jinzaibank.mobi',

    // 検索エンジンURLパターン設定(正規表現)
    'URL_PATTERN_ARRAY'              => [
        // Google
        '/(^http:\/\/www\.google\.co\.jp\/search).*[^a-z]q=([^&]+)/',
        // Yahoo! JAPAN
        '/(^http:\/\/search\.yahoo\.co\.jp\/search).*[^a-z]p=([^&]+)/',
        // MSN Japan
        '/(^http:\/\/search\.msn\.co\.jp\/results\.aspx).*[^a-z]q=([^&]+)/',
        // インフォシーク Infoseek
        '/(^http:\/\/search\.www\.infoseek\.co\.jp\/Web).*[^a-z]qt=([^&]+)/',
        // Excite エキサイト
        '/(^http:\/\/www\.excite\.co\.jp\/search\.gw).*[^a-z]search=([^&]+)/',
        // goo
        '/(^http:\/\/green\.search\.goo\.ne\.jp\/search).*[^a-z]MT=([^&]+)/',
        '/(^http:\/\/search\.goo\.ne\.jp\/web\.jsp).*[^a-z]MT=([^&]+)/',
        // livedoor
        '/(^http:\/\/search\.livedoor\.com\/search).*[^a-z]q=([^&]+)/',
    ],

    // 学生
    'studentList'                    => ['44', '45', '46'],

    // 退職意向確認
    // 全部
    // ※「現在働いていない／ブランク期間中」は「離職中／退職確定済」に寄せる
    'RETIREMENT_INTENTIONS'          => [
        '離職中／退職確定済'   => '離職中／退職確定済',
        'できるだけ早く辞めたい' => 'できるだけ早く辞めたい',
        '良い転職先なら辞めたい' => '良い転職先なら辞めたい',
        '良い転職先なら検討する' => '良い転職先なら検討する',
        '半年以上は辞められない' => '半年以上は辞められない',
        'あまり辞める気は無い'  => 'あまり辞める気は無い',
        'その他'         => 'その他',
    ],

    // エージェント　登録カテゴリ
    // 3.bladeでURLにcategory=7の様に指定した時に対象のデータをselectでdefaultになるようにしているのでこれを修正する時は注意
    'ENTRY_CATEGORY_MANUAL'          => [
        1 => '専門学校',
        2 => 'アカデミー生',
        3 => '友人知人紹介',
        4 => 'ジョブノート（広告）',
        5 => 'ジョブノート（SEO）',
        6 => 'SEO',
        7 => '国試黒本（新卒）',
        8 => '国試黒本（掘起し）',
        9 => '就職フェア',
        10 => 'KJA送客',
        11 => 'アフィリエイト広告',
        12 => 'JN（アフィリエイト）',
        13 => 'その他',
    ],

    'FROM_MAIL'                      => 'info@willone.jp',
    'WOA_TELEMA'                     => 'woa_telema@bm-sms.co.jp',
    'KISOTSU_TO_MAIL'                => 'woa_kisotsu@bm-sms.co.jp',
    'SHINSOTU_TO_MAIL'               => 'woa_shinsotu@bm-sms.co.jp',

    // 連携元サイト名 ※API経由で求職者登録される連携元が増える時はここに追加
    'SRC_SITE_NAMES'                 => [
        'ジョブノート',
    ],

    // 登録フォームGLP LPID
    'ENTRY_TEMPLATE_ID' => [
        'pc' => [
            'top'    => 'PC_org_001', // TOPページ（エリアから探す、職種から探す、職種TOP含む）
            'list'   => 'PC_org_002', // 一覧ページ
            'detail' => 'PC_org_003', // 詳細ページ
            'other'  => 'PC_org_004', // その他
            'common' => 'PC_org_005', // ヘッダー・フッター
        ],
        'sp' => [
            'top'    => 'SP_org_001', // TOPページ（エリアから探す、職種から探す、職種TOP含む）
            'list'   => 'SP_org_002', // 一覧ページ
            'detail' => 'SP_org_003', // 詳細ページ
            'other'  => 'SP_org_004', // その他
            'common' => 'SP_org_005', // ヘッダー・フッター
        ],
    ],

    // 電話希望時間帯
    'REQ_CALL_TIME'                  => [
        1 => "10:00～12:00",
        2 => "12:00～13:00",
        3 => "13:00～17:00",
        4 => "17:00～19:00",
        5 => "19:00～20:00",
        9 => "その他",
    ],
    // 問合せ内容
    'INQUIRY'                        => [
        1 => "サービス・料金の詳細について",
        2 => "近隣求職者の登録状況について",
        3 => "その他(フリーコメント記入欄)",
    ],

    // 登録経路
    'ETNRY_ROUTE_ENTRY'              => 0, // 新規登録
    'ETNRY_ROUTE_REENTRY'            => 2, // 掘起しアンケート

    // 掘起しテンプレート番号
    'MODAL_DIGS_TEMPLATE_NO'         => 8, // モーダル表示の掘起しアンケート

    'listRoute'                      => [
        '0' => '新規登録',
        '2' => '掘起しアンケート',
    ],

    // 人材紹介申込用希望職種
    'RECRUIT_REQ_WORK_TYPE'          => [
        1 => '柔道整復師',
        2 => 'あん摩マッサージ指圧師',
        3 => '鍼灸師',
        4 => '整体',
        6 => 'カイロプラクティック',
        7 => 'アロマ',
        9 => 'その他',
    ],

    // ご用件
    'CONTACT_INQUIRY'                => [
        1 => '就職転職の相談',
        2 => 'ご登録について',
        3 => 'システムについて',
        4 => 'その他のお問い合わせ',
    ],

    // ご用件
    'REENTRY_CONTACT_INQUIRY'        => [
        1 => '求人について',
        2 => '就職転職の相談',
        3 => 'ご登録について',
        4 => 'システムについて',
        5 => 'その他のお問い合わせ',
    ],

    // 就職活動状況について
    'REENTRY_JOB_HUNTING_STATUS_LABEL'        => [
        1 => '就職活動中',
        2 => 'これから就職活動を行う予定',
        3 => '現在働いている職場でそのまま働く予定',
        4 => '開業予定',
        5 => 'その他',
    ],

    // 問い合わせ時間
    'REENTRY_CONTACT_TIME'        => [
        1 => '午前中',
        2 => '12時〜15時',
        3 => '15時〜18時',
        4 => '18時〜20時',
    ],

    // 性別
    'GENDER'                         => [
        1 => '男性',
        2 => '女性',
    ],

    // 事例の既卒 or 新卒
    'EXAMPLE_TYPE'                         => [
        1 => '既卒',
        2 => '新卒',
    ],

    // 空白を削除
    'REMOVE_BLANK_LIST'              => [
        'name',
        'kana_name',
        'inc_name',
        'staff_name',
        'staff_name_kana',
    ],

    // 半角数字のみに
    'HALF_SIZE_NUMBER_LIST'          => [
        'postcode',
        'tel',
    ],

    // メールの形式に
    'MAIL_FORMAT_LIST'               => [
        'mail',
        'email',
    ],

    // 全角→半角
    'REPLACE_HALF_SIZE_LIST'         => [
        'age',
    ],

    // 働き方
    'EMPLOY_TYPE' => [
        'fulltime' =>
        [
            'value' => '常勤',
            'search_key' => 'fulltime',
            'query_key' => 1,
        ],
        'parttime' =>
        [
            'value' => '非常勤',
            'search_key' => 'parttime',
            'query_key' => 2,
        ],
    ],


    'BUSINESS_TYPE' => [
        'grouphome' => [
            'value' => 'グループホーム',
            'search_key' => 'grouphome',
            'query_key' => 'グループホーム',
        ],
        'clinic' => [
            'value' => 'クリニック',
            'search_key' => 'clinic',
            'query_key' => '診療所',

        ],
        'company' => [
            'value' => '企業',
            'search_key' => 'company',
            'query_key' => '企業',
        ],
        'daycare' => [
            'value' => 'デイケア事業所',
            'search_key' => 'daycare',
            'query_key' => 'デイケア事業所',
        ],
        'dayservice' => [
            'value' => 'デイサービス事業所',
            'search_key' => 'dayservice',
            'query_key' => 'デイサービス事業所',
        ],
        'homesinkyu' => [
            'value' => '訪問鍼灸事業所',
            'search_key' => 'homesinkyu',
            'query_key' => '訪問鍼灸事業所',
        ],
        'hospital' => [
            'value' => '病院',
            'search_key' => 'hospital',
            'query_key' => '病院',
        ],
        'medicaltreat' => [
            'value' => '施術所',
            'search_key' => 'medicaltreat',
            'query_key' => '施術所',
        ],
        'others' => [
            'value' => 'その他',
            'search_key' => 'others',
            'query_key' => '共同生活援助,小規模多機能,居宅介護支援事業所,往診・訪問診療,放課後等デイサービス,施設,本部（施設）,生活介護,看護小規模多機能居宅介護,複合型サービス,訪問介護事業所,訪問入浴事業所,訪問看護ステーション,軽費老人ホーム,障害者支援,障害者施設,その他',
        ],
        'roujinhome' => [
            'value' => '有料老人ホーム',
            'search_key' => 'roujinhome',
            'query_key' => '有料老人ホーム',
        ],
        'rouken' => [
            'value' => '介護老人保健施設',
            'search_key' => 'rouken',
            'query_key' => '介護老人保健施設',
        ],
        'school' => [
            'value' => '教育・学校',
            'search_key' => 'school',
            'query_key' => '教育・学校',
        ],
        'servicetuki' => [
            'value' => 'サービス付き高齢者専用住宅',
            'search_key' => 'servicetuki',
            'query_key' => 'サービス付き高齢者専用住宅',
        ],
        'shortstay' => [
            'value' => 'ショートステイ事業所',
            'search_key' => 'shortstay',
            'query_key' => 'ショートステイ事業所',
        ],
        'tokuyou' => [
            'value' => '特別養護老人ホーム',
            'search_key' => 'tokuyou',
            'query_key' => '特別養護老人ホーム',
        ],
        'zaitaku' => [
            'value' => '訪問マッサージ事業所',
            'search_key' => 'zaitaku',
            'query_key' => '訪問マッサージ事業所',
        ],
    ],

    'LICENSE_MAPPING'                => [
        '柔道整復師'           => '柔道整復師',
        '鍼灸師'             => '鍼灸師',
        'あん摩マッサージ指圧師'     => 'あん摩ﾏｯｻｰｼﾞ指圧師',
        '柔道整復師（学生）'       => '国資学生（柔道整復）',
        '鍼灸師（学生）'         => '国資学生（鍼灸）',
        'あん摩マッサージ指圧師（学生）' => '国資学生（あん摩ﾏｯｻｰｼﾞ指圧）',
        '整体師'             => '整体',
        'ﾘﾌﾚｸｿﾛｼﾞｽﾄ'      => 'ﾘﾌﾚｸｿﾛｼﾞｽﾄ',
        'ｶｲﾛﾌﾟﾗｸﾀｰ'       => 'ｶｲﾛﾌﾟﾗｸﾀｰ',
        'ﾛﾐﾛﾐｾﾗﾋﾟｽﾄ'      => 'ﾛﾐﾛﾐｾﾗﾋﾟｽﾄ',
        'ﾀｲ古式ｾﾗﾋﾟｽﾄ'      => 'ﾀｲ古式ｾﾗﾋﾟｽﾄ',
        'ｱﾛﾏｾﾗﾋﾟｽﾄ'       => 'ｱﾛﾏｾﾗﾋﾟｽﾄ',
    ],

    //willmail設定情報
    'WILLMAIL_URL'                   => 'https://willap.jp/api/rest/1.0.0/customers/',
    'WILLMAIL_ACCOUNT_KEY'           => env('WILLMAIL_ACCOUNT_KEY'),
    'WILLMAIL_API_KEY'               => env('WILLMAIL_API_KEY'),
    'WILLMAIL_TARGETDB_ID_WOA'       => env('WILLMAIL_TARGETDB_ID_WOA'),

    // BigQuery 設定情報
    'GOOGLE_CLOUD_APPLICATION_CREDENTIALS' => env('GOOGLE_CLOUD_APPLICATION_CREDENTIALS'),
    'GOOGLE_CLOUD_PROJECT_ID'       => env('GOOGLE_CLOUD_PROJECT_ID'),

    // 関東エリア
    'AREA_KANTO'                     => 2,
    // その他のエリア
    'AREA_OTHER'                     => [43, 53, 81],
    // 3STEP プルダウン用
    'AREA_PULLDOWN'                  => [11, 14, 21, 22, 23, 24, 25, 26, 27, 31, 36, 42, 43, 52, 53, 54, 64, 81, 88],

    // 人気エリア
    'AREA_POP'                       => [
        'tokyo'    => [
            'prefName' => '東京都',
            'cities'   => [
                ['name' => '中央区', 'roma' => 'chuoku'],
                ['name' => '港区', 'roma' => 'minatoku'],
            ],
        ],
        'kanagawa' => [
            'prefName' => '神奈川県',
            'cities'   => [
                ['name' => '横浜市鶴見区', 'roma' => 'yokohamashitsurumiku'],
                ['name' => '横浜市神奈川区', 'roma' => 'yokohamashikanagawaku'],
            ],
        ],
        'saitama'  => [
            'prefName' => '埼玉県',
            'cities'   => [
                ['name' => 'さいたま市西区', 'roma' => 'saitamashinishiku'],
                ['name' => 'さいたま市北区', 'roma' => 'saitamashikitaku'],
            ],
        ],
        'chiba'    => [
            'prefName' => '千葉県',
            'cities'   => [
                ['name' => '千葉市中央区', 'roma' => 'chibashichuoku'],
                ['name' => '千葉市花見川区', 'roma' => 'chibashihanamigawaku'],
            ],
        ],
    ],

    // 求人一覧で非公開部分の表示に市区町村を表示する都道府県
    'STATE_DISPLAY_OF_PRIVATE_JOBS'  => [
        26 => '東京都',
        24 => '埼玉県',
        25 => '千葉県',
        27 => '神奈川県',
        53 => '大阪府',
        43 => '愛知県',
        54 => '兵庫県',
    ],

    // 登録フォームで「県外への転居を検討」を表示させない都道府県
    'STATE_NOT_MOVE'                 => [
        11 => '北海道',
        14 => '宮城県',
        21 => '茨城県',
        22 => '栃木県',
        23 => '群馬県',
        24 => '埼玉県',
        25 => '千葉県',
        26 => '東京都',
        27 => '神奈川県',
        36 => '長野県',
        42 => '静岡県',
        43 => '愛知県',
        52 => '京都府',
        53 => '大阪府',
        54 => '兵庫県',
        81 => '福岡県',
    ],

    'AREA_LIST'                      => [
        1 => '北海道・東北',
        2 => '関東',
        3 => '北信越',
        4 => '東海',
        5 => '近畿',
        6 => '中国',
        7 => '四国',
        8 => '九州',
    ],

    // パンくず
    'BASE_BREAD_CRUMB'               => [
        ['label' => '就職支援のウィルワン', 'url' => '/woa'],
    ],

    // 検索一覧offset
    'DEFAULT_OFFSET'                 => 30,

    // jobImage
    'JOB_IMAGE_LIST'                 => [
        '0' => '1.jpg',
        '1' => '1.jpg',
        '2' => '2.jpg',
        '3' => '2.jpg',
        '4' => '3.jpg',
        '5' => '3.jpg',
        '6' => '4.jpg',
        '7' => '4.jpg',
        '8' => '5.jpg',
        '9' => '5.jpg',
    ],

    // 注力エリア
    'FOCUS_AREA' => [
        // 埼玉、千葉、東京、神奈川、愛知県、大阪府、兵庫県、福岡県
        'judoseifukushi'=> [24,25,26,27,43,53,54,81],
        // 埼玉、東京、神奈川、愛知県、大阪府
        'ammamassajishiatsushi' => [24,26,27,43,53],
        // 埼玉、千葉、東京、神奈川、愛知県、大阪府、兵庫県、福岡県
        'harikyushi' => [24,25,26,27,43,53,54,81],
    ],

    // 求人一覧で indexにする職種
    'INDEX_JOB_TYPE'                 => [
        'judoseifukushi',
        'ammamassajishiatsushi',
        'harikyushi',
        'seitaishi_therapist',
    ],

    // 求人一覧で indexにする職種
    'INDEX_JOB_TYPE_AGGREGATE'                 => [
        'judoseifukushi',
        'ammamassajishiatsushi',
        'harikyushi',
        'seitaishi_therapist',
        'other',
    ],

    // 職種グループ
    'JOB_TYPE_GROUP'                 => [
        'judoseifukushi'        => [
            'name'          => '柔道整復師',
            'ids'           => [1, 2, 5],
            'syokusyu_text' => 1,
            'image'         => '/woa/images/search_type_icon1.png',
            'master_id'     => 40, // master_license_mst.id
        ],
        'ammamassajishiatsushi' => [
            'name'          => 'あん摩マッサージ指圧師',
            'ids'           => [4, 7],
            'syokusyu_text' => 2,
            'image'         => '/woa/images/search_type_icon2.png',
            'master_id'     => 41,
        ],
        'harikyushi'            => [
            'name'          => '鍼灸師',
            'ids'           => [3, 6],
            'syokusyu_text' => 3,
            'image'         => '/woa/images/search_type_icon3.png',
            'master_id'     => 42,
        ],
        'seitaishi_therapist'   => [
            'name'          => '整体師・セラピスト',
            'ids'           => [8, 9, 10, 11, 12, 13],
            'syokusyu_text' => 4,
            'image'         => '/woa/images/search_type_icon4.png',
            'master_id'     => 43,
        ],
        'other'                 => [
            'name'          => 'その他',
            'ids'           => [99],
            'syokusyu_text' => 9,
            'image'         => '',
            'master_id'     => 0, // master_license_mstになし
        ],
    ],

    // フッター SEOテキスト
    'DEFAULT_DISPLAY_SYOKUSYU_TEXT_LIST' => [1, 2, 3, 4],

    // S3のイメージURL
    'S3_CO_IMAGE_URL' => env('S3_CO_IMAGE_URL'),

    // 黒本URL
    'KUROHON_MYPAGE' => env('KUROHON_MYPAGE'),

    // 私たちのサービスについてはこちら↓
    'OUR_SERVICE_DESCRIPTION_URL' => 'https://tinyurl.com/2aj42g9s',

    // 注目枠の表示位置
    'PR_DISPLAY_POSITION' => [
        'first'    => 1,
        'second' => 2,
    ],

    // 会社IDと画像の紐付け
    'OPPORTUNITY_IMAGES' => [
        '5827' => '5827.jpeg',
    ],

    // アップロードステータス
    'UPLOAD_STATUS' => [
        'pending' => 'pending',
        'success' => 'success',
        'failed' => 'failed',
    ],
];
