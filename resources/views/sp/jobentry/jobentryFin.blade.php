<html lang="ja">
    <head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>{{ $headtitle }}</title>
    <link rel="stylesheet" type="text/css" href="/woa/entry/css/style.css?20171228">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/woa/css/jobentry/sp/jobentryFin.css">

@include('common._gtag')
        <link rel="icon" href="/woa/favicon.ico">
</head>
<body class="step1">
  <div class="header">
    <img src="/woa/images/logo.png" class="header-logo">
  </div>
  <div class="contents">
    <div class="form">
        <form id="form" name="form1" action="/woa/postdata" method="post" target="_self" enctype="application/x-www-url-encoded">

          <div id="dialog_form" class="formContent" data-initialstate="false" style="padding: 0;">
            <div id="dialog_header" class="formHeader">
              <div class="row rowTable">&nbsp;</div>
            </div>
            <div id="dialog_content" style="clear : both;">
                <p class="message" style="padding: 10px 0 0;">ご応募ありがとうございました！</p>
                <p class="infoMessage">
                  改めて、お電話もしくはメールで求人詳細をご案内させていただきます。<br>
                  お問い合わせが立て込んでおりますので、少しお時間をいただければ幸いです。<br><br>
                  お急ぎの場合は 03-6778-5276 までお問い合わせください。<br>
                </p>
            </div>
          </div>
        </form>
    </div>
  </div>

  <div class="btnArea">
    <a href="{{ route('Top') }}" class="thanksBtn">ウィルワントップへ</a>
  </div>

  <div id="footer">
    <div class="innerfooter">
      <small>(C) SMS CO., LTD. All Rights Reserved.</small>
    </div>
  </div>
</body>
</html>
