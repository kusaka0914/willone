<?php

namespace App\Managers;

use App\Managers\SelectBoxManager;

class EntryCompanyManager
{
    private $view_data = [];

    public function __construct($params, $old_addr1, $old_addr2, $old_mail)
    {
        // リクエストパラメータを設定する
        $this->view_data['action'] = $this->fetch($params, 'action');
        $this->view_data['order_tel_contact'] = $this->fetch($params, 'order_tel_contact');
        $this->view_data['account'] = $this->fetch($params, 'account');
        $this->view_data['shubetu'] = $this->fetch($params, 'shubetu');

        // メールアドレスの復元
        $p1 = $this->fetch($params, 'p1');
        $this->view_data['sent_address'] = $this->decryptMail($p1);

        if (isset($old_mail)) {
            //入力値復元
            $this->view_data['mail'] = $old_mail;
        } else {
            //初回値
            $this->view_data['mail'] = $this->view_data['sent_address'];
        }
        // 都道府県・市区町村の復元
        $this->view_data['addr1'] = $old_addr1 ? $old_addr1 : $this->fetch($params, 'pref');
        $this->view_data['addr2'] = $old_addr1 ? $old_addr2 : null;
        // 都道府県・市区町村の設定
        $selectBoxMgr = new SelectBoxManager;
        $this->view_data['prefectureList'] = $selectBoxMgr->sysPrefectureSb();
        $this->view_data['cityList'] = $selectBoxMgr->sysCitySb($this->view_data['addr1']);

        $this->view_data['tel_time_idList'] = config('ini.REQ_CALL_TIME');
        $this->view_data['inquiryList'] = config('ini.INQUIRY');
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
