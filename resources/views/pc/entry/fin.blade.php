<html lang="ja">
    <head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>{{ $headtitle }}</title>
    <link href="{{addQuery('/woa/entry/css/style.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/woa/entry/css/entry_regist_mail_form.css?20210603">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">

@include('common._gtag')
    <link rel="icon" href="/woa/favicon.ico">

@if(!empty($redirect_url))
    <meta http-equiv="refresh" content="3;url={{$redirect_url}}">

    <style text="css">
        .loading {
            display: none;
            background-color: black;
            opacity: 0.2;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0px;
            left: 0px;
        }

        .circle{
            width: 50px;
            height: 50px;
            border-radius:150px;
            border:5px solid #fff;
            border-top-color:rgba(0,0,0,0.3);
            box-sizing:border-box;
            top:20%;
            left:35%;
            animation:circle 1s linear infinite;
            -webkit-animation:circle 1s linear infinite;
            margin: 20% auto;
        }
        @keyframes circle{
            0%{transform:rotate(0deg)}
            100%{transform:rotate(360deg)}
        }
        @-webkit-keyframes circle{
            0%{-webkit-transform:rotate(0deg)}
            100%{-webkit-transform:rotate(360deg)}
        }
    </style>
@endif

</head>
<body class="step1 entry-fin">
    <div class="header">
        <img src="/woa/images/logo.png" class="header-logo">
    </div>
    <div class="contents">
    <div class="form">
        <h1><img src="/woa/entry/img/title01.png" alt="人気の非公開求人をご紹介！"></h1>
        <form id="form" name="form1" action="/woa/postdata" method="post" target="_self" enctype="application/x-www-url-encoded">

          <div id="dialog_form" class="formContent border-top-sp" data-initialstate="false">
            <div id="dialog_header" class="formHeader">
              <div class="row rowTable">
                ご登録完了
              </div>
            </div>
            <div id="dialog_content" style="clear : both;">
                @if($feed_transition_flag)
                <p class="message">ご応募ありがとうございました！</p>
                @else
                <p class="message">ご登録をいただきありがとうございます。<br/>後ほど担当者よりご本人様確認のため、<br/>ご連絡をさせていただきます。</p>
                @endif
                @include ("common._shortholiday")
                <a href="{{$job_note_entry_url}}" class="thanksBtn thanksBtn-main">
                    <div class="thanksBtn-text">
                        スカウトを受けたい方はこちら
                    </div>
                    <span>姉妹サイトの「JOBNOTE」登録ページに遷移します。</span>
                </a>
                <a href="@if (preg_match('/jinzaibank.com/', $_SERVER['HTTP_HOST'])){{ route('Top') }}@else{{ route('TopFromOld') }}@endif" class="thanksBtn">ウィルワントップへ</a>
            </div>
          </div>
        </form>
    </div>
</div>

<div id="footer">
    <div class="innerfooter">
        <small>(C) SMS CO., LTD. All Rights Reserved.</small>
    </div>
</div>

@if(!empty($redirect_url))
<div id="loading" class="loading">
    <div class="circle"></div>
</div>
@endif

<script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js"></script>

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
