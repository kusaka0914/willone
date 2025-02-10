<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Services\HubSpotProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // リダイレクト/リンクをSSL化する
        \URL::forceScheme('https');

        Schema::defaultStringLength(191);

        \Validator::extend('hiragana', function ($attribute, $value, $parameters, $validator) {
            // 半角空白、全角空白、全角記号、全角かなを許可
            return preg_match("/^[ぁ-んー 　！\-＠［\-｀｛\-～]+$/u", $value);
        });

        \Validator::extend('hiraganakatakana', function ($attribute, $value, $parameters, $validator) {
            // 全角かな、全角カナのみを許可
            $value = preg_replace("/[ぁ-んー 　ァ-ヴ]/u", "", $value);
            if (empty($value)) {
                return true;
            } else {
                return false;
            }
        });

        \Validator::extend('not_blank_only', function ($attribute, $value, $parameters, $validator) {
            // 空白のみは許可しない
            return !preg_match("/^[\s]+$/u", $value);
        });

        \Validator::extend('custom_email', function ($attribute, $value, $parameters, $validator) {
            // メールアドレスの形式、並びに、ドメイン名チェック
            $result = true;
            if (!preg_match('/^[\w\-]+([\.\w\-\+\?\/]+)*@[a-z0-9]+([\.a-z0-9\-])+([\.][a-z0-9\-]{2,4})$/i', $value)) {
                $result = false;
            } else {
                $host = explode("@", $value);
                // domain確認
                $result = checkdnsrr($host[1], "A") || checkdnsrr($host[1], "MX");
            }

            return $result;
        });

        \Validator::extend('custom_tel', function ($attribute, $value, $parameters, $validator) {
            // 空白のとき、何もせずに return
            if (0 >= strlen($value)) {
                return false;
            }

            // 全角→半角変換
            $value = mb_convert_kana($value, "a", "UTF-8");
            // 全角・半角スペース削除
            $value = mb_ereg_replace("\x20|　", "", $value);
            // 長音、ダッシュを半角ハイフンに置換
            $value = mb_ereg_replace("ー|ｰ|―", "-", $value);

            // 数字、ハイフン、かっこ以外が含まれるか
            if (preg_match("/[^0-9\(\)\-]/", $value)) {
                // return Ethna::raiseNotice("{form}に使用できるのは数字と-（ハイフン）、()（カッコ）だけです", E_FORM_INVALIDVALUE);
                return false;
            }

            return true;
        });

        \Validator::extend('custom_tel_format', function ($attribute, $value, $parameters, $validator) {
            // 空白のとき、何もせずに return
            if (0 >= strlen($value)) {
                return false;
            }

            // 全角→半角変換
            $value = mb_convert_kana($value, "a", "UTF-8");
            // 全角・半角スペース削除
            $value = mb_ereg_replace("\x20|　", "", $value);
            // 長音、ダッシュを半角ハイフンに置換
            $value = mb_ereg_replace("ー|ｰ|―", "-", $value);

            // かっことハイフンのチェック
            if (preg_match("/\(.*\(|\).*\)/", $value) // かっこが2組以上ある場合
                 || preg_match("/\)$/", $value) // 閉じかっこが末尾にある場合
                 || preg_match("/\-.*\-.*\-.*/", $value) // ハイフンが3つ以上ある場合
                 || preg_match("/^\-|\-$/", $value) // ハイフンが先頭・末尾にある場合
            ) {
                return false;
            }

            if (!preg_match("/^\([0-9]+\)[\-]?[0-9]+[\-]?[0-9]+$/", $value) // かっこが先頭にある場合
                 && !preg_match("/^[0-9]+[\-]?\([0-9]+\)[\-]?[0-9]+$/", $value) // かっこが真ん中にある場合
                 && !preg_match("/^[0-9]+[\-]?[0-9]+[\-]?[0-9]+$/", $value) // かっこがない場合
            ) {
                return false;
            }

            return true;
        });

        \Validator::extend('custom_tel_length', function ($attribute, $value, $parameters, $validator) {
            // 空白のとき、何もせずに return
            if (0 >= strlen($value)) {
                return false;
            }

            // 全角→半角変換
            $value = mb_convert_kana($value, "a", "UTF-8");
            // 全角・半角スペース削除
            $value = mb_ereg_replace("\x20|　", "", $value);
            // 長音、ダッシュを半角ハイフンに置換
            $value = mb_ereg_replace("ー|ｰ|―", "-", $value);

            if (is_numeric($value) && 3 > strlen($value)) {
                return false;
            }

            // 桁数チェック
            $tmp = preg_replace("/\(|\)|-/", "", $value); // $tmp は数値だけに

            if (preg_match("/^050|^060|^070|^080|^090/", $tmp)) {
                // 携帯、PHP、IP電話
                $maxlen = $minlen = 11;
            } else {
                // それ以外
                $maxlen = $minlen = 10;
            }

            // 桁数が多い場合
            if ($maxlen < strlen($tmp)) {
                return false;
            }

            // 桁数少ない場合
            if ($minlen > strlen($tmp)) {
                return false;
            }

            return true;
        });

        \Validator::extend('custom_tel_exist', function ($attribute, $value, $parameters, $validator) {
            // 空白のとき、何もせずに return
            if (0 >= strlen($value)) {
                return false;
            }

            // 全角→半角変換
            $value = mb_convert_kana($value, "a", "UTF-8");
            // 全角・半角スペース削除
            $value = mb_ereg_replace("\x20|　", "", $value);
            // 長音、ダッシュを半角ハイフンに置換
            $value = mb_ereg_replace("ー|ｰ|―", "-", $value);

            // 上2桁が「00」の場合
            if (substr($value, 0, 2) === '00') {
                return false;
            }

            // 携帯電話番号
            if (preg_match("/^050|^060|^070|^080|^090/", $value)) {
                // 4桁目が「0」の場合
                if (substr($value, 3, 1) === '0') {
                    return false;
                }
            }

            return true;
        });

        \Validator::extend('age_area', function ($attribute, $value, $parameters, $validator) {
            // 18~80歳の間以外は許可しない
            if (!preg_match("/(1[8-9]|[2-7][0-9]|80)$/", $value)) {
                return false;
            }

            return true;
        });

        \Validator::extend('ctrl_emoji_char', function ($attribute, $value, $parameters, $validator) {
            if (preg_match('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', $value) // 制御文字を許可しない
                || preg_match('/[\x{2000}-\x{206F}]+/u', $value) // 一般句読点を許可しない
                || preg_match('/[\x{2200}-\x{22FF}]+/u', $value) // 数学記号を許可しない
                || preg_match('/[\x{2300}-\x{23FF}]+/u', $value) // その他の技術用記号を許可しない
                || preg_match('/[\x{2400}-\x{243F}]+/u', $value) // 制御機能用記号を許可しない
                || preg_match('/[\x{2440}-\x{245F}]+/u', $value) // 光学的文字認識を許可しない
                || preg_match('/[\x{2460}-\x{24FF}]+/u', $value) // 囲み英数字を許可しない
                || preg_match('/[\x{25A0}-\x{25FF}]+/u', $value) // 幾何学模様を許可しない
                || preg_match('/[\x{2600}-\x{26FF}]+/u', $value) // その他の記号を許可しない
                || preg_match('/[\x{2700}-\x{27BF}]+/u', $value) // 装飾記号を許可しない
                || preg_match('/[\x{2B00}-\x{2BFF}]+/u', $value) // その他の記号と矢印を許可しない
                || preg_match('/[\x{3200}-\x{32FF}]+/u', $value) // 囲みCJK文字・月を許可しない
                || preg_match('/[\x{3300}-\x{33FF}]+/u', $value) // CJK互換用文字を許可しない
                || preg_match('/[\x{1F000}-\x{1F02F}]+/u', $value) // 麻雀牌を許可しない
                || preg_match('/[\x{1F030}-\x{1F09F}]+/u', $value) // ドミノ牌を許可しない
                || preg_match('/[\x{1F0A0}-\x{1F0FF}]+/u', $value) // トランプを許可しない
                || preg_match('/[\x{1F100}-\x{1F1FF}]+/u', $value) // 囲み英数字補助を許可しない
                || preg_match('/[\x{1F200}-\x{1F2FF}]+/u', $value) // 囲み表意文字補助を許可しない
                || preg_match('/[\x{1F300}-\x{1F5FF}]+/u', $value) // その他の記号と絵文字を許可しない
                || preg_match('/[\x{1F600}-\x{1F64F}]+/u', $value) // 顔文字を許可しない
                || preg_match('/[\x{1F650}-\x{1F67F}]+/u', $value) // 装飾絵記号を許可しない
                || preg_match('/[\x{1F900}-\x{1F9FF}]+/u', $value) // 記号と絵文字補助を許可しない
                || preg_match('/[\x{1FA70}-\x{1FAFF}]+/u', $value) // 記号と絵文字拡張Aを許可しない
                || preg_match('/[\x{1F680}-\x{1F6FF}]+/u', $value) // 交通と地図の記号を許可しない
                || preg_match('/[\x{FE00}-\x{FE0F}]+/u', $value) // 特定の絵文字のみで入力した場合の不具合対応
            ) {
                return false;
            }
            return true;
        });

        // HubSpotProviderを登録
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend('hubspot', function ($app) use ($socialite) {
            $config = $app['config']['services.hubspot'];
            return $socialite->buildProvider(HubSpotProvider::class, $config);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
