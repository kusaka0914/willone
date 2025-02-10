<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    @include('common._ogp')
    <title>ウィルワンエージェント</title>
    <script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js"></script>
    <link rel="icon" href="/woa/favicon.ico">
    <link href="{{addQuery('/woa/css/static/introduce.css')}}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ addQuery('/woa/js/modal_footer.js') }}"></script>
</head>
<body>
    <div class="header">
        <img src="/woa/images/logo.png" class="header-logo" alt="ウィルワン">
    </div>

    <div class="contents">
        <!--mainContentsBegin-->
        <div class="topCont">
            <img src="/woa/entry/img/friend_main.png" alt="お友達紹介キャンペーン　Amazonギフト券3,000円分プレゼント！！">
            <img src="/woa/img/introduce/dots.png" alt="" width="920" height="77" class="arrow">
            <div class="linkArea">
                <div class="mail">
                    <img src="/woa/img/introduce/title-mail.png" alt="メールで送る">
                    <div class="linkArea-inner">
                        <a href="mailto:?subject={{ $mail_subject }}&body={{ $mail_body }}" class="ui-link">メールで送る</a>
                    </div>
                </div>
                <div class="line">
                    <img src="/woa/img/introduce/title-line.png" alt="LINEで送る">
                    <div class="linkArea-inner">
                        <a href="{{ $line_text }}" class="ui-link">LINEで送る</a>
                    </div>
                </div>
            </div>
            <div class="caution">
                <h3>注意事項</h3>
                <ul>
                    <li>
                        ※プレゼント対象となるご条件
                        <ul>
                            <li>柔道整復師、鍼灸師、あん摩マッサージ指圧師のいずれかの資格を持っている方、または資格取得予定の学生の方</li>
                            <li>※上記資格および上記資格取得予定の学生以外の方は、条件が異なる場合がございます。各紹介先ページでご確認ください。</li>
                            <li>紹介者氏名を入力して登録している</li>
                            <li>登録後30日以内に当社キャリアパートナーと面談を完了している</li>
                        </ul>
                    </li>
                    <li>※次の場合につきましては、キャンペーン対象外となりますのでご了承ください。
                    <ul>
                        <li>既にキャンペーンによりAmazonギフト券を受け取ったことがある方の再登録の場合</li>
                        <li>紹介者様の登録履歴を当社にて確認できない場合</li>
                    </ul>
                    </li>
                    <li>※土日祝日などにより発送が数日前後することがありますのであらかじめご了承ください。</li>
                    <li>※Amazonギフト券はお友達のご登録確認後、翌月中旬に原則Eメールにて発送させていただきます。発送後に不在、宛所不明、受領拒否、その他の理由で返送されてきた場合、お客様に電話もしくはEメールにてご連絡いたします。 ご連絡に対するお客様からのご回答の有無に寄らず、返送日（当社受領日）から三ヶ月を過ぎた場合は、 権利失効となりますのでご注意ください。返送日から三ヶ月の間は、お客様からご連絡をいただきましたら、 ご連絡翌月の付与時期に合わせて、再度ギフト券を発送させていただきます。 その後当社に返送がされた場合についても、権利失効の起算日は最初の返送日となります。</li>
                    <li>※内容は予告なく変更となる場合があります。何卒ご了承ください。</li>
                </ul>
            </div>
            </div>
    </div>

    <footer>
        @include('pc.contents.include._modal_footer')
    </footer>
</body>
</html>
