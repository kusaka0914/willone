<?php
/**
 * UtilManager
 */
namespace App\Managers;

use App\Managers\TerminalManager;

class UtilManager
{
    /**
     *
     * Enter description here ...
     * @param $form
     */
    public function determineMobInfo(&$form)
    {
        if (!$this->isMobEmail($form['mob_mail'])) {
            if ($this->isMobEmail($form['pc_mail'])) {
                $this->change($form['mob_mail'], $form['pc_mail']);
            }
        }
        if (!$this->isPcEmail($form['pc_mail'])) {
            if ($this->isPcEmail($form['mob_mail'])) {
                $this->change($form['mob_mail'], $form['pc_mail']);
            }
        }

        if (!$this->isMobPhone($form['mob_phone'])) {
            if ($this->isMobPhone($form['tel'])) {
                $this->change($form['mob_phone'], $form['tel']);
            }
        }
        if (!$this->isPcPhone($form['tel'])) {
            if ($this->isPcPhone($form['mob_phone'])) {
                $this->change($form['mob_phone'], $form['tel']);
            }
        }
    }

    /**
     *
     * Detemine email is pc email or not
     * @param unknown_type $email
     */
    public function isPcEmail($email)
    {
        if (empty($email)) {
            return false;
        }

        return !$this->isMobEmail($email);
    }

    /**
     *
     * Detemine email is mobile email or not
     * @param $email
     */
    public function isMobEmail($email)
    {
        $mobDomainEmails = [
            "docomo.ne.jp",
            "mopera.net",
            "dwmail.jp",
            "softbank.ne.jp",
            "i.softbank.jp",
            "disney.ne.jp",
            "d.vodafone.ne.jp",
            "h.vodafone.ne.jp",
            "t.vodafone.ne.jp",
            "c.vodafone.ne.jp",
            "r.vodafone.ne.jp",
            "k.vodafone.ne.jp",
            "n.vodafone.ne.jp",
            "s.vodafone.ne.jp",
            "q.vodafone.ne.jp",
            "jp-d.ne.jp",
            "jp-h.ne.jp",
            "jp-t.ne.jp",
            "jp-c.ne.jp",
            "jp-r.ne.jp",
            "jp-k.ne.jp",
            "jp-n.ne.jp",
            "jp-s.ne.jp",
            "jp-q.ne.jp",
            "ezweb.ne.jp",
            "biz.ezweb.ne.jp",
            "ido.ne.jp",
            "ezweb.ne.jp",
            "ezweb.ne.jp",
            "sky.tkk.ne.jp",
            "sky.tkc.ne.jp",
            "sky.tu -ka.ne.jp",
            "pdx.ne.jp",
            "di.pdx.ne.jp",
            "dj.pdx.ne.jp",
            "dk.pdx.ne.jp",
            "wm.pdx.ne.jp",
            "willcom.com",
            "emnet.ne.jp",
            "vertuclub.ne.jp",
        ];
        if (in_array(substr($email, strpos($email, "@") + 1), $mobDomainEmails)) {
            return true;
        }

        return false;
    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $phone
     */
    public function isPcPhone($phone)
    {
        if (empty($phone)) {
            return false;
        }

        return !$this->isMobPhone($phone);
    }

    /**
     *
     * Enter description here ...
     * @param $number
     */
    public function isMobPhone($phone)
    {
        $mobThreeCharFirsts = ["070", "080", "090"];
        if (in_array(substr(preg_replace("/\(|\)|-/", "", $phone), 0, 3), $mobThreeCharFirsts)) {
            return true;
        }

        return false;
    }

    /**
     *
     * Enter description here ...
     * @param $from
     * @param $to
     */
    public function change(&$from, &$to)
    {
        $temp = $from;
        $from = $to;
        $to = $temp;
    }

    /**
     *
     * Multi-bytes trim function
     * @param $str
     * @param $spc
     */
    public function mb_trim($str, $spc)
    {
        mb_internal_encoding("UTF-8");
        mb_regex_encoding("UTF-8");

        return mb_ereg_replace("[$spc]*$", "", mb_ereg_replace("^[$spc]*", "", $str));
    }

    /**
     *
     * Remove all spaces (EN space, JP space) function
     * @param $str
     * @return $str (after erasing all white-spaces)
     */
    public function mb_eraseSpace($str)
    {
        $spc = " 　";
        mb_internal_encoding("UTF-8");
        mb_regex_encoding("UTF-8");

        return mb_ereg_replace("[$spc]+", "", $str);
    }

    public function mb_strtotime($sDate = null, $blnNow = true)
    {
        // 日本語版の対応
        if (preg_match('/^([0-9]{4})[年]{1}([0-9]{1,2})[月]{1}([0-9]{1,2})[日]{1}[\s　]([0-9]{1,2})[時]{1}([0-9]{1,2})[分]{1}([0-9]{1,2})[秒]{1}[\s　]*$/u', $sDate, $match)) {
            // YYYY年MM月DD日HH時MI分SS秒
            $sTimestamp = mktime($match[4], $match[5], $match[6], $match[2], $match[3], $match[1]);
        } elseif (preg_match('/^([0-9]{4})[年]([0-9]{1,2})[月]([0-9]{1,2})[日][\s　]([0-9]{1,2})[時]([0-9]{1,2})[分][\s　]*$/u', $sDate, $match)) {
            // YYYY年MM月DD日HH時MI分
            $sTimestamp = mktime($match[4], $match[5], 0, $match[2], $match[3], $match[1]);
        } elseif (preg_match('/^([0-9]{4})[年]([0-9]{1,2})[月]([0-9]{1,2})[日][\s　]*$/u', $sDate, $match)) {
            // YYYY年MM月DD日
            $sTimestamp = mktime(0, 0, 0, $match[2], $match[3], $match[1]);

        // 通常
        } else {
            $sTimestamp = strtotime($sDate, $blnNow);
        } // end if

        return $sTimestamp;
    }

    public function getEncoding($str, $default = 'auto')
    {
        foreach (['SJIS-win', 'UTF-8', 'eucJP-win', 'SJIS', 'EUC-JP'] as $charset) {
            if ($str == mb_convert_encoding($str, $charset, $charset)) {
                return $charset;
            }
        }
    }

    public function isEmptyArray($arr)
    {
        $ret = true;
        foreach ($arr as $v) {
            if (!empty($v)) {
                return false;
            }
        }

        return $ret;
    }

    /**
     * @param type $text
     * @param type $blocksize
     * @return type
     */
    public function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);

        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * @param type $text
     * @param type $blocksize
     * @return type
     */
    public function jinzai_pkcs5_unpad($text)
    {
        $pad = ord($text[strlen($text) - 1]);
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }

