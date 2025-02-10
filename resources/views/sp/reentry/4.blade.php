<!DOCTYPE html>
<html>

<head>
  <meta name="robots" content="noindex,nofollow">
</head>
<p>@include('common._ogp')</p>
<title>{{ $headtitle }}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<link rel="stylesheet" type="text/css" href="/woa/css/common/base.css">
<link rel="stylesheet" type="text/css" href="{{addQuery('/woa/css/common/reentry.css')}}">
<link rel="stylesheet" type="text/css" href="/woa/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="/woa/css/common/form/swiper.min.css">
<p>@include('common._gtag')
  <link rel="icon" href="/woa/favicon.ico">
</p>

<body id="pagetop" class="likeModal sp style-mamiya reclamation reclamation-second">
  <div class="header">
    <img src="/woa/images/logo.png" class="header-logo" alt="ウィルワン">
  </div>
  <div class="likeModal_contents ani-showFloaty">
    <div id="wrap">

      <div class="header-banner header-banner-sp">
        <img src="/woa/images/header_banner_sp.png" alt="willoneは国試黒本の運営会社が提供する治療家業専門の転職エージェントです。">
      </div>

      <!-- 3秒転職アンケート -->
      <div class="">
        <div class="">
          <div class="formMuscle_form">
            <form name="frm" action="{{config('app.url')}}/reentryfin" method="post">
             @include ( 'common/_hidden_reentry' )
              <input type="hidden" id="changetype" name="changetype" value="転職アンケート">
              <div class="swiper-container">
                <div class="swiper-wrapper">
                  <!-- 〓〓〓　スライド　〓〓〓 -->
                  <div class="swiper-slide swiper-no-swiping">
                    <!-- /.formMuscle_formItem -->
                    <div class="formMuscle_formItem" style="padding:0 4px;">
                        <div class="formMuscle_error" id="errorMsg_req_date_recent" style="display: none;">連絡希望時間帯（複数選択可）</div>
                        <div class="c-form-title-wrap">
                            <span class="form-label form-label-any">任意</span>
                            <p class="c-form-base-title">連絡希望時間帯（複数選択可）</p>
                        </div>
                        <div class="reclamation-notation">
                            ご状況に合わせて専任スタッフよりメールかお電話でご連絡させていただきます。ご都合のつきやすいお時間をお選びください。
                        </div>
                        @if($errors->has('reentry_contact_time'))
                        <div class="error">
                        <ul>
                            <li>
                            <span class="required_error">※{{$errors->first('reentry_contact_time')}}</span>
                            </li>
                        </ul>
                        </div>
                        @endif
                        <ul class="select-list-recent2 oneColumn" style=" padding-left: 0;">
                        @foreach(config('ini.REENTRY_CONTACT_TIME') as $key => $one)
                        <li>
                            <input type="checkbox" name="reentry_contact_time[]" id="reentry_contact_time[{{ $key }}]" value="{{ $key }}" {{ !empty(old("reentry_contact_time")) && in_array((string)$key, old("reentry_contact_time"), true) ? 'checked' : ''}}>
                            <label for="reentry_contact_time[{{ $key }}]">
                            <i class="fa fa-check"></i>{{ $one }}</label>
                        </li>
                        @endforeach
                        </ul>
                    </div>

                    <div>
                        <div class="c-form-title-wrap">
                            <span class="form-label form-label-any">任意</span>
                            <p class="c-form-base-title">お問い合わせ内容</p>
                        </div>
                        @if($errors->has('toiawase'))
                        <div class="error">
                          <ul>
                            <li>
                              <span class="required_error">※{{$errors->first('toiawase')}}</span>
                            </li>
                          </ul>
                        </div>
                        @endif
                        <textarea name="toiawase" class="c-form-base-txtbox">{{ old('toiawase')}}</textarea>
                    </div>
                    <div class="reclamation-notation reclamation-policy" style="background: #fff;">
                        <p class="reclamation-natation-title">
                        【個人情報の取扱いについて】
                        </p>
                        <ul>
                            <li>・本フォームからお客様が記入・登録された個人情報は、資料送付・電子メール送信・電話連絡などの目的で利用・保管します。</li>
                            <li>・<a href="/woa/privacy">プライバシーポリシー</a>に同意の上、下記ボタンを押してください。</li>
                        </ul>
                    </div>
                    <div class="formMuscle_formItem" style="padding:0 4px;">
                        <button type="submit" id="submitMob" class="formMuscle_submit" name="submitMob">この内容で問い合わせる</button>
                    </div>
                  </div>
                  <!-- /.formMuscle_formItem -->

                </div>
                <!-- /.swiper-slide -->
              </div>
              <!-- /.swiper-wrapper -->
            </form>
          </div>
          <!-- /.swiper-container -->
        </div>
        <!-- /.formMuscle_form -->
      </div>
      <!-- /.formMuscle_box -->
      <span class="unsubscribe-btn">配信停止はコチラ</span>
    </div>
    <!-- 3秒転職アンケート -->
  </div>
  <!-- /wrap -->
  <!-- / .boxFlex_contents -->
  <div class="unsubscribe-box">
    <span class="close">
      <span>
      </span>
    </span>
    <p>メールマガジン・ショートメールの配信停止は以下のアドレス宛に、
      <b>配信を停止するメールアドレスもしくはお電話番号</b>に加え
      <b>「配信停止」</b>とご記載の上お送り頂けますと幸いです。</p>
    <p>お手数をお掛けし大変申し訳ございませんが何卒よろしくお願いします。</p>
    <div class="mail-box">
      <span>お問い合わせメールアドレス</span>
      <p class="e-mail">info@willone.jp</p>
    </div>
  </div>
  <span class="cover">
  </span>
  <script aysnc type="text/javascript" src="/woa/js/jquery-1.11.1.min.js?20190910"></script>
  <script aysnc type="text/javascript" src="/woa/js/common/form/disableButton.js?20190910"></script>
  <script aysnc type="text/javascript" src="/woa/js/common/form/enterBlock.js?20190910"></script>
  <script aysnc type="text/javascript" src="/woa/js/common/form/LAB.js?20190910"></script>
  <script aysnc type="text/javascript" src="/woa/js/common/form/itemSelected.js?20190910"></script>
  <script type="text/javascript" src="/woa/js/common/form/ga_normal.js?20190910"></script>
  <script type="text/javascript">
  // hidden値を設定
  function setBtnAction(act,no) {
    var btnact = act;
    document.getElementById("btn_action").value = btnact;
  }
  // エラーメッセージ表示（指定IDをdisplay: block にする）
  function showErrorMsg(elem){
    var target = document.getElementById(elem);
    if (target.style.display != 'block') {
      target.style.display = 'block';

      setTimeout( function(){
        target.style.display = 'none';
      }, 2500);

    }
  }
  // エラーメッセージ表示（指定IDをdisplay: none にする）
  function hiddenErrorMsg(elem){
    var target = document.getElementById(elem);
    if (target.style.display != 'none') {
      target.style.display = 'none';
    }
  }

  </script>
  <script aysnc type="text/javascript" src="/woa/js/common/form/jquery.validate_multiStepForm.js?20180221"></script>
  <script src="/woa/js/common/form/swiper.jquery.min.js?20180221"></script>
  <script>
  $(function() {
    var swiper = null;
    // Slider
    if (swiper == null) {
      swiper = new Swiper('.swiper-container', {
        speed: 300,
        pagination: '.swiper-pagination',
        paginationClickable: false,
        preventClicks: false,
  // TODO 一旦コメント
  //      autoHeight: true,
        onlyExternal: false,
      });
    } else {
      swiper.slideTo(0, 0);
    }
    $.template_id = $(':hidden[name="t"]').val();

    var formSubmitted = false;
      // トラッキングブロック等で送信されなかった場合、再送信を行う
      function submitGaSendForm() {
        if (!formSubmitted) {
          formSubmitted = true;
          document.frm.submit();
        }
      }

    $.sendGA = function (actionType, label) {
      var action = 'none';
      if (actionType == 1) {
        action = 'click';
      } else if (actionType == 2) {
        action = 'edit';
      }
      // LP番号をLabel値に追加
      var lp = 'LP' + $.template_id + '_';
      if(label == '{{ strtoupper(config('app.device')) }}_CV'){
        // 1秒後に再送信するように設定
        setTimeout(submitGaSendForm, 1000);
        // form submit
        ga('send', 'event', 'listing', action, lp + label, 0, {
          'nonInteraction': 1,
          hitCallback: submitGaSendForm
        });
        // GA4イベント発火用
        // 計測が済んだあと、または計測が済んでいないが1000ミリ秒経過した場合、submitGaSendFormを呼ぶ
        dataLayer.push({ 'event': 'lp-event-push', 'lp-event-element': lp + label, eventCallback: submitGaSendForm, eventTimeout: 1000 });
      } else {
        ga('send', 'event', 'listing', action, lp + label, 0, {
          'nonInteraction': 1
        });
        // GA4イベント発火用
        dataLayer.push({ 'event': 'lp-event-push', 'lp-event-element': lp + label });
      }
    }
    $.sendGA(1, '{{ strtoupper(config('app.device')) }}_step1_OPEN');
    var reclamation_select = 0;
    var reentry_contact_time = 0;
    var toiawase = 0;

    // 連絡希望時間
    $('input[name="reentry_contact_time[]"]').on('change', function(){
      if(reentry_contact_time == 0) {
        reentry_contact_time = 1;
        $.sendGA(1, 'reentry_contact_time_selected');
      }
    });
    // お問い合わせ内容
    $('.c-form-base-txtbox').on('change', function(){
      if(toiawase == 0) {
        toiawase = 1;
        $.sendGA(1, 'toiawase_input');
      }
    });

    $("#submitMob").on('click', function() {
        $.sendGA(1, '{{ strtoupper(config('app.device')) }}_CV');
    });
    // 配信停止案内
    $(".unsubscribe-btn").on('click', function() {
      $(".unsubscribe-box").addClass('active');
      $(".cover").addClass('active');
    });
    $(".unsubscribe-box .close, .cover").on('click', function() {
      $(".unsubscribe-box").removeClass('active');
      $(".cover").removeClass('active');
    });

  });
  </script>
  <!-- 共通タグ -->{{-- @include('template_pc.include._commonTag') --}}</body>

</html>
