<html lang="ja">
    <head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>{{ $headtitle }}</title>
    <link rel="stylesheet" type="text/css" href="/woa/entry/css/style.css?20171228">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="/woa/css/reentry/sp/reentryFin.css">

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
                <p class="message" style="padding: 10px 0 0;">ご回答ありがとうございました！</p>
            </div>
          </div>
        </form>
    </div>
  </div>

  @include('pc.reentry.include._recommendOrder')

  <div class="btnArea">
    <a href="{{ route('Top') }}" class="thanksBtn">ウィルワントップへ</a>
  </div>

  <div id="footer">
    <div class="innerfooter">
      <small>(C) SMS CO., LTD. All Rights Reserved.</small>
    </div>
  </div>
  <script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js"></script>
  @include('common/_hidden_reentry_popup')
</body>
</html>
