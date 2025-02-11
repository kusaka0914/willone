@if (!empty($digsSfCUstomer))
    <link rel="stylesheet" type="text/css" href="{{ addQuery('/woa/reentry/pc/form8/css/style.css') }}">
    <div class="blur-overlay">

        <!-- スライダーコンテナ -->
        <form id="ajax-form">
            @include('common/_hidden_reentry')

            <div id="slider-container">
            <img src="/woa/reentry/pc/form8/img/head01.png" alt="ウィルワン">

                <div class="step-bar">
                    <div class="step-bar-num step-bar-num-active">1</div>
                    <div class="step-bar-num">2</div>
                    <div class="step-bar-num">3</div>
                </div>
                <p class="step-bar-desc">かんたん５秒！</p>
                <p id="step3-error" class="step3-error-msg" style="display: none;">退職のご意向を下記から選んでください！</p>
                <div id="slider">

                    <!-- スライド1 -->
                    <div class="slide" data-index="1">
                        <ul class="slide-wrap">
                            @foreach ($req_emp_type_list as $row)
                                <li id="req_emp_type_li_{{ $row->id }}">
                                    <label>
                                        <input type="radio" name="req_emp_type_recent"
                                            id="req_emp_type_recent[{{ $row->id }}]" value="{{ $row->emp_type }}">
                                        <img src="/woa/reentry/pc/form8/img/req_emp_type_{{ $row->id }}.png"
                                            alt="{{ $row->emp_type }}">
                                        {{ $row->emp_type }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                        <div class="stop-subscription">
                            <a class="stop-subscription-btn unsubscribe-btn">配信停止はこちら</a>
                        </div>
                    </div>
                    <!-- スライド2 -->
                    <div class="slide" data-index="2">
                        <ul class="slide-wrap">
                            @foreach ($req_date_list as $row)
                                <li id="req_emp_date_li_{{ $row->id }}">
                                    <label>
                                        <input type="radio" name="req_date_recent"
                                            id="req_date_recent[{{ $row->id }}]" value="{{ $row->req_date }}">
                                        <img src="/woa/reentry/pc/form8/img/req_date_{{ $row->id }}.png"
                                            alt="{{ $row->req_date }}">
                                        {{ $row->req_date }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                        <div class="stop-subscription stop-subscription-flex">
                            <button type="button" class="prev">戻る</button>
                            <a class="stop-subscription-btn unsubscribe-btn">配信停止はこちら</a>
                        </div>
                    </div>
                    <!-- スライド3 -->
                    <div class="slide" data-index="3">
                        <ul class="slide-wrap retirement_intention_ul">
                            @foreach (config('ini.RETIREMENT_INTENTIONS') as $key => $value)
                                <li>
                                    <label>{{ $value }}
                                        <input type="radio" name="retirement_intention"
                                            id="retirement_intention[{{ $key }}]"
                                            value="{{ $key }}">
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                        <button type="button" class="cv">詳細を確認</button>
                        <div class="stop-subscription stop-subscription-flex">
                            <button type="button" class="prev">戻る</button>
                            <a class="stop-subscription-btn unsubscribe-btn">配信停止はこちら</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="unsubscribe-box">
            <div class="relative">
                <span class="close"><span></span></span>
                <p>メールマガジン・ショートメールの配信停止は以下のアドレス宛に、<b>配信を停止するメールアドレスもしくはお電話番号</b>に加え<b>「配信停止」</b>とご記載の上お送り頂けますと幸いです。</p>
                <p>お手数をお掛けし大変申し訳ございませんが何卒よろしくお願いします。</p>
                <div class="mail-box">
                    <span>お問い合わせメールアドレス</span>
                    <p class="e-mail">info@willone.jp</p>
                </div>
            </div>
        </div>
        <span class="cover"></span>
    </div>
    <!-- jQueryは別の場所で読み込まれる -->
    <script>
        // jQueryが読み込まれたら実行
        document.addEventListener("DOMContentLoaded", function() {
            const $slider = $("#slider");
            const $slides = $(".slide");
            const containerWidth = $("#slider-container").width();
            let currentIndex = 0;

            // ラジオ選択のevent
            $('[name="req_emp_type_recent"],[name="req_date_recent"]').on("click", function() {
                if (currentIndex < $slides.length - 1) {
                    currentIndex++;
                    $slider.animate({
                            left: -currentIndex * containerWidth + "px", // 1スライド分左へ移動
                        },
                        500 // アニメーション速度（ミリ秒）
                    );
                    const currentStep = $(this).closest('.slide').attr('data-index');
                    $.sendGA(`step${currentStep}_selected`);

                    // Step bar の更新
                    $(".step-bar-num").removeClass("step-bar-num-active"); // すべてリセット
                    $(".step-bar-num").slice(0, currentIndex + 1).addClass(
                        "step-bar-num-active"); // 累積でクラスを付与
                }
            });

            // 戻るボタンのクリックイベント
            $(".prev").on("click", function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    $slider.animate({
                            left: -currentIndex * containerWidth + "px", // 1スライド分右へ移動
                        },
                        500 // アニメーション速度（ミリ秒）
                    );
                    const currentStep = $(this).closest('.slide').attr('data-index');
                    $.sendGA(`step${currentStep}_prev`);

                    // Step bar の更新
                    $(".step-bar-num").removeClass("step-bar-num-active"); // すべてリセット
                    $(".step-bar-num").slice(0, currentIndex + 1).addClass(
                        "step-bar-num-active"); // 累積でクラスを付与
                }
            });

            // 最後のラジオのevent
            $('.cv').on("click", function() {
                if (!$('[name="retirement_intention"]:checked').length) {
                    $('#step3-error').css('display', 'block');
                    setTimeout(function() {
                        $('#step3-error').fadeOut(2000);
                    }, 2000);
                    return;
                }
                $('html, body').animate({ scrollTop: 0 });
                $.sendGA('{{ strtoupper(config('app.device')) }}_CV');
                $('.blur-overlay').css('display', 'none');
                $.ajax({
                    url: '{{ route('api.modalDigs.regist') }}',
                    type: "POST",
                    dataType: "json",
                    data: jQuery(jQuery('#ajax-form')).serialize(),
                    complete: function(data) { // 成功、失敗どっちでも処理する
                        // 特に処理はなし
                    }
                });
            });
            $.sendGA = function(label) {
                // LP番号をLabel値に追加
                const template = $('[name="t"]').val();
                // GA4イベント発火用
                dataLayer.push({
                    'event': 'lp-event-push',
                    'lp-event-element': `LP${template}_${label}`
                });
            }
            $.sendGA('{{ strtoupper(config('app.device')) }}_step1_OPEN');
        });

        // 配信停止案内
        document.querySelectorAll(".unsubscribe-btn").forEach(function(button) {
            button.addEventListener("click", function() {
                document.querySelector(".unsubscribe-box").classList.add("active");
                document.querySelector(".cover").classList.add("active");
            });
        });

        document.querySelectorAll(".unsubscribe-box .close, .cover").forEach(function(element) {
            element.addEventListener("click", function() {
                document.querySelector(".unsubscribe-box").classList.remove("active");
                document.querySelector(".cover").classList.remove("active");
            });
        });

        document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        // 親の <ul> を取得
        const ul = this.closest('ul');

        if (ul && ul.classList.contains('retirement_intention_ul')) {
            // retirement_intention_ul を持つ場合
            ul.querySelectorAll('li.selected').forEach(function(selectedLi) {
                selectedLi.classList.remove('selected');
            });

            // チェックされたラジオボタンの親 <li> にクラスを追加
            const li = this.closest('li');
            if (li) {
                li.classList.add('selected');
            }

            // <ul> 内で selected が付与された場合、button.cv に cv-active を追加
            const button = document.querySelector('button.cv');
            if (button) {
                if (ul.querySelector('li.selected')) {
                    button.classList.add('cv-active');
                } else {
                    button.classList.remove('cv-active');
                }
            }
        } else {
            // retirement_intention_ul を持たない場合
            // 全ての <li> からクラスを削除
            document.querySelectorAll('li.selected').forEach(function(selectedLi) {
                selectedLi.classList.remove('selected');
            });

            // チェックされたラジオボタンの親 <li> にクラスを追加
            const li = this.closest('li');
            if (li) {
                li.classList.add('selected');
            }
        }
    });
});

    </script>
@endif
