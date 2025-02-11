<?php
/**
 *  ApiManager
 */

namespace App\Managers;

class ApiManager
{

    /**
     * メールアドレスのドメイン存在チェック
     * @access public
     * @param string $mail
     * @return boolean
     */
    public function isDnsByMail($mail)
    {
        $result = false;
        if (preg_match('/^[\w\-]+([\.\w\-\+\?\/]+)*@[a-z0-9]+([\.a-z0-9\-])+([\.][a-z0-9\-]{2,4})$/i', $mail)) {
            $host = explode("@", $mail);
            if (checkdnsrr($host[1], "A") || checkdnsrr($host[1], "MX")) {
                $result = true;
            }
        }

        return $result;
    }
}
