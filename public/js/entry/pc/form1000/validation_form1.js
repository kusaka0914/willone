$.validationMessages = {
    'license'              : {'id': 'license_errmsg',              'message': '保有資格をひとつ以上選択して下さい'},
    'graduation_year'      : {'id': 'graduation_year_errmsg',      'message': '学生の場合は卒業年を選択して下さい'},
    'req_emp_type'         : {'id': 'req_emp_type_errmsg',         'message': '働き方を選択して下さい'},
    'req_date'             : {'id': 'req_date_errmsg',             'message': '転職希望時期を選択して下さい'},
    'zip'                  : {'id': 'zip_errmsg',                  'message': '郵便番号を正しく入力してください'},
    'addr1'                : {'id': 'addr1or2_errmsg0',            'message': '都道府県を選択して下さい'},
    'addr2'                : {'id': 'addr1or2_errmsg1',            'message': '市区町村を選択して下さい'},
    'name_kan'             : {'id': 'name_kan_errmsg',             'message': 'お名前の入力をお願いします'},
    'name_cana'            : {'id': 'name_cana_errmsg',            'message': 'ふりがなの入力をお願いします'},
    'name_cana_format'     : {'id': 'name_cana_errmsg',            'message': 'ふりがなは、ひらがなで入力してください'},
    'birth_year'           : {'id': 'birth_year_errmsg',           'message': '生まれ年を選択して下さい'},
    'retirement_intention' : {'id': 'retirement_intention_errmsg', 'message': '退職意向を選択して下さい'},
    'mob_phone'            : {'id': 'mob_phone_errmsg',            'message': '携帯電話番号を入力してください'},
    'mob_empty'            : {'id': 'mob_phone_errmsg',            'message': '携帯電話番号を入力してください'},
    'mob_max_length'       : {'id': 'mob_phone_errmsg',            'message': '携帯電話番号の桁数が多すぎます'},
    'mob_min_length'       : {'id': 'mob_phone_errmsg',            'message': '携帯電話番号の桁数が足りていません'},
    'mob_format_pos'       : {'id': 'mob_phone_errmsg',            'message': '携帯電話番号の-（ハイフン）、()（カッコ）の入力が正しくありません'},
    'mob_format'           : {'id': 'mob_phone_errmsg',            'message': '携帯電話番号を正しく入力してください'},
    'mob_mail'             : {'id': 'mob_mail_errmsg',             'message': 'メールアドレスを入力してください'},
    'mob_mail_format'      : {'id': 'mob_mail_errmsg',             'message': 'メールアドレスを正しく入力してください'},
    'mob_mail_dns'         : {'id': 'mob_mail_errmsg',             'message': 'ご利用可能なメールアドレスを入力してください'}
};
$.valid_mail_result = "";
$.valid_mail_flag = true;
$.validation_result = false;
/**
 * stepごとのvalidation
 * @params step integer
 */
$.validate_form = function (pagenum) {
    var result = true;
    // 各ステップで1項目でもエラーならばfalseを返す
    if (pagenum == 1) {
        result = $.validate_item('license') && result;
        result = $.validate_item('graduation_year') && result;
    } else if (pagenum == 2) {
        result = $.validate_item('req_emp_type') && result;
        result = $.validate_item('req_date') && result;
    } else if (pagenum == 3) {
        result = $.validate_item('zip') && result;
        result = $.validate_item('addr1') && result;
        if (!result) {  // エラー時は強制的に住所エリアを表示
            $.dispAddr(true);
        }
    } else if (pagenum == 4) {
        result = $.validate_item('name_kan') && result;
        result = $.validate_item('name_cana') && result;
        result = $.validate_item('birth_year') && result;
    } else if (pagenum == 5) {
        result = $.validate_item('retirement_intention') && result;
        result = $.validate_item('mob_phone') && result;
        result = $.validate_item('mob_mail') && result;
    }
    return result;
};

$.errmsg_on = function (target) {
    var id = "#" + $.validationMessages[target].id;
    var errmsg = $.validationMessages[target].message;
    $(id).text(errmsg).show();
    return true;
};

$.errmsg_off = function (target) {
    var id = "#" + $.validationMessages[target].id;
    $(id).text("").hide();
    return true;
};

$.validate_required = function (input_id) {
    var result = true;
    if ($('#' + input_id).val().length == 0) {
        $.errmsg_on(input_id);
        result = false;
    } else {
        $.errmsg_off(input_id);
    }
    return result;
};

$.validate_checked = function (input_id) {
    var result = true;
    var check_flg = false;
    $('input[name="' + input_id + '[]"]').each(function(){
        if( $(this).prop("checked") ){
            check_flg = true;
            return false;
        }
    });
    if (!check_flg) {
        $.errmsg_on(input_id);
        result = false;
    } else {
        $.errmsg_off(input_id);
    }
    return result;
};

