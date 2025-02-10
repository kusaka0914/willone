<!DOCTYPE html>
<html>

<head>
    <meta name="robots" content="noindex,nofollow">
    @include('common._ogp')
    <title>{{ $headtitle }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport"
        content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/woa/css/common/base.css">
    <link rel="stylesheet" type="text/css" href="{{addQuery('/woa/css/common/reentry.css')}}">
    <link rel="stylesheet" type="text/css" href="{{addQuery('/woa/reentry/pc/form7/css/style7.css')}}">
    <link rel="stylesheet" type="text/css" href="/woa/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{addQuery('/woa/reentry/pc/form7/css/swiper-bundle.min.css')}}">
    @include('common._gtag')
    <link rel="icon" href="/woa/favicon.ico">
</head>

<body id="pagetop" class="likeModal bg-completionPage {{config('app.device')}}">
    <div class="header">
        <img src="/woa/reentry/pc/form7/img/head01.png" class="header-logo" alt="ウィルワン">
    </div>
    <div class="likeModal_contents ani-showFloaty">

        <div id="wrap">

            <!-- 3秒転職アンケート -->
            <div class="formMuscle">
                <div class="formMuscle_box step1 {{config('app.device')}}" style="padding-top: 1em;">
                    <div class="step-bar">
                        <div class="step-bar-num step-bar-num-active">1</div>
                        <div class="step-bar-num">2</div>
                        <div class="step-bar-num">3</div>
                    </div>
                    <p class="step-bar-desc">かんたん５秒！</p>
                    <div class="formMuscle_form" style="margin-top: 0;">
                        <form name="frm" action="{{config('app.url')}}/reentryfin" method="post"
                            onsubmit="disableButton();">
                            @include ('common/_hidden_reentry')
                            <input type="hidden" id="changetype" name="changetype" value="転職アンケート">

                            <div class="swiper-container">
                                <div class="swiper-wrapper">

                                    <!-- 〓〓〓　スライド　〓〓〓 -->
                                    <div class="swiper-slide swiper-no-swiping">
                                        <h2 class="formMuscle_title itemTitle">
                                            ご希望の働き方を教えて下さい
                                        </h2>
                                        <div class="formMuscle_formItem" style="padding:0 4px;">

                                            <div class="formMuscle_error" id="errorMsg_req_emp_type_recent"
                                                style="display: none;">ご希望の雇用形態を下記から選んでください！</div>
                                            @if($errors->has('req_emp_type'))
                                                <div class="error">
                                                    <ul>
                                                        <li><span
                                                                class="required_error">※{{$errors->first('req_emp_type')}}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                            <ul class="select-list-recent2 oneColumn" style=" padding-left: 0;">
                                                @foreach ($req_emp_type_list as $key => $row)
                                                    <li id="req_emp_type_li_{{ $key }}">
                                                        <input type="radio" name="req_emp_type_recent[]"
                                                            id="req_emp_type_recent[{{ $key }}]" value="{{ $key }}" @if (old('req_emp_type') == $key) checked="checked" @endif>
                                                        <label for="req_emp_type_recent[{{ $key }}]">
                                                            <img src="/woa/reentry/pc/form7/img/req_emp_type_{{ $key }}.png"
                                                                alt="{{ $row }}">
                                                            {!! nl2br($row) !!}
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div><!-- /.formMuscle_formItem -->
                                        <div class="formMuscle_formItem" style="padding:0 4px;">
                                        </div><!-- /.formMuscle_formItem -->
                                    </div><!-- /.swiper-slide -->

                                    <!-- 〓〓〓　スライド　〓〓〓 -->
                                    <div class="swiper-slide swiper-no-swiping">
                                        <h2 class="formMuscle_title itemTitle">
                                            転職希望時期を教えて下さい
                                        </h2>

                                        <div class="formMuscle_formItem" style="padding:0 4px;">

                                            <div class="formMuscle_error" id="errorMsg_req_date_recent"
                                                style="display: none;">ご希望の転職希望時期を下記から選んでください！</div>
                                            @if($errors->has('req_date'))
                                                <div class="error">
                                                    <ul>
                                                        <li><span
                                                                class="required_error">※{{$errors->first('req_date')}}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                            <ul class="select-list-recent2 oneColumn req_emp_date-ul" style=" padding-left: 0;">
                                                @foreach ($req_date_list as $key => $row)
                                                    <li id="req_emp_date_li_{{ $key }}">
                                                        <input type="radio" name="req_date_recent[]"
                                                            id="req_date_recent[{{ $key }}]" value="{{ $key }}" @if (old('req_date') == $key) checked="checked" @endif>
                                                        <label for="req_date_recent[{{ $key }}]">
                                                            <img src="/woa/reentry/pc/form7/img/req_date_{{ $key }}.png"
                                                                alt="{{ $row }}">
                                                            {{ $row }}
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div><!-- /.formMuscle_formItem -->
                                        <div class="formMuscle_formItem" style="padding:0 4px;">
                                            <p class="slide-back">
                                                < 戻る</p>
                                        </div><!-- /.formMuscle_formItem -->
                                    </div><!-- /.swiper-slide -->

                                    <!-- 〓〓〓　スライド　〓〓〓 -->
                                    <div class="swiper-slide swiper-no-swiping">
                                        <div class="formMuscle_formItem" style="padding:0 4px;">
                                            <h2 class="formMuscle_title itemTitle">
                                                退職のご意向を教えて下さい
                                            </h2>

                                            <div class="formMuscle_error" id="errorMsg_req_retirement_recent"
                                                style="display: none;">退職のご意向を下記から選んでください！</div>
                                            @if($errors->has('retirement_intention'))
                                                <div class="error">
                                                    <ul>
                                                        <li><span
                                                                class="required_error">※{{$errors->first('retirement_intention')}}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                            <ul class="select-list-recent2 oneColumn req_retirement_recent_ul"
                                                style=" padding-left: 0;">
                                                @foreach ($req_retirement_list as $key => $value)
                                                    <li>
                                                        <input type="radio" name="req_retirement_recent[]"
                                                            id="req_retirement_recent[{{ $key }}]" value="{{ $key }}" @if (old('retirement_intention') == $key) checked="checked" @endif>
                                                        <label for="req_retirement_recent[{{ $key }}]">{{ $value }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div><!-- /.formMuscle_formItem -->
                                        <div class="formMuscle_formItem" style="padding:0 4px;">
                                            <button type="button" id="submitMob" class="formMuscle_submit disabled"
                                                name="submitMob"
                                                onclick="setBtnAction('job_detail'); submitOnce();">詳細を確認</button>
                                        </div>
                                        <p class="slide-back">
                                            < 戻る</p>

                                    </div><!-- /.swiper-slide -->
                                </div><!-- /.swiper-wrapper -->
                            </div><!-- /.swiper-container -->
                            <input type="hidden" name="complete_modal" value="select_contact_time">
                        </form>
                    </div>
                    <!-- /.formMuscle_form -->
                </div>
                <!-- /.formMuscle_box -->
                <span class="unsubscribe-btn">配信停止はコチラ</span>
            </div>
            <!-- 3秒転職アンケート -->

        </div><!-- /wrap -->

    </div>
    <!-- / .boxFlex_contents -->
    <div class="unsubscribe-box">
        <span class="close"><span></span></span>
        <p>メールマガジン・ショートメールの配信停止は以下のアドレス宛に、<b>配信を停止するメールアドレスもしくはお電話番号</b>に加え<b>「配信停止」</b>とご記載の上お送り頂けますと幸いです。</p>
        <p>お手数をお掛けし大変申し訳ございませんが何卒よろしくお願いします。</p>
        <div class="mail-box">
            <span>お問い合わせメールアドレス</span>
            <p class="e-mail">info@willone.jp</p>
        </div>
    </div>
    <span class="cover"></span>
    <script aysnc type="text/javascript" src="/woa/js/jquery-1.11.1.min.js"></script>
    <script aysnc type="text/javascript" src="/woa/js/common/form/disableButton.js"></script>
    <script aysnc type="text/javascript" src="/woa/js/common/form/enterBlock.js"></script>
    <script aysnc type="text/javascript" src="/woa/js/common/form/LAB.js"></script>
    <script aysnc type="text/javascript" src="/woa/js/common/form/itemSelected.js"></script>
    <script type="text/javascript" src="/woa/js/common/form/ga_normal.js"></script>
    <script type="text/javascript">
        // hidden値を設定
        function setBtnAction(act, no) {
            var btnact = act;
            document.getElementById("btn_action").value = btnact;
        }
        // エラーメッセージ表示（指定IDをdisplay: block にする）
        function showErrorMsg(elem) {
            var target = document.getElementById(elem);
            if (target.style.display != 'block') {
                target.style.display = 'block';

                setTimeout(function () {
                    target.style.display = 'none';
                }, 2500);

            }
        }
        // エラーメッセージ表示（指定IDをdisplay: none にする）
        function hiddenErrorMsg(elem) {
            var target = document.getElementById(elem);
            if (target.style.display != 'none') {
                target.style.display = 'none';
            }
        }

        var cnt_submit = 0;
        // 二重登録防止
        function submitOnce() {
            if (cnt_submit == 0) {
                if (($.validate(3))) {
                    $.sendGA(1, '{{ strtoupper(config('app.device')) }}_CV');
                    cnt_submit++;
                }
            }
        }
    </script>
    <script aysnc type="text/javascript" src="/woa/js/common/form/jquery.validate_multiStepForm.js"></script>
    <script src="{{addQuery('/woa/reentry/pc/form7/js/swiper-bundle.min.js')}}"></script>


    <script>
        $(function () {
            var swiper = null;

            // ステップバーの要素を取得
            const stepBarNums = document.querySelectorAll('.step-bar-num');

            // ステップバーを更新する関数
            function updateStepBar(index) {
                stepBarNums.forEach((num, idx) => {
                    // 現在のスライドインデックス以下のスライドにアクティブクラスを付与
                    if (idx <= index) {
                        num.classList.add('step-bar-num-active');
                    } else {
                        num.classList.remove('step-bar-num-active');
                    }
                });
            }

            // Slider
            if (swiper == null) {
                swiper = new Swiper('.swiper-container', {
                    speed: 300,
                    pagination: '.swiper-pagination',
                    paginationClickable: false,
                    preventClicks: false,
                    onlyExternal: false,
                    autoHeight: true,
                    on: {
                        // スライド変更時にステップバーを更新
                        slideChange: function () {
                            updateStepBar(swiper.realIndex);
                        },
                    },
                });

                // 初期状態でステップバーを更新
                updateStepBar(swiper.realIndex);

            } else {
                swiper.slideTo(0, 0);
                // スライドをリセットした場合もステップバーを更新
                updateStepBar(0);
            }
            $.validate = function (step) {
                if (step == 1) {
                    // 希望雇用形態
                    var res = true;
                    if (!$("input[name='req_emp_type_recent[]']").is(':checked')) {
                        showErrorMsg('errorMsg_req_emp_type_recent');
                        return false;
                    } else {
                        hiddenErrorMsg('errorMsg_req_emp_type_recent');
                    }
                    return res;
                } else if (step == 2) {
                    // 転職希望時期
                    var res = true;
                    if (!$("input[name='req_date_recent[]']").is(':checked')) {
                        showErrorMsg('errorMsg_req_date_recent');
                        return false;
                    } else {
                        hiddenErrorMsg('errorMsg_req_date_recent');
                    }
                    return res;
                } else if (step == 3) {
                    // 退職意向
                    var res = true;
                    if (!$("input[name='req_retirement_recent[]']").is(':checked')) {
                        showErrorMsg('errorMsg_req_retirement_recent');
                        res = false;
                    } else {
                        hiddenErrorMsg('errorMsg_req_retirement_recent');
                    }
                    return res;
                }
                return true;
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
            var req_emp_type_recent_changed = 0;
            var req_date_recent_changed = 0;
            var req_retirement_recent_changed = 0;

            // 希望雇用形態
            $('input[name="req_emp_type_recent[]"]').on('change', function () {
                if (req_emp_type_recent_changed == 0) {
                    req_emp_type_recent_changed = 1;
                    $.sendGA(1, 'step1_selected');
                }
                if ($.validate(1)) {
                    // 次へ
                    swiper.slideNext();
                    $(".formMuscle_box").removeClass('step1');
                    $(".formMuscle_box").addClass('step2');
                    return true;
                }
            });

            // 転職希望時期
            $('input[name="req_date_recent[]"]').on('change', function () {
                if (req_date_recent_changed == 0) {
                    req_date_recent_changed = 1;
                    $.sendGA(1, 'step2_selected');
                }
                if ($.validate(2)) {
                    swiper.slideNext();
                    $(".formMuscle_box").removeClass('step2');
                    $(".formMuscle_box").addClass('step3');
                    return true;
                }
            });
            // 退職のご意向
            $('input[name="req_retirement_recent[]"]').on('change', function () {
                if (req_retirement_recent_changed == 0) {
                    req_retirement_recent_changed = 1;
                    $.sendGA(1, 'step3_selected');
                }
            });
            // イベント
            $("#button-next").on('click', function () {
                if ($.validate(1)) {
                    // 次へ
                    swiper.slideNext();
                    $(".formMuscle_box").removeClass('step1');
                    $(".formMuscle_box").addClass('step2');
                    return true;
                }
                return false;
            });
            $("#button-next2").on('click', function () {
                if ($.validate(2)) {
                    swiper.slideNext();
                    $(".formMuscle_box").removeClass('step2');
                    $(".formMuscle_box").addClass('step3');
                    return true;
                }
                return false;
            });
            $("#submitMob").on('click', function () {
                if ($.validate(3)) {
                    return true;
                }
                return false;
            });
            $("input[name='req_emp_type_recent[]']").change(function () {
                $('#req_emp_type').val($(this).val());
                hiddenErrorMsg('errorMsg_req_emp_type_recent');
                $("#button-next").removeClass('disabled');
            });
            $("input[name='req_date_recent[]']").change(function () {
                if ($.validate(2)) {
                    $('#req_date').val($(this).val());
                    if ($("#button-next2").size() > 0) {
                        $("#button-next2").removeClass('disabled');
                    }
                } else {
                    if ($("#button-next2").size() > 0) {
                        $("#button-next2").addClass('disabled');
                    }
                }
            });
            $("input[name='req_retirement_recent[]']").change(function () {
                if ($.validate(3)) {
                    $('#retirement_intention').val($(this).val());
                    $("#submitMob").removeClass('disabled');
                } else {
                    $("#submitMob").addClass('disabled');
                }
            });

            // 値保持用
            if ($("input[name='req_emp_type_recent[]']").is(':checked')) {
                $('#req_emp_type').val($("input[name='req_emp_type_recent[]']:checked").val());
                $("#button-next").removeClass('disabled');
            }
            if ($("input[name='req_date_recent[]']").is(':checked')) {
                $('#req_date').val($("input[name='req_date_recent[]']:checked").val());
                if ($("#button-next2").size() > 0) {
                    $("#button-next2").removeClass('disabled');
                }
            }
            if ($("input[name='req_retirement_recent[]']").is(':checked')) {
                $('#retirement_intention').val($("input[name='req_retirement_recent[]']:checked").val());
                $("#submitMob").removeClass('disabled');
            }
            // 配信停止案内
            $(".unsubscribe-btn").on('click', function () {
                $(".unsubscribe-box").addClass('active');
                $(".cover").addClass('active');
            });
            $(".unsubscribe-box .close, .cover").on('click', function () {
                $(".unsubscribe-box").removeClass('active');
                $(".cover").removeClass('active');
            });

            // "戻る" ボタンのイベント設定
            document.querySelectorAll('.slide-back').forEach(button => {
                button.addEventListener('click', () => {
                    swiper.slidePrev(); // 前のスライドに移動
                });
            });


        });
    </script>

    <!-- 共通タグ -->
    {{-- @include('template_pc.include._commonTag') --}}
</body>

</html>
