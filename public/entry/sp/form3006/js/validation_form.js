$.getYear = new Date().getFullYear();
$.minYear = $.getYear - 80;
$.maxYear = $.getYear - 18;
$.validationMessages = {
    'license'                    : {'id': 'license_errmsg',              'message': '保有資格をひとつ以上選択して下さい'},
    'graduation_year'            : {'id': 'graduation_year_errmsg',      'message': '学生の方以外は「不明」を選択してください'},
    'req_emp_type'               : {'id': 'req_emp_type_errmsg',         'message': '働き方を選択して下さい'},
    'zip'                        : {'id': 'zip_errmsg',                  'message': '郵便番号を正しく入力してください'},
    'addr1'                      : {'id': 'addr1or2_errmsg0',            'message': '都道府県を選択して下さい'},
    'addr2'                      : {'id': 'addr1or2_errmsg1',            'message': '市区町村を選択して下さい'},
    'name_kan'                   : {'id': 'name_kan_errmsg',             'message': 'お名前の入力をお願いします'},
    'name_cana'                  : {'id': 'name_cana_errmsg',            'message': 'ふりがなの入力をお願いします'},
    'name_cana_format'           : {'id': 'name_cana_errmsg',            'message': 'ふりがなは、ひらがなで入力してください'},
    'input_birth_year'           : {'id': 'input_birth_year_errmsg',     'message': '生まれ年を入力して下さい'},
    'input_birth_year_format'    : {'id': 'input_birth_year_errmsg',     'message': '生まれ年は4桁の数字で入力して下さい'},
    'input_birth_year_between'   : {'id': 'input_birth_year_errmsg',     'message': '生まれ年は'+ $.minYear + 'から' + $.maxYear + 'の数値を入力して下さい'},
    'mob_phone'                  : {'id': 'mob_phone_errmsg',            'message': '携帯電話番号を入力してください'},
    'mob_empty'                  : {'id': 'mob_phone_errmsg',            'message': '携帯電話番号を入力してください'},
    'mob_max_length'             : {'id': 'mob_phone_errmsg',            'message': '携帯電話番号の桁数が多すぎます'},
    'mob_min_length'             : {'id': 'mob_phone_errmsg',            'message': '携帯電話番号の桁数が足りていません'},
    'mob_format_pos'             : {'id': 'mob_phone_errmsg',            'message': '携帯電話番号の-（ハイフン）、()（カッコ）の入力が正しくありません'},
    'mob_format'                 : {'id': 'mob_phone_errmsg',            'message': '携帯電話番号を正しく入力してください'},
    'mob_mail'                   : {'id': 'mob_mail_errmsg',             'message': 'メールアドレスを入力してください'},
    'mob_mail_format'            : {'id': 'mob_mail_errmsg',             'message': 'メールアドレスを正しく入力してください'},
    'mob_mail_dns'               : {'id': 'mob_mail_errmsg',             'message': 'ご利用可能なメールアドレスを入力してください'},
    'moving_flg'                 : {'id': 'moving_flg_errmsg',           'message': '転居予定を選択して下さい'}
};
$.valid_mail_result = "";
$.valid_mail_flag = true;
$.validation_result = false;
/**
 * stepごとのvalidation
 * @params step integer
 */
$.validate_form = function (pagenum) {
    var result = {};
    // 各ステップで1項目でもエラーならばfalseを返す
    if (pagenum == 1) {
        result = $.validate_item('license');
        if (Object.keys(result.errors).length == 0) {
            result = $.validate_item('graduation_year');
        }
    } else if (pagenum == 2) {
        var result = $.validate_item('req_emp_type');
    } else if (pagenum == 3) {
        result = $.validate_item('zip');
        if (Object.keys(result.errors).length == 0) {
            result = $.validate_item('addr1');
            if (Object.keys(result.errors).length == 0) {
                result = $.validate_item('addr2');
                if (Object.keys(result.errors).length == 0) {
                    result = $.validate_item('moving_flg');
                }
            }
        }
    } else if (pagenum == 4) {
        result = $.validate_item('name_kan');
        if (Object.keys(result.errors).length == 0) {
            result = $.validate_item('name_cana');
            if (Object.keys(result.errors).length == 0) {
                result = $.validate_item('input_birth_year');
            }
        }
    } else if (pagenum == 5) {
        result = $.validate_item('mob_phone');
        if (Object.keys(result.errors).length == 0) {
            result = $.validate_item('mob_mail');
        }
    }
    return result;
};

