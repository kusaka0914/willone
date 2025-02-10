/**
 * GAタグの送信処理
 */
$(function($) {

    var lpNo = '';
    // ------------------------------------------------------------------------
    // テンプレート番号を取得
    // ------------------------------------------------------------------------
    lpNo = $(':hidden[name="t"]').val();
    if (!lpNo) {
        // LP値をURLから取得
        var urls = location.href.match(/PC_\d{1,}|SP_\d{1,}/g);
        if (urls && urls[0]) {
            lpNo = urls[0];
        }
    }

    // ------------------------------------------------------------------------
    // GAタグ設定
    // ------------------------------------------------------------------------
    // GAオブジェクト
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','_ga');
    // GAトラッカーを作成
    _ga('create', 'UA-1675084-1', 'auto');

    $.sendGA = function (actionType, label) {
        var action = 'none';
        if (actionType == 1) {
            action = 'click';
        } else if (actionType == 2) {
            action = 'edit';
        } else if (actionType == 3) {
            action = 'link';
        } else if (actionType == 4) {
            action = 'select';
        }
        if (lpNo) {
            //console.log(lpNo + '_' + label);
            _ga('send', 'event', 'listing', action, lpNo + '_' + label, 0, {'nonInteraction': 1});
        }
    }

    // 資格
    $('input[name="license[]"]').on('change', function(){
        $.sendGA(1, 'STEP1-license');
    });
    // 卒業年
    $('select[name="graduation_year"]').on('change', function(){
        $.sendGA(4, 'STEP1-graduation_year');
    });

    // 働き方
    $('input[name="req_emp_type[]"]').on('change', function(){
        $.sendGA(1, 'STEP2-req_emp_type');
    });
    // 転職時期
    $('select[name="req_date"]').on('change', function(){
        $.sendGA(4, 'STEP2-req_date');
    });
    $('input[name="req_date"]').on('change', function(){
        $.sendGA(4, 'STEP2-req_date');
    });

    // 郵便番号
    $('#zip').on('change', function(){
        $.sendGA(2, 'STEP3-zip');
    });
    // 都道府県
    $('#addr1').on('change', function(){
        $.sendGA(4, 'STEP3-addr1');
    });
    // 市区町村
    $('#addr2').on('change', function(){
        $.sendGA(4, 'STEP3-addr2');
    });
    // 番地建物
    $('#addr3').on('change', function(){
        $.sendGA(2, 'STEP3-addr3');
    });
    // 転居可否
    $('input[name="moving_flg"]').on('change', function(){
        $.sendGA(1, 'STEP3-moving');
    });
    // お名前（漢字）
    $('#name_kan').on('change', function(){
        $.sendGA(2, 'STEP4-name_kan');
    });
    // お名前（ふりがな）
    $('#name_cana').on('change', function(){
        $.sendGA(2, 'STEP4-name_cana');
    });
    // 生まれ年（プルダウン）
    $('#birth_year').on('change', function(){
        $.sendGA(4, 'STEP4-birth_year');
    });

    // 生まれ年
    $('#input_birth_year').on('change', function(){
        $.sendGA(2, 'STEP4-input_birth_year');
    });

    // 退職意向
    $('#retirement_intention').on('change', function(){
        $.sendGA(4, 'STEP5-retirement_intention');
    });
    $('input[name="retirement_intention"]').on('change', function(){
        $.sendGA(4, 'STEP5-retirement_intention');
    });
    // メールアドレス
    $('#mob_mail').on('change', function(){
        $.sendGA(2, 'STEP5-mob_mail');
    });
    // 携帯電話番号
    $('#mob_phone').on('change', function(){
        $.sendGA(2, 'STEP5-mob_phone');
    });

    // 利用規約
    $('#kiyaku, #form_kiyaku').on('click', function(){
        $.sendGA(3, 'kiyaku');
    });
    // 個人情報の取り扱いについて
    $('#kojin_joho').on('click', function(){
        $.sendGA(3, 'kojin_joho');
    });
    // 運営会社
    $('#company').on('click', function(){
        $.sendGA(3, 'company');
    });

    // 次へ、登録、入力チェックエラー
    $(document).on('click', 'a.bx-next', function(){
        var step = $('body').attr('class').toUpperCase();
        // 入力チェックエラー
        if (!$.validation_result) {
            $("div[id*='_errmsg']").each(function(idx, elem){
                if ($(this).css('display') != 'none' && $(this).html().length > 0) {
                    var id = $(this).attr('id').replace(/_errmsg\d*/, '');
                    // 都道府県、市区町村に関しては値をチェックして特定する
                    if (id == 'addr1or2') {
                        if ($('#addr1').val().length > 0 && $('#addr2').val().length == 0) {
                            id = 'addr2';
                        } else {
                            id = 'addr1';
                        }
                    }
                    $.sendGA(1, step + '_error-' + id);
                }
            });
        } else {
            if (step != 'STEP5') {
                // 次へ
                $.sendGA(1, 'STEP' + (parseInt(step.replace('STEP', '')) + 1) + '_OPEN');
            } else {
                // 登録
                $.sendGA(1, 'CV');
            }
        }
    });

    // 戻る
    $(document).on('click', 'a.bx-prev', function(){
        var step = $('body').attr('class').toUpperCase();
        $.sendGA(1, 'pre-' + step);
    });

    // 流入
    $.sendGA(1, $('body').attr('class').toUpperCase() + '_OPEN');

});
