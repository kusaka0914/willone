<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    @include('common._ogp')
    <title>{{ $headtitle }}</title>
    <script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/woa/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="/woa/js/jquery.autoKana.js"></script>
    <script type="text/javascript" src="/woa/js/common/ga_common.js"></script>
    <script type="text/javascript" src="/woa/js/common/SearchCity/jquery.SearchCity.js?20191220"></script>
    <script type="text/javascript" src="/woa/js/entry/pc/form1000/jq.bxslider_for_horizscrl_form1.js?20191220"></script>
    <script type="text/javascript" src="/woa/js/entry/pc/form1000/validation_form1.js?20191220"></script>
    <script type="text/javascript" src="/woa/js/entry/pc/form1000/horizontal_scroll_form1.js?20191220"></script>
    <script type="text/javascript" src="/woa/js/entry/pc/form1000/modal_footer1.js?20191220"></script>
    <script src="{{addQuery('/woa/js/common/enterBlock.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="/woa/css/entry/pc/style1000.css?20221026">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
@include('common._gtag')

    <link rel="icon" href="/woa/favicon.ico">
</head>
<body class="step1">
    <div class="header">
        <img src="/woa/images/logo.png" class="header-logo" alt="ウィルワン">
    </div>
    <div class="contents">
    <!--mainContentsBegin-->
    <div class="topCont">
    <img src="/woa/entry/img/friend_main.png" alt="お友達紹介キャンペーン　Amazonギフト券3,000円分プレゼント！！">
    </div>
    <div class="linkArea">
    <a class="btn" href="{{$introduceEntryPath}}"><span class="ui-btn">つぎへ</span></a>
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
@include('pc.contents.include._modal_footer')

<!-- adwordsリマケタグ -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 822859503;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/822859503/?guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- /adwordsリマケタグ -->
@include('common._common_tag')
</body>
<script>
$( document ).ready(function( $ ) {
    $(window).load(function(){
        $('input').on('change',function () {
            $('input:checkbox:checked').parent().addClass('checked');
            $('input:radio:checked').parent().addClass('checked');
            $('input:not(:checked)').parent().removeClass('checked');
        });
    });
});
</script>
<script>
$(function() {
    $.fn.autoKana('#name_kan', '#name_cana', {
        katakana : false  //true：カタカナ、false：ひらがな（デフォルト）
    });
});
</script>
</html>
