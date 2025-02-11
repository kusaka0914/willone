// モーダルウィンドウ
jQuery(document).ready(function ($) {
    var wn = "";

    $(document).on("click", "[data-modal]", function (event) {
        event.preventDefault();
        wn = '.' + $(this).data('modal');
        $(wn).fadeIn(200);
        $('body').addClass('modal-on');
    });
    $(".close > *,.modalBK,.close").click(function () {
      $(wn).fadeOut(200);
      $("body").removeClass("modal-on");
    });

    //利用規約リンク等
    $("#rule").load("/woa/include/ct/_rule.html");
    $("#privacy").load("/woa/include/ct/_privacy-policy.html");
    $("#access").load("/woa/include/ct/_company.html");
});

pageNo = 0;
$(function() {
        // bxSlider 初期化
    $('.bxslider').bxSlider({
        mode: 'horizontal',
        useCSS: false,
        infiniteLoop: false,
        hideControlOnEnd: true,
        touchEnabled: false,
        speed: 200,
        easing: 'easeInOutQuart',
        onSlidePrev: $.onSlidePrev,
        onSlideAfter: $.onSlideAfterHandler,
        onSliderLoad: function(currentIndex) {
            $('#dialog_content').css('opacity', '1'); // 初期表示でのレイアウト崩れを回避する
        }
    });

    $('.bx-controls').addClass('index1');

    //Nextのリンク内を[次のStepへ]の画像に変更
    $('a.bx-next').html('<span class="nextBtn">つぎへ</span>');
    $('a.bx-next').addClass('off');

    //Prevのリンク内を変更
    $('a.bx-prev').html('<span><img src="/woa/entry/img/btn_back.png"></span>');

    $('a.bx-next').wrap('<p class="btn-area"></p>');


    //初期状態では1ページ目の表示なので、Prevリンクを非表示にする。
    $('a.bx-prev').css('visibility', 'hidden');

    // ------------------------------------------------------------------------
    //1ページ目のクローンに対する処理
    // ------------------------------------------------------------------------
    // チェックボックスの削除
    $('li.bx-clone input[type=checkbox]').remove();
    // labelのfor属性を削除
    $('li.bx-clone label').removeAttr("for");
    // id="license_container"のdivからid属性を削除
    $('li.bx-clone div#license_container').removeAttr("id");


    // 資格
    $('input[name="license[]"]').on('change', function(){
        $.validate_item('license');
        if (typeof $.dispGradYear === 'function') {
            $.dispGradYear();
        }
    });
    // 卒業年
    $('#graduation_year').on('change', function(){
        $.validate_item('graduation_year');
    });

    // 働き方
    $('input[name="req_emp_type[]"]').on('change', function(){
        $.validate_item('req_emp_type');
    });

    // 郵便番号
    $('#zip').on('keydown keyup keypress change', function(){
        if ($('#zip').val().length == 7) {
            $('#addr_txt_area').hide();
            $('#addr_area').show();
            $('#zip2').addClass('on');
        }
    });
    $('#zip2,#addr_txt_area').on('click', function(){
        $.dispAddr();
    });
    // 都道府県
    $('#addr1').on('change', function(){
        $.validate_item('addr1');
    });
    // 市区町村
    $('#addr2').on('change', function(){
        $.validate_item('addr2');
    });

    // お名前（漢字）
    $('#name_kan').on('change', function(){
        $.validate_item('name_kan');
    });
    // お名前（ふりがな）
    $('#name_cana').on('change', function(){
        $.validate_item('name_cana');
    });
    // 生まれ年
    $('#birth_year').on('change', function(){
        $.validate_item('birth_year');
    });
    // メールアドレス
    $('#mob_mail').on('change keyup', function(){
        $.checkMail($(this).val(), true);
    });
    $('#mob_mail').on('change', function(){
        $.validate_item('mob_mail');
    });
    // 携帯電話番号
    $('#mob_phone').on('change', function(){
        $.validate_item('mob_phone');
    });
    // 転居可否
    $('input[name="moving_flg"]').on('change', function(){
        $.validate_item('moving_flg');
    });
});

// ページ番号を与えてそのページ内に制限する関数
$.setTabOrder = function(pagenum) {
    for ( var step =1; step <= 5; step ++ ) {
        var div = "div#Step" + step;
        var sel = div + " input," + div + " select";
        if ( pagenum == step )
            $(sel).removeAttr("disabled");
        else
            $(sel).attr("disabled", "disabled");
    }
};

