<?php
namespace App\Managers;

/**
 *  CrawlerManager
 *
 *  @author
 *  @access     public
 */
class CrawlerManager
{
    // クローラーUAパターン
    const CRAWLER_UA_PATTERN = [
        '/(Y!J\-)/',
        '/(Slurp)/',
        '/(Googlebot)/',
        '/(Google\-Sitemaps)/',
        '/(adsence\-Google)/',
        '/(msnbot)/',
        '/(bingbot)/',
        '/(Hatena)/',
        '/(MicroAd)/',
        '/(Baidu)/',
        '/(MJ12bo)/',
        '/(Steeler)/',
        '/(OutfoxBot)/',
        '/(Pockey)/',
        '/(psbot)/',
        '/(Yeti)/',
        '/(Websi)/',
        '/(Wget)/',
        '/(NaverBot)/',
        '/(BecomeBot)/',
        '/(DotBot)/',
        '/(ichiro)/',
        '/(archive\.org_bot)/',
        '/(YandexBot)/',
        '/(ICC\-Crawler)/',
        '/(SiteBot)/',
        '/(TurnitinBot)/',
        '/(Purebot)/',
        '/(AhrefsBot)/',
        '/(i\-robot)/',
        '/(moba\-crawler)/',
        '/(symphonybot1\.froute\.jp)/',
        '/(LD_mobile_bot)/',
    ];

    // クローラー削除パラメータ
    const CRAWLER_REMOVE_PARAM = [
        'action',
        'PHPSESSID',
        'guid',
        'code',
    ];

    /**
     * クローラーの判定を行う
     * @param string $user_agent ユーザーエージェント
     * @return boolean
     */
    public function isCrawler($user_agent)
    {
        $crawler_flg = false;

        foreach (self::CRAWLER_UA_PATTERN as $pattern) {
            if (preg_match($pattern, $user_agent)) {
                $crawler_flg = true;
                break;
            }
        }

        return $crawler_flg;
    }

    /**
     * リンクURLの対象パラメーターを削除する
     * @param Response $response レスポンスオブジェクト
     * @return void
     */
    public function removeUrlParam($response)
    {
        $content = $response->getContent();

        $content = preg_replace_callback(
            '/<a(.*?)href="(.*?)"/',
            function ($matchs) {
                // $matchs[0] 全体
                // $matchs[1] hrefより前の属性
                // $matchs[2] hrefの値
                $attr = $matchs[1];
                // &の文字実体参照を置換
                $url = preg_replace('/&amp;/', '&', $matchs[2]);
                // URLとパラメータを分割
                $explode_url = explode('?', $url);
                // パラメータが存在する場合
                if (count($explode_url) > 1) {
                    // パラメータを分割
                    $params = explode('&', $explode_url[1]);
                    foreach ($params as $key => $val) {
                        // 対象のパラメータを削除
                        foreach (self::CRAWLER_REMOVE_PARAM as $rm_param) {
                            if (preg_match("/^{$rm_param}=/", $val)) {
                                unset($params[$key]);
                            }
                        }
                    }
                    // 削除処理後にURLを再構築
                    if ($params) {
                        $url = $explode_url[0] . '?' . implode('&', $params);
                    } else {
                        $url = $explode_url[0];
                    }
                }
                // hrefより前の属性が存在する場合
                if (trim($attr) != '') {
                    $attr = stripslashes($attr);
                }

                return '<a' . $attr . 'href="' . $url . '"';
            },
            $content
        );

        $response->setContent($content);
    }
}
