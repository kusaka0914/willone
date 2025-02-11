<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
     */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => ':attributeは配列にしてください。',
    'before'               => 'The :attribute must be a date before :date.',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => ':attributeと:attribute(確認)が一致しません',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => ':attributeは正しい年月日フォーマットで指定してください',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => ':attributeは:digits文字の数値を入力してください',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => ':attributeには正しいメールアドレスを入力してください',
    'exists'               => ':attributeには正しい値を入力してください',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'The :attribute field must have a value.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => ':attributeには正しい値を入力してください',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => ':attributeには数字(整数)を入力して下さい',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => ':attributeは:max字以下の数字を入力してください',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => ':attributeは:max字以下の文字を入力してください',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => ':attributeは:min字以上の数字を入力してください',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => ':attributeは:min字以上の文字を入力してください',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => ':attributeは半角数字を入力してください',
    'present'              => 'The :attribute field must be present.',
    'regex'                => ':attributeを正しく入力してください',
    'required'             => ':attributeを入力してください',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => ':attribute 文字列を入力してください',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.',

    // カスタムバリデーション
    'hiragana'             => ':attributeは全角ひらがなで入力してください',
    'hiraganakatakana'     => ':attributeは全角ひらがな、全角カタカナで入力してください',
    'custom_email'         => ':attributeには正しいメールアドレスを入力してください',
    'custom_tel'           => ':attributeに使用できるのは数字と-（ハイフン）、()（カッコ）だけです',
    'custom_tel_format'    => ':attributeの-（ハイフン）、()（カッコ）の入力が正しくありません',
    'custom_tel_length'    => ':attributeには正しい桁数で入力してください',
    'custom_tel_exist'     => ':attributeには正しい電話番号を入力してください',
    'not_blank_only'       => ':attributeに空白だけの入力はできません',
    'age_area'             => ':attributeは18歳から80歳の間で入力してください',
    'ctrl_emoji_char'      => ':attributeにご使用できない文字が含まれています',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
     */

    'custom'               => [
        'attribute-name'        => [
            'rule-name' => 'custom-message',
        ],
        'license'               => [
            'required' => ':attributeをひとつ以上選択してください',
        ],
        'req_emp_type'          => [
            'required' => ':attributeを選択してください',
        ],
        'req_date'              => [
            'required' => ':attributeを選択してください',
        ],
        'cm_practice'           => [
            'required' => ':attributeを選択してください',
        ],
        'operation'             => [
            'required' => ':attributeを選択してください',
        ],
        'req_work_type'         => [
            'required' => ':attributeをひとつ以上選択してください',
        ],
        'sex'                   => [
            'required' => ':attributeを選択してください',
        ],
        'addr2'                 => [
            'required' => ':attributeを選択してください',
        ],
        'birth_year'            => [
            'required' => ':attributeを選択してください',
        ],
        'graduation_year'       => [
            'required' => ':attributeを選択してください',
        ],
        'retirement_intention'  => [
            'required' => ':attributeを選択してください',
        ],
        'entry_category_manual' => [
            'required' => ':attributeを選択してください',
        ],
        'type'                  => [
            'required' => ':attributeを選択してください',
        ],
        'seibetsu'              => [
            'required' => ':attributeを選択してください',
        ],
        'inquiry'               => [
            'required' => ':attributeを正しく入力してください',
        ],
        'toiawase'              => [
            'required' => ':attributeを正しく入力してください',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
     */

    'attributes'           => [
        'license'                => '保有資格',
        'graduation_year'        => '卒業年度',
        'introduce_name'         => '紹介者氏名',
        'req_date'               => '入職希望時期',
        'req_emp_type'           => '希望雇用形態',
        'req_work_type'          => '希望職種',
        'retirement_intention'   => '退職意向',
        'entry_category_manual'  => '登録カテゴリ',
        'sex'                    => '性別',
        'seibetsu'               => '性別',
        'birth'                  => '生まれた年',
        'birth_year'             => '生まれた年',
        'input_birth_year'       => '生まれた年',
        'name_kan'               => '氏名',
        'name'                   => '氏名',
        'name_cana'              => 'ふりがな',
        'kana_name'              => 'フリガナ',
        'zip'                    => '郵便番号',
        'postcode'               => '郵便番号',
        'addr'                   => '住所',
        'address'                => '住所',
        'addr1'                  => '都道府県',
        'addr2'                  => '市区町村',
        'addr3'                  => '番地・建物名',
        'pref'                   => '都道府県',
        'state'                  => '市区町村',
        'tel'                    => '電話番号',
        'mob_phone'              => '携帯電話番号',
        'mail'                   => 'メールアドレス',
        'mob_mail'               => 'メールアドレス',
        'email'                  => 'メールアドレス',
        'company_name'           => '貴社名',
        'division_name'          => '部署名',
        'inquiry'                => 'お問合せ内容',
        'toiawase'               => 'お問合せ内容',
        'title'                  => 'タイトル',
        'shiken_date'            => '試験日',
        'kaitou_image1'          => '解答イメージ1',
        'kaitouurl'              => '解答URL',
        'src_site_name'          => '連携元サイト名',
        'src_customer_id'        => '連携元顧客ID',
        'action'                 => 'アクションパラメータ',
        'action_first'           => 'アクションパラメータ（最初）',
        'ip'                     => 'IPアドレス',
        'ua'                     => 'User agent',
        'inc_name'               => '会社名',
        'staff_name'             => '担当者',
        'staff_name_kana'        => 'フリガナ',
        'age'                    => '年齢',
        'type'                   => 'ご用件',
        'contact_inquiry'        => 'ご用件',
        'reentry_contact_time'   => '連絡希望時間帯',
        'reentry_contact_time_1' => '第1希望',
        'reentry_contact_time_2' => '第2希望',
        'reentry_contact_time_3' => '第3希望',
    ],

];