$.errmsg_on = function (target) {
    var id = "#" + $.validationMessages[target].id;
    var errmsg = $.validationMessages[target].message;
    $(id).text(errmsg).show();

    // 資格のエラーメッセージ表示且つ「学生」ボタンが押されていない場合はエリアの高さを再設定する
    if (target === 'license' && $('#slide_student_btn').is(':visible')) {
        var step1height = $('#Step1').outerHeight(true);
        $('.bxslider > li').height(step1height);
    }

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

$.validate_name_required = function (input_id) {
    var result = true;
    var check_flg = false;
    $('input[name="' + input_id + '"]').each(function () {
        if ($(this).prop("checked")) {
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

$.validate_checked = function (input_id) {
    var result = true;
    var check_flg = false;
    $('input[name="' + input_id + '[]"]').each(function () {
        if ($(this).prop("checked")) {
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
    // エラーの発生した箇所のキーとメッセージのマップを初期化（この関数の返り値）
    var errors = {};

    switch (input_id) {
        case 'license':
            // 資格
            result = $.validate_checked(input_id);
            if (result == false) {
                errors[input_id] = $.validationMessages[input_id];
            }
            break;

        case 'graduation_year':
            // 卒業年 ※卒業年エリアが表示されている時のみ入力チェック
            if ($('#graduation_year_area').is(':visible')) {
                result = $.validate_required(input_id);
                if (result == false) {
                    errors[input_id] = $.validationMessages[input_id];
                }
            }
            break;

        case 'req_emp_type':
            // 働き方
            result = $.validate_checked(input_id);
            if (result == false) {
                errors[input_id] = $.validationMessages[input_id];
            }
            break;

        case 'zip':
            // 郵便番号
            if ($('#zip').val().length > 0 && !$('#zip').val().match(/^[0-9]{7,7}$/)) {
                $.errmsg_on(input_id);
                result = false;
            } else {
                $.errmsg_off(input_id);
            }
            if (result == false) {
                errors[input_id] = $.validationMessages[input_id];
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
            } else if ($('#addr2').val().length == 0) {
                $.errmsg_off('addr1');
                $.errmsg_on('addr2');
                result = false;
            } else {
                $.errmsg_off('addr1');
                $.errmsg_off('addr2');
            }
            if (result == false) {
                errors[input_id] = $.validationMessages[input_id];
            }
            break;

        case 'name_kan':
            // お名前（漢字）
            result = $.validate_required(input_id);
            if (result == false) {
                errors[input_id] = $.validationMessages[input_id];
            }
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
            if (result == false) {
                errors[input_id] = $.validationMessages[input_id];
            }
            break;

        case 'input_birth_year':
            // 生まれ年
            target = $.validateBirthYear($('#' + input_id).val());
            if (target.length > 0) {
                $.errmsg_on(target);
                result = false;
            } else {
                $.errmsg_off(input_id);
            }
            if (result == false) {
                errors[input_id] = $.validationMessages[input_id];
            }
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
            if (result == false) {
                errors[input_id] = $.validationMessages[input_id];
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
            if (result == false) {
                errors[input_id] = $.validationMessages[input_id];
            }
            break;

        case 'moving_flg':
            // 転居可否
            var prefList = $("#not_move").val().split(",");
            var addr1 = $("#addr1").val();
            if (prefList.indexOf(addr1) === -1) {
                result = $.validate_name_required(input_id);
            }
            if (result == false) {
                errors[input_id] = $.validationMessages[input_id];
            }
            break;

    }
    // エラーメッセージとエラーコードを返却
    return { "errors": errors };
};

// ----------------------------------------------------------------------------
// 携帯電話番号のチェック
// ----------------------------------------------------------------------------
$.validateMobPhone = function (mob_phone) {
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
// 生まれ年のチェック
// ----------------------------------------------------------------------------
$.validateBirthYear = function (birth_year) {
    if ($.trim(birth_year) == '') {
        return "input_birth_year";
    }
    if (!isNaN(birth_year) && birth_year.length !== 4) {
        return "input_birth_year_format";
    }
    if (!birth_year.match(/^[0-9]{4}$/)) {
        return "input_birth_year_format";
    }
    if ($.minYear > birth_year || birth_year > $.maxYear) {
        return "input_birth_year_between";
    }

    return "";
}

// ----------------------------------------------------------------------------
// メールアドレスのチェック
// ----------------------------------------------------------------------------
$.validateMobMail = function (value) {
    value = $.trim(value);
    var result = '';
    if (value == '') {
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
$.checkMail = function (mail, async) {
    if (mail) {
        var ret = true;
        var errors = {};
        $.valid_mail_flag = false;

        var api_res = $.ajax({
            url: "/woa/api/checkDns",
            type: "POST",
            async: async,
            data: {
                mail: mail
            },
            dataType: "json",
        }).done(function (response) {
            res = response.result;
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
                    errors["mob_mail"] = $.validationMessages["mob_mail"];
                }
            }
            // return errors;
        }).fail(function (response) {
            $.valid_mail_flag = true;
        });
        return { "errors": errors };
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
