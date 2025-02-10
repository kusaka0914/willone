;
$(function() {
    // ------------------------------------------------------------------------
    // 郵便番号住所検索の初期化
    // ------------------------------------------------------------------------
    $(this).searchCityInit("zip", "addr1", "addr2", "addr3");

    // ------------------------------------------------------------------------
    // 漢字の自動かな変換プラグインの初期化
    // ------------------------------------------------------------------------
    $(this).autoKana('#name_kan', '#name_cana', {
        katakana: false,
        toggle: false
    });

    // validate
    $('#name_kan').bind('keydown keyup keypress change', function() {
        $.checkVal('name_kan');
    });
    $('#name_cana').bind('keydown keyup keypress change', function() {
        $.checkVal('name_cana');
    });
    $('#birth_year').on('change', function() {
        $.checkVal('birth_year');
    });
    $('#zip').bind('keydown keyup keypress change', function() {
        $.checkVal('zip');
    });
    $('#addr1').on('change', function() {
        $.checkVal('addr1');
    });
    $('#addr2').on('change', function() {
        $.checkVal('addr2');
    });
    $('#addr3').bind('keydown keyup keypress change', function() {
        $.checkVal('addr3');
    });
    $('#mob_phone').bind('keydown keyup keypress change', function() {
        $.checkVal('mob_phone');
    });
    $('#mob_mail').bind('keyup change', function() {
        $.checkVal('mob_mail');
    });
    $('input[name^="license"]').on('change', function() {
        $.checkVal('license');
    });
    $('#graduation_year').on('change', function() {
        $.checkVal('graduation_year');
    });
    $('input[name^="req_emp_type"]').on('change', function() {
        $.checkVal('req_emp_type');
    });
    $('#req_date').on('change', function() {
        $.checkVal('req_date');
    });
    $('#retirement_intention').on('change', function() {
        $.checkVal('retirement_intention');
    });
    $('#agent_entry_categorys').on('change', function() {
        $.checkVal('agent_entry_categorys');
    });

    $('#agreement_flag').on('click', function() {
        swithBtnSubmitOnOff();
    });

    /**
     * validate処理
     */
    $.checkVal = function(id) {
        var result = $.checkValidate(id);
        if (result) {
            $("#" + id + "_errmsg").css({ display: 'block' }).text("※" + result);
        } else {
            $("#" + id + "_errmsg").css({ display: 'none' });
        }
    }

    function swithBtnSubmitOnOff() {
        // 「利用規約に同意する」チェックボックスによるsubmitボタン制御
        $('#btnSubmit').prop('disabled', !$('#agreement_flag').prop('checked'));
    }

    // 画面初期表示時
    swithBtnSubmitOnOff();
});