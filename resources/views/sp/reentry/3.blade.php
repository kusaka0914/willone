<!DOCTYPE html>
<html>

<head>
  <meta name="robots" content="noindex,nofollow">
</head>
<p>@include('common._ogp')</p>
<title>{{ $headtitle }}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<link rel="stylesheet" type="text/css" href="{{addQuery('/woa/css/common/base.css')}}">
<link rel="stylesheet" type="text/css" href="{{addQuery('/woa/css/common/reentry.css')}}">
<link rel="stylesheet" type="text/css" href="/woa/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="/woa/css/common/form/swiper.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<p>@include('common._gtag')
  <link rel="icon" href="/woa/favicon.ico">
</p>

<body id="pagetop" class="likeModal bg-completionPage sp style-mamiya reclamation reclamation-third">
    <div class="header">
        <img src="/woa/images/logo.png" class="header-logo" alt="ウィルワン">
    </div>
    <div class="likeModal_contents ani-showFloaty">
        <div id="wrap">
            <!-- 3秒転職アンケート -->
            <div class="formMuscle formMuscle-reentry-sp">
                <div class="header-img">
                    <img src="/woa/images/header_banner_sp.png" class="" alt="willoneは国試黒本の運営会社が提供する治療家業専門の転職エージェントです">
                </div>
                <div class="formMuscle_box" style="padding-top: 1em;">
                    <div class="formMuscle_form" style="margin-top: 0;">
                        <form name="frm" action="{{config('app.url')}}/reentryfin" method="post">
                            @include('common/_hidden_reentry')
                            <input type="hidden" id="changetype" name="changetype" value="転職アンケート">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <!-- 〓〓〓　スライド　〓〓〓 -->
                                    <div class="formMuscle_formItem" style="padding:0 4px;">
                                        <div class="datetime-box">
                                            <div class="item-contions-flex mgn-t5">
                                                <span class="item-conditions item-conditions-optional">任意</span>
                                                <p class="c-form-base-title">希望連絡日時（複数選択可）</p>
                                            </div>
                                            @if($errors->has('reentry_contact_time_1'))
                                            <div class="error">
                                                <ul>
                                                    <li>
                                                        <span class="required_error">※{{$errors->first('reentry_contact_time_1')}}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            @endif
                                            @if($errors->has('reentry_contact_time_2'))
                                            <div class="error">
                                                <ul>
                                                    <li>
                                                        <span class="required_error">※{{$errors->first('reentry_contact_time_2')}}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            @endif
                                            @if($errors->has('reentry_contact_time_3'))
                                            <div class="error">
                                                <ul>
                                                    <li>
                                                        <span class="required_error">※{{$errors->first('reentry_contact_time_3')}}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            @endif
                                            <div class="datetime-item">
                                                <label>第1希望 </label>
                                                <input type="text" name="reentry_contact_time_1" id="reentry_contact_time_1" value="{{ old('reentry_contact_time_1') }}">
                                            </div>
                                            <div class="datetime-item">
                                                <label>第2希望 </label>
                                                <input type="text" name="reentry_contact_time_2" id="reentry_contact_time_2" value="{{ old('reentry_contact_time_2') }}">
                                            </div>
                                            <div class="datetime-item">
                                                <label>第3希望 </label>
                                                <input type="text" name="reentry_contact_time_3" id="reentry_contact_time_3" value="{{ old('reentry_contact_time_3') }}">
                                            </div>
                                        </div>

                                        <div>
                                        <div class="item-contions-flex mgn-t20">
                                            <span class="item-conditions item-conditions-optional">任意</span>
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
                                            <textarea name="toiawase" class="c-form-base-txtbox">{{ old('toiawase') }}</textarea>
                                        </div>
                                        <div class="reclamation-notation" style="background: #fff;">
                                            <p class="reclamation-natation-title">【個人情報の取扱いについて】</p>
                                            <ul>
                                                <li>・本フォームからお客様が記入・登録された個人情報は、資料送付・電子メール送信・電話連絡などの目的で利用・保管します。</li>
                                                <li>・<a href="/woa/privacy">プライバシーポリシー</a>に同意の上、下記ボタンを押してください。</li>
                                            </ul>
                                        </div>
                                        <div class="formMuscle_formItem" style="padding:0 4px;">
                                            <button type="submit" id="submitMob" class="formMuscle_submit" name="submitMob">相談してみる</button>
                                        </div>
                                    </div>
                                    <!-- /.formMuscle_formItem -->
                                </div>
                                <!-- /.swiper-wrapper -->
                            </div>
                            <!-- /.swiper-container -->
                        </form>
                    </div>
                    <!-- /.formMuscle_form -->
                </div>
                <!-- /.formMuscle_box -->
            </div>
            <!-- 3秒転職アンケート -->
        </div>
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
    <!-- flatpickrのメインスクリプトを読み込む -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- flatpickrの日本語ローカリゼーションを読み込む -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
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
                    // autoHeight: true,
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

            $.sendGA = function(actionType, label) {
                var action = 'none';
                if (actionType == 1) {
                    action = 'click';
                } else if (actionType == 2) {
                    action = 'edit';
                }
                // LP番号をLabel値に追加
                var lp = 'LP' + $.template_id + '_';
                if (label == '{{ strtoupper(config('app.device')) }}_CV') {
                    // 1秒後に再送信するように設定
                    setTimeout(submitGaSendForm, 1000);
                    // form submit
                    ga('send', 'event', 'listing', action, lp + label, 0, {
                        'nonInteraction': 1,
                        hitCallback: submitGaSendForm
                    });
                    // GA4イベント発火用
                    // 計測が済んだあと、または計測が済んでいないが1000ミリ秒経過した場合、submitGaSendFormを呼ぶ
                    dataLayer.push({
                        'event': 'lp-event-push',
                        'lp-event-element': lp + label,
                        eventCallback: submitGaSendForm,
                        eventTimeout: 1000
                    });
                } else {
                    ga('send', 'event', 'listing', action, lp + label, 0, {
                        'nonInteraction': 1
                    });
                    // GA4イベント発火用
                    dataLayer.push({ 'event': 'lp-event-push', 'lp-event-element': lp + label });
                }
            }
            $.sendGA(1, '{{ strtoupper(config('app.device')) }}_step1_OPEN');

            var reentry_contact_time = 0;
            var toiawase = 0;

            // 連絡希望時間
            $('input[name^="reentry_contact_time_"]').on('change', function(){
                if(reentry_contact_time == 0) {
                    reentry_contact_time = 1;
                    $.sendGA(1, 'reentry_contact_time_selected');
                }
            });



            // お問い合わせ内容
            $('.c-form-base-txtbox').on('change', function() {
                if (toiawase == 0) {
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function setupFlatpickr(selector) {
                flatpickr(selector, {
                    locale: "ja",
                    enableTime: true,
                    dateFormat: "Y-m-d",
                    time_24hr: true,
                    defaultDate: null,  // 初期日付を設定しない
                    minDate: "today",   // 今日以前の日付を選択できないようにする
                    disableMobile: true, // スマートフォンでも同じUIを使用する
                    onOpen: function(selectedDates, dateStr, instance) {
                        const timeContainer = instance.calendarContainer.querySelector('.flatpickr-time');
                        if (!timeContainer.querySelector('.custom-time-slot')) {
                            const customTimeSlot = document.createElement('select');
                            customTimeSlot.classList.add('custom-time-slot');
                            customTimeSlot.innerHTML = `
                                <option value="">選択してください</option>
                                <option value="10時〜12時">10時〜12時</option>
                                <option value="12時〜14時">12時〜14時</option>
                                <option value="14時〜18時">14時〜18時</option>
                                <option value="18時〜20時">18時〜20時</option>
                            `;
                            timeContainer.appendChild(customTimeSlot);

                            customTimeSlot.addEventListener('change', function() {
                                if (this.value) {
                                    let selectedDate = instance.selectedDates[0];
                                    if (!selectedDate) {
                                        selectedDate = new Date();
                                        instance.setDate(selectedDate);
                                    }
                                    instance.input.value = selectedDate.toLocaleDateString('ja-JP') + ' ' + this.value;
                                    instance.input.setAttribute('data-time-slot', this.value);
                                }
                            });

                            if (instance.selectedDates.length > 0) {
                                customTimeSlot.dispatchEvent(new Event('change'));
                            }
                        }
                    },
                    onValueUpdate: function(selectedDates, dateStr, instance) {
                        const timeSlot = instance.input.getAttribute('data-time-slot');
                        if (timeSlot) {
                            instance.input.value = dateStr + ' ' + timeSlot;
                        }
                    }
                });
            }

            setupFlatpickr("#reentry_contact_time_1");
            setupFlatpickr("#reentry_contact_time_2");
            setupFlatpickr("#reentry_contact_time_3");
        });
    </script>
  <!-- 共通タグ -->{{-- @include('template_pc.include._commonTag') --}}</body>

</html>
