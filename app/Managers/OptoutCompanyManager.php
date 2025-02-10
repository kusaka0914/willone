<?php

namespace App\Managers;

// use App\Managers\UtilManager;

class OptoutCompanyManager
{
    const STOP_REASON_OPTIONS = [
        '充足している'    => '充足している',
        '該当職種の配置なし' => '該当職種の配置なし',
        '採用担当ではない'  => '採用担当ではない',
        'メール不要'     => 'メール不要',
        'その他'       => 'その他',
    ];

    private $view_data = [];

    // $inputed_mail string 前回フォームで入力したアドレス
    public function __construct($params, $inputed_mail)
    {

        // リクエストパラメータを設定する
        $this->view_data['action'] = $this->fetch($params, 'action');
        $this->view_data['order_tel_contact'] = $this->fetch($params, 'order_tel_contact');
        $this->view_data['account'] = $this->fetch($params, 'account');
        $this->view_data['shubetu'] = $this->fetch($params, 'shubetu');
        // メールアドレスの復元
        $p1 = $this->fetch($params, 'p1');
        $mail = $this->decryptMail($p1);
        $this->view_data['sent_address'] = $mail;

        // 初期値：初回はパラメータから、以降は入力値
        if (empty($inputed_mail)) {
            $this->view_data['mail'] = $mail;
        } else {
            $this->view_data['mail'] = $inputed_mail ? $inputed_mail : $mail;
        }

        // 配信停止理由リストを設定する
        $this->view_data['stop_reason_options'] = self::STOP_REASON_OPTIONS;
    }

    public function getViewData()
    {
        return $this->view_data;
    }

    private function fetch($params, $key)
    {
        // params配列にkeyが存在すればその値を返して、なければnullを返す
        return array_key_exists($key, $params) ? $params[$key] : null;
    }

    private function decryptMail($mail_param)
    {
        $encrypted_mail = base64_decode($mail_param);

        $key = base64_decode(env('ACC_MAILMGS_ENCRYPT_KEY64'));
        // 暗号化と復号化の形式が整合性とれていないためこの処理で正常に復号化できていない
        // 正常に復号化させるためには暗号化しているPentahoの処理を修正する必要あり
        $decrypted_mail = openssl_decrypt($encrypted_mail, 'aes-128-ecb', $key);
        $mail = trim($decrypted_mail); //暗号化時にブロック長を満たすため付加されてる0x00コードをtrim

        return $mail;
    }
}
