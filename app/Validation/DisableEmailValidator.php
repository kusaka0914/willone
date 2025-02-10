<?php

namespace App\Validation;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\EmailValidation;

class DisableEmailValidator extends EmailValidator
{
    /**
     * 人材紹介サイトはRFCに違反しているキャリアメールからの登録がそこそこある為、
     * Laravel標準のメールアドレスRFC準拠チェックを、何もチェックしない処理へ差し替え（無効化）
     *
     * @param string $email
     * @param EmailValidation $emailValidation
     * @return bool true
     */
    public function isValid($email, EmailValidation $emailValidation)
    {
        return true;
    }
}
