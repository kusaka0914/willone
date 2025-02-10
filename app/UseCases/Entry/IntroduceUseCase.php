<?php

namespace App\UseCases\Entry;

class IntroduceUseCase
{

    final public function __invoke(): array
    {
        $introUrl = $this->makeUrl();
        $introMail = $this->makeMailData($introUrl['mail_url']);

        return [
            'mail_body'    => $introMail['mail_body'],
            'mail_subject' => $introMail['mail_subject'],
            'line_text'    => $this->makeLineData($introUrl['line_url']),
        ];
    }

    /**
     * 紹介URLを作成する
     *
     * @return array
     */
    private function makeUrl(): array
    {

        $baseUrl = config('app.url') . "/glp/friend2?" . http_build_query(request()->query());

        return [
            'mail_url' => $baseUrl,
            'line_url' => $baseUrl,
        ];
    }

    /**
     * Aタグmailtoに使用するsubject,bodyを作成する
     *
     * @param string $sendUrl
     * @return array
     */
    private function makeMailData(string $sendUrl): array
    {

        // PC/SP使用
        $subject = '【ご案内】' . config('ini.SITE_MEISYOU') . ' お友達紹介キャンペーン';
        $body = "より多くの皆さまに、" . config('ini.SITE_MEISYOU') . "を知って頂くため、\r\n";
        $body .= "ただ今『お友達紹介キャンペーン』を実施しております！\r\n";
        $body .= "\r\n";
        $body .= "ご登録後、ご紹介者に3,000円分のギフト券をプレゼントいたします。\r\n";
        $body .= "\r\n";
        $body .= "▼以下のURLからご登録をお願いします。\r\n";
        $body .= "{$sendUrl}\r\n";
        $body .= "\r\n";
        $body .= "ご登録をお待ちしております！\r\n";

        $result['mail_subject'] = rawurlencode($subject);
        $result['mail_body'] = rawurlencode($body);

        return $result;
    }

    /**
     * AタグでLINEの本文のテキストを作成する
     *
     * @param string $sendUrl
     * @return string
     */
    private function makeLineData(string $sendUrl): string
    {
        $url = 'http://line.naver.jp/R/msg/text/?';

        $text = "＼お友達紹介キャンペーン実施中！／\r\n";
        $text .= config('ini.SITE_MEISYOU') . "をより皆さんに知って頂く為に、『お友達紹介キャンペーン』を実施しております！\r\n";
        $text .= "ご登録後、ご紹介者に3,000円分のギフト券をプレゼントいたします。\r\n";
        $text .= "\r\n";
        $text .= "▼以下のURLからご登録をお願いします。\r\n";
        $text .= "{$sendUrl}\r\n";

        return $url . rawurlencode($text);
    }
}
