<html lang="ja">
    <head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>{{ $headtitle }}</title>
    <link rel="stylesheet" type="text/css" href="{{ addQuery('/woa/entry/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ addQuery('/woa/entry/css/entry_regist_mail_form.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ addQuery('/woa/entry/css/kurohon_resist_mail_form2.css') }}">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">

@include('common._gtag')
    <link rel="icon" href="/image_file/kurohon.ico">

</head>
<body class="step1 kurohon-form">
    <div class="header">
        <img src="/woa/img/kurohon/logo.svg" class="header-logo">
    </div>
    <div class="contents">
    <div class="form">
        <form id="form" name="form1" action="/woa/postdata" method="post" target="_self" enctype="application/x-www-url-encoded">

        <div id="dialog">
    <div class="dialog-header">
        <img src="/woa/img/kurohon/hero-img.svg" alt="国試黒本" width="70">
        <div class="dialog-header-heading">
            <p class="mb-heading">ご入力ありがとうございます。</p>
            <p class="mb-small">今後、メールアドレスまたは電話番号あてに</p>
            <p class="mb-small">国試黒本の就職情報についてご連絡させていただきます。</p>
        </div>
        @include ("common._shortholiday")
    </div>
    <div class="mgn-b40">

        <p class="dialog-sub-title">＼ 治療家の就職は私たちにお任せください ／</p>

        <div class="bg-white">
            <div class="logo-box">
                <img src="/woa/img/kurohon/logo.svg" alt="国試黒本">
                <img src="/woa/img/kurohon/logo-text.svg" alt="治療家を目指す人のための総合メディア">
            </div>
            <a href="https://kurohon.jp/event" class="dialog-box-btn dialog-kurohon-jobnote-btn dialog-kurohon-btn-main">
                <span>就職イベントを確認する</span>
                <img src="/woa/entry/img/ico_link_black.png" alt="スカウトを受けたい場合はこちら（姉妹サイトのジョブノートの登録ページに遷移します。）">
            </a>
            <p class="notation">※黒本購入・模試受験には別途黒本サイトでのご登録が必要となります。</p>
        </div>

    </div>
    <div class="dialog-box">
        <h2>就職について相談する</h2>
        <div class="dialog-box-sub">
            <h3>お電話でのご相談</h3>
            <div class="dialog-box-tel">
                <img src="/woa/entry/img/phone_x5F_on.png" alt="お電話でのご相談">
                <a hef="tel:0120633582">0120-63-3582</a>
            </div>
            <div class="dialog-box-tel-second">
                <p>または <span>03-6778-5276</span></p>
                <p>営業時間　平日 09:30～18:30 </p>
            </div>
        </div>
    </div>
    <div class="dialog-box">
        <h2>今後の流れ</h2>
        <div class="dialog-box-flow">
            <div class="dialog-box-flow-step">
                <div class="dialog-box-num">
                    1
                </div>
                <div class="dialog-box-flow-card">
                    <h4>担当と相談</h4>
                    <p>就職について気になることを気軽に相談</p>
                </div>
            </div>
            <div class="dialog-box-flow-step">
                <div class="dialog-box-num dialog-box-num-last">
                    2
                </div>
                <div class="dialog-box-flow-card">
                    <h4>就職先のご紹介</h4>
                    <p>あなたにマッチした求人をご紹介</p>
                </div>
            </div>
        </div>
    </div>
</div>



          <!-- <div id="dialog_form" class="formContent" data-initialstate="false">
            <div id="dialog_header" class="formHeader">
              <div class="row rowTable">
                ご登録完了！！！！
              </div>
            </div>
            <div id="dialog_content" style="clear : both;">
                <p class="message">ご登録ありがとうございました！kurohonteest</p>
                @include ("common._shortholiday")
                @if (preg_match('/jinzaibank.com/', $_SERVER['HTTP_HOST']))
                    <a href="{{ route('Top') }}" class="thanksBtn">ウィルワントップへ</a>
                @else
                    <a href="{{ route('TopFromOld') }}" class="thanksBtn">ウィルワントップへ</a>
                @endif
            </div>
          </div> -->
        </form>
    </div>
</div>

<div id="footer">
    <div class="innerfooter">
        <small>(C) SMS CO., LTD. All Rights Reserved.</small>
    </div>
</div>

<script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js"></script>

<script>
$(function () {
    $('#clipboard').click(function () {
        // data-urlの値を取得
        const text = $(this).data('text');

        // クリップボードにコピー
        navigator.clipboard.writeText(text);

        // フラッシュメッセージ表示
        $('.clipboard-btn p').fadeIn("slow", function () {
            $(this).delay(2000).fadeOut("slow");
        });
    });
});
</script>

@if ((config('app.url') == 'https://www.jinzaibank.com/woa' || config('app.url') == 'https://www.willone.jp')
    && (mb_truncate($name_kan,5,"") != '【テスト】' || mb_truncate($name_kan,7,"") == '【テスト】タグ' ))

<!-- Yahoo Code for your Conversion Page -->
<script type="text/javascript">
    /* <![CDATA[ */
    var yahoo_conversion_id = 1000416626;
    var yahoo_conversion_label = "7wZTCJTLn3sQtKXXhwM";
    var yahoo_conversion_value = 0;
    /* ]]> */
</script>
<script type="text/javascript" src="https://s.yimg.jp/images/listing/tool/cv/conversion.js">
</script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="https://b91.yahoo.co.jp/pagead/conversion/1000416626/?value=0&label=7wZTCJTLn3sQtKXXhwM&guid=ON&script=0&disvt=true"/>
    </div>
</noscript>
<!-- /Yahoo Code for your Conversion Page -->
@endif

@include('common._CV_Tag_other')
</body>
</html>