        return substr($text, 0, -1 * $pad);
    }

    /**
     * @return type
     */
    public function generate_random_password()
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = []; //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = mt_rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass);
    }

    // 配列にそのキーが存在すれば値を、なければnullを返す
    public function getArrayVal($arr, $key)
    {
        if (array_key_exists($key, $arr)) {
            return $arr[$key];
        } else {
            return null;
        }
    }

    // デバイス判定
    // return pc,sp,mb
    public function getDevice()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

        if ($ua) {
            $objTM = new TerminalManager();
            if ($objTM->isSmartPhone($ua)) {
                $device = "sp";
            } elseif ($objTM->isMobile($ua)) {
                $device = "mb";
            } else {
                $device = "pc";
            }
        } else {
            $device = "pc";
        }

        return $device;
    }

    /**
     * アクションパラメータの形式チェック
     * return OKの場合は引数をそのまま、NGの場合はブランクを返す
     **/
    public function checkActionParam($actionParam)
    {
        // アクションパラメータの形式を正規表現でチェック
        $action_check_pattern = '/^[a-zA-Z0-9_\-]+$/';

        if (!preg_match($action_check_pattern, $actionParam)) {
            $actionParam = ''; // false判定となる
        }

        return $actionParam;
    }

    /**
     * AB設定のランダム取得
     * @access private
     * @param string $status
     * @return string A or B
     */
    public function getScreen($status = '')
    {
        $scr = 'A';

        if ($status == '2') {
            // A固定
            $scr = 'A';
        } elseif ($status == '3') {
            // B固定
            $scr = 'B';
        } else {
            // ABランダム
            $r = mt_rand(0, 999);
            if ($r % 2 == 1) {
                $scr = 'A';
            } else {
                $scr = 'B';
            }
        }

        return $scr;
    }

    /**
     * 制御文字の削除
     * @param string $text
     * @return string $textから制御文字を削除したもの
     */
    public function removeCtrlChar($text)
    {
        return preg_replace('/[\x00-\x1F\x7F]/', '', $text);
    }

    /**
     * 絵文字の削除
     * @param string $text
     * @return string $removedText($textから絵文字を削除したもの)
     */
    public function removeEmojiChar($text)
    {
        // 一般句読点
        $removedText = preg_replace('/[\x{2000}-\x{206F}]+/u', '', $text);
        // 数学記号
        $removedText = preg_replace('/[\x{2200}-\x{22FF}]+/u', '', $removedText);
        // その他の技術用記号
        $removedText = preg_replace('/[\x{2300}-\x{23FF}]+/u', '', $removedText);
        // 制御機能用記号
        $removedText = preg_replace('/[\x{2400}-\x{243F}]+/u', '', $removedText);
        // 光学的文字認識
        $removedText = preg_replace('/[\x{2440}-\x{245F}]+/u', '', $removedText);
        // 囲み英数字
        $removedText = preg_replace('/[\x{2460}-\x{24FF}]+/u', '', $removedText);
        // 幾何学模様
        $removedText = preg_replace('/[\x{25A0}-\x{25FF}]+/u', '', $removedText);
        // その他の記号
        $removedText = preg_replace('/[\x{2600}-\x{26FF}]+/u', '', $removedText);
        // 装飾記号
        $removedText = preg_replace('/[\x{2700}-\x{27BF}]+/u', '', $removedText);
        // その他の記号と矢印
        $removedText = preg_replace('/[\x{2B00}-\x{2BFF}]+/u', '', $removedText);
        // 囲みCJK文字・月
        $removedText = preg_replace('/[\x{3200}-\x{32FF}]+/u', '', $removedText);
        // CJK互換用文字
        $removedText = preg_replace('/[\x{3300}-\x{33FF}]+/u', '', $removedText);
        // 麻雀牌
        $removedText = preg_replace('/[\x{1F000}-\x{1F02F}]+/u', '', $removedText);
        // ドミノ牌
        $removedText = preg_replace('/[\x{1F030}-\x{1F09F}]+/u', '', $removedText);
        // トランプ
        $removedText = preg_replace('/[\x{1F0A0}-\x{1F0FF}]+/u', '', $removedText);
        // 囲み英数字補助
        $removedText = preg_replace('/[\x{1F100}-\x{1F1FF}]+/u', '', $removedText);
        // 囲み表意文字補助
        $removedText = preg_replace('/[\x{1F200}-\x{1F2FF}]+/u', '', $removedText);
        // その他の記号と絵文字
        $removedText = preg_replace('/[\x{1F300}-\x{1F5FF}]+/u', '', $removedText);
        // 顔文字
        $removedText = preg_replace('/[\x{1F600}-\x{1F64F}]+/u', '', $removedText);
        // 装飾絵記号
        $removedText = preg_replace('/[\x{1F650}-\x{1F67F}]+/u', '', $removedText);
        // 記号と絵文字補助
        $removedText = preg_replace('/[\x{1F900}-\x{1F9FF}]+/u', '', $removedText);
        // 記号と絵文字拡張A
        $removedText = preg_replace('/[\x{1FA70}-\x{1FAFF}]+/u', '', $removedText);
        // 交通と地図の記号
        $removedText = preg_replace('/[\x{1F680}-\x{1F6FF}]+/u', '', $removedText);
        // 特定の絵文字のみで入力した場合の不具合対応
        $removedText = preg_replace('/[\x{FE00}-\x{FE0F}]+/u', '', $removedText);

        return $removedText;
    }

    public function inputReplacement($allRequest)
    {
        $removeBlankList = config('ini.REMOVE_BLANK_LIST');
        $halfSizeNumberList = config('ini.HALF_SIZE_NUMBER_LIST');
        $mailFormatList = config('ini.MAIL_FORMAT_LIST');
        $replaceHalfSizeList = config('ini.REPLACE_HALF_SIZE_LIST');

        foreach ($allRequest as $key => $val) {
            if (in_array($key, $removeBlankList, true)) {
                $allRequest[$key] = str_replace(["　", " "], "", $val);
            } elseif (in_array($key, $halfSizeNumberList, true)) {
                // 全角で書かれている場合半角に変換し、全角スペースを除去
                $val = trim(mb_convert_kana($val, 'as', 'UTF-8'));
                // 半角数字以外の文字列は除去
                $allRequest[$key] = preg_replace('/[^0-9]/', '', $val);
            } elseif (in_array($key, $mailFormatList, true)) {
                // 全角で書かれている場合半角に変換し、全角スペースを除去
                $val = trim(mb_convert_kana($val, 'as', 'UTF-8'));
                // 小文字に変換
                $val = strtolower($val);
                // 半角英数字、特定記号以外の文字列は除去
                $allRequest[$key] = preg_replace('/[^a-z0-9\._@\/\?\+-]/', '', $val);
            } elseif (in_array($key, $replaceHalfSizeList, true)) {
                if (isset($val)) {
                    // 全角で書かれている場合半角に変換
                    $allRequest['age'] = trim(mb_convert_kana($val, 'a', 'UTF-8'));
                }
            }
        }

        return $allRequest;
    }

    public function isNoindex($jobData)
    {
        return empty($jobData) ? true : false;
    }


    /**
     * 環境名を返す
     *
     * @return string $envName 環境名
     */
    public function getEnvName()
    {
        $env = config('app.env');
        $url = config('app.url');

        $envName = '';
        switch ($env) {
            case 'production':
                $envName = 'prd';
                break;
            case 'development':
                // 開発環境の場合、dev{n}部分を抽出
                $host = parse_url($url)['host'];
                $subDomain = explode('.', $host)[0];
                $envName = explode('-', $subDomain)[0];
                break;
            default:
                $envName = $env;
                break;
        }

        return $envName;
    }

    /**
     * 環境毎にエラー通知するかを判定
     *
     * @return bool 通知判定結果
     */
    public function isErrorNotify()
    {
        $notifyEnvs = [
            'production',
        ];

        return in_array(config('app.env'), $notifyEnvs, true);
    }
}