// ------------------------------------------------------------------------
// blur(),focus()を実行
// ------------------------------------------------------------------------
$.setBlur = function () {
    $('#zip').blur();
    $('#addr3').blur();
    $('#name_kan').blur();
    $('#name_cana').blur();
    $('#mob_phone_inp').blur();
    $('#mob_mail_inp').blur();
};

// ------------------------------------------------------------------------
// スライドの移動が完了した直後に呼ばれるハンドラ
// ------------------------------------------------------------------------
$.onSlideAfterHandler = function($slideElement, oldIndex, newIndex) {
    var pagenum = newIndex + 1;

    pageNo = pagenum;

    // フォーカス外し
    $.setBlur();

    /* validation 解除*/
    $('#license_errmsg').text('');
    $('#license_errmsg').hide();
    $('#graduation_year_errmsg').text('');
    $('#graduation_year_errmsg').hide();

    if (pagenum == 5) {
        // 規約リンクの表示
        $(".bx-controls-direction").after(
          '<div id="form_rule"><p><a href="/woa/rule" rel="dialog nofollow" id="form_kiyaku" class="form_rule_link" data-modal="rule" data-transition="pop">利用規約</a></p></div>'
        );
        // 利用規約
        $('#form_kiyaku').on('click', function(){
          kiyakuGA4();
        });
    }

    if ( pagenum < 6 ) {
        // タブインデックスの更新
        $.setTabOrder(pagenum);

        // ページ表示画像の変更
        $('img#form_status')
            .attr('data-page-num', pagenum)
            .attr('src','/woa/entry/pc/form3007/img/' + pagenum + '.png' );

        //1ページ目の場合、Prevリンクを非表示にする。
        $('a.bx-prev').css('visibility', (pagenum == 1 ? 'hidden':'visible') );

        // [次へ]ボタンか[登録する]ボタンを切り替え
        $('a.bx-next').attr('data-page-num', pagenum);
        if ( pagenum < 5 ) {
           let remain = 5 - pagenum;
            $('a.bx-next').html('<span class="nextBtn">残り' + remain + 'STEP</span>');
            // 利用規約の非表示
            $('#form_rule').hide();
        } else {
            $('a.bx-next').html('<span class="lastBtn"><small>利用規約に同意の上</small><br>理想の職場を探しに行く!</span>');
            // 利用規約の表示
            $('#form_rule').show();
        }


        // コントローラー（.bx-controls）にインデックスに対応したクラスを追加
        var nextIndex = pagenum + 1;
        $('.bx-controls')
            .removeClass('index'+ newIndex)
            .removeClass('index'+ nextIndex)
            .addClass('index'+ pagenum);


        switch (pagenum) {
            case 1:
                $('body').removeClass('step2 step3 step4 step5').addClass('step1');
                break;
            case 2:
                $('body').removeClass('step1 step3 step4 step5').addClass('step2');
                break;
            case 3:
                $('body').removeClass('step1 step2 step4 step5').addClass('step3');
                break;
            case 4:
                $('a.bx-next').removeClass('newBtn03');
                $('body').removeClass('step1 step2 step3 step5').addClass('step4');
                $("#retirementIntention").hide();// #7337
                break;
            case 5:
                $('a.bx-next').addClass('newBtn03');
                $('body').removeClass('step1 step2 step3 step4').addClass('step5');
                $("#retirementIntention").show();// #7337
                break;
        }
    }
};

// 卒業年エリアの表示/非表示を切替え
$.dispGradYear = function() {
    var checked = 0;
    var student = 0;
    $('input[name^="license[]"]:checked').each(function(){
        var str = $(this).val();
        var student_values = ['44', '45', '46'];
        if (student_values.indexOf(str) >= 0) {
            student++;
        }
        checked++;
    });
    if (checked > 0) {
        $('#license_errmsg').text('');
        $('#license_errmsg').hide();
    }
    return true;
};

// 住所入力エリアの表示/非表示を切替え
$.dispAddr = function(forcedDisplay = false) {
    if (!$('#addr_area').is(':visible') || forcedDisplay) {
        // 表示
        $('#addr_txt_area').hide();
        $('#addr_area').show();
        $('#zip2').addClass('on');
    } else {
        // 非表示
        $('#addr_area').hide();
        $('#addr_txt_area').show();
        $('#zip2').removeClass('on');
    }
    return true;
};
