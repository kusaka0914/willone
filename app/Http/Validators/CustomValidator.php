<?php

namespace App\Http\Validators;

use \Illuminate\Validation\Validator;

class CustomValidator extends Validator
{
    /**
     * 全角かな、全角カナのみかどうか？
     */
    public function validateZenHiraKata($attribute, $value, $parameters)
    {
        // 空なら評価しない（妥当である）
        if (empty($value)) {return true;}

        $value = preg_replace("/[ぁ-んー 　ァ-ヴ]/u", "", $value);

        return empty($value);
    }

    /**
     * 妥当なメールアドレスかどうか？
     */
    public function validateEmailFormat($attribute, $value, $parameters)
    {
        // 空なら評価しない（妥当である）
        if (empty($value)) {return true;}

        if (!preg_match('/^([\w])+([\w\._\-])*\@([\w])+([\w\._\-])*\.([a-zA-Z])+$/', $value)) {
            $result = false;
        } else {
            $host = explode("@", $value);
            // domain確認
            $result = checkdnsrr($host[1], "A") || checkdnsrr($host[1], "MX");
        }

        return $result;
    }
}