$.validate_item = function (input_id) {
    var result = true;
    switch (input_id) {
        case 'license':
            // 資格
            result = $.validate_checked(input_id);
            break;

        case 'graduation_year':
            // 卒業年 ※卒業年エリアが表示されている時のみ入力チェック
            if ($('#graduation_year_area').is(':visible')) {
                result = $.validate_required(input_id);
            }
            break;

        case 'req_emp_type':
            // 働き方
            result = $.validate_checked(input_id);
            break;

        case 'req_date':
            // 転職時期
            result = $.validate_required(input_id);
            break;

        case 'zip':
            // 郵便番号
            if ($('#zip').val().length > 0 && !$('#zip').val().match(/^[0-9]{7,7}$/)) {
                $.errmsg_on(input_id);
                result = false;
            } else {
                $.errmsg_off(input_id);
            }
            break;

        case 'addr1':
            // 都道府県
        case 'addr2':
            // 市区町村
            if ($('#addr1').val().length == 0) {
                $.errmsg_on('addr1');
                $.errmsg_off('addr2');
                result = false;
            } else if ($('#addr1').val().length > 0 && $('#addr2').val().length == 0) {
                $.errmsg_off('addr1');
                $.errmsg_on('addr2');
                result = false;
            } else {
                $.errmsg_off('addr1');
                $.errmsg_off('addr2');
            }
            break;

        case 'name_kan':
            // お名前（漢字）
            result = $.validate_required(input_id);
            break;

        case 'name_cana':
            // お名前（ふりがな）
            var val = $('#' + input_id).val();
            if (val.length == 0) {
                $.errmsg_on(input_id);
                result = false;
            } else if (!$.validateKana(val)) {
                $.errmsg_on(input_id + '_format');
                result = false;
            } else {
                $.errmsg_off(input_id);
            }
            break;

        case 'birth_year':
            // 生まれ年
        case 'retirement_intention':
            // 退職意向
            result = $.validate_required(input_id);
            break;

        case 'mob_mail':
            // メールアドレス
            target = $.validateMobMail($('#' + input_id).val());
            if (target.length > 0) {
                $.errmsg_on(target);
                result = false;
            } else {
                $.errmsg_off(input_id);
            }
            break;

        case 'mob_phone':
            // 携帯電話番号
            target = $.validateMobPhone($('#' + input_id).val());
            if ($.trim(target).length > 0) {
                $.errmsg_on(target);
                result = false;
            } else {
                $.errmsg_off(input_id);
            }
            break;
    }
    return result;
};

// ----------------------------------------------------------------------------
// 携帯電話番号のチェック
// ----------------------------------------------------------------------------
$.validateMobPhone = function (mob_phone){
    if ($.trim(mob_phone) == '') {
        return "mob_empty";
    }
    if (!isNaN(mob_phone) && mob_phone.length < 3) {
        return "mob_min_length";
    }
    if (mob_phone.match(/[^0-9\(\)\-]/)) {
        return "mob_format";
    }
    if (mob_phone.match(/\(.*\(|\).*\)/) || mob_phone.match(/\)$/) || mob_phone.match(/\-.*\-.*\-.*/) || mob_phone.match(/^\-|\-$/)) {
        return "mob_format_pos";
    }
    if (!mob_phone.match(/^\([0-9]+\)[\-]?[0-9]+[\-]?[0-9]+$/) && !mob_phone.match(/^[0-9]+[\-]?\([0-9]+\)[\-]?[0-9]+$/)
            && !mob_phone.match(/^[0-9]+[\-]?[0-9]+[\-]?[0-9]+$/)) {
        return "mob_format_pos";
    }
    var tmp = mob_phone.replace(/\(|\)|-/g, "");
    if (tmp.match(/^050|^060|^070|^080|^090/)) {
        if(tmp.substr(3,1) === '0') {
            return "mob_format";
        };
        var maxlen = 11;
        var minlen = 11;
    } else {
        var maxlen = 10;
        var minlen = 10;
    }
    if (maxlen < tmp.length) {
        return "mob_max_length";
    }
    if (minlen > tmp.length) {
        return "mob_min_length";
    }
    if (!tmp.match(/^0{1}[0-9]{9,10}$/)) {
        // 0から始まる11or10桁の数値
        return "mob_format";
    }

    return "";
}

// ----------------------------------------------------------------------------
// メールアドレスのチェック
// ----------------------------------------------------------------------------
$.validateMobMail = function (value){
    value = $.trim(value);
    var result = '';
    if (value == '' || value == 0) {
        result = "";
    } else if (!value.match(/^[\w\-]+[\w\.\-]*@[a-zA-Z]+[\w\-\.]*\.[a-zA-Z]{2,4}$/)) {
        result = "mob_mail_format";
    } else if (!$.valid_mail_flag) {
        result = "mob_mail_dns";
    }
    return result;
}

// ----------------------------------------------------------------------------
// メールアドレスのドメインチェック
// ----------------------------------------------------------------------------
$.checkMail = function (mail, async){
    if (mail) {
        var ret = true;
        $.valid_mail_flag = false;

        $.ajax({
            type: "POST",
            url: "/woa/api/checkDns",
            async: async,
            //data: "mail=" + $.fn.UrlEncodeMail(mail),     // GET
            data: {
              mail : mail
            },
            dataType: "json",
            success: function(json){
                res = json.result;
                if (!async) {
                    ret = res;
                    $.valid_mail_flag = res;
                } else {
                    // MEMO:非同期時に処理が必要であればここで実行
                    if (res == true) {
                        // 正常
                        $.valid_mail_flag = true;
                        $.errmsg_off("mob_mail_dns");
                    } else {
                        // 異常
                        $.valid_mail_flag = false;
                        $.errmsg_on("mob_mail_dns");
                    }
                }
                return res;
            },
            error: function(){
                $.valid_mail_flag = true;
            }
        });
        return ret;
    }
    return true;
}

$.validateKana = function (value) {
    if (value == "") {
        return false;
    }
    var match = ($.trim(value)).match(/^[ぁ-んー　 ]+$/);
    return match;
}
