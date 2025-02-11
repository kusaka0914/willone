/** *************************************************************
 *  登録フォーム用
 *  入力チェック関数
 ***************************************************************/
jQuery(document).ready(function($) {
    // ----------------------------------------------------------------------------
    // 入力値のエラーメッセージ
    // ----------------------------------------------------------------------------
    $.validationMessages = {
        "license": '保有資格を選択してください',
        "graduation_year": '卒業年度を選択してください',
        "req_emp_type": '希望雇用形態を選択してください',
        "req_date": '入職希望時期を選択してください',
        "name_kan": '氏名を入力してください',
        "name_kan_format": '正しい氏名を入力してください',
        "name_cana": 'ふりがなを入力してください',
        "name_cana_format": 'ふりがなをひらがなで入力してください',
        "birth_year": '生まれた年を選択してください',
        "zip": '郵便番号を正しく入力してください',
        "addr1": '都道府県を選択してください',
        "addr2": '市区町村を選択してください',
        "mob_empty": '携帯電話番号を入力してください',
        "mob_max_length": '携帯電話番号の桁数が多すぎます',
        "mob_min_length": '携帯電話番号の桁数が足りていません',
        "mob_format": '携帯電話番号を正しく入力してください',
        "mob_mail": 'メールアドレスを入力してください',
        "mob_mail_format": 'メールアドレスを正しく入力してください',
        "retirement_intention": '退職意向を選択してください',
        "entry_category_manual": '登録カテゴリを選択してください',
    };
    // ----------------------------------------------------------------------------
    //  氏名のNGワードパターンの作成
    // ----------------------------------------------------------------------------
    var lastNames = ["看護", "かんご", "カンゴ"];
    var firstNames = ["花子", "はなこ", "ハナコ"];
    var replacePatterns = [];
    for (var i = 0; i < lastNames.length; i++) {
        for (var j = 0; j < firstNames.length; j++) {
            replacePatterns.push(lastNames[i] + firstNames[j]);
        }
    }
    var replacePattern = replacePatterns.join("|");
    var tmp_error_msg = "";
    // ----------------------------------------------------------------------------
    // バリデーション実行
    // (引数の input_key には、idが渡される想定)
    // ----------------------------------------------------------------------------
    $.checkValidate = function(input_key) {
        var errors = "";

        switch (input_key) {
            case 'name_kan': // 名前（漢字）
                var value = $.trim($("#" + input_key).val());

                if (value == '' || value == 0) {
                    errors = $.validationMessages[input_key];
                }
                break;

            case 'name_cana': // 名前（ふりがな）
                var name_cana = $.trim($("#name_cana").val());
                if (name_cana == '') {
                    errors = $.validationMessages[input_key];
                } else {
                    // 空白削除
                    name_cana = name_cana.replace(/\s+/g, "");
                    // 全角ひらがな以外が含まれるかチェック
                    name_cana = name_cana.match(/^[ぁ-んー　 ]+$/);
                    if (!name_cana) {
                        errors = $.validationMessages[input_key + "_format"];
                    } else {
                        // 補完した文字列をセット
                        $("#" + input_key).val(name_cana);
                    }
                }
                break;

            case 'zip': // 郵便番号
                var value = $.trim($("#" + input_key).val());
                if (value.length > 0 && !value.match(/^[0-9]{7,7}$/)) {
                    errors = $.validationMessages[input_key];
                }
                break;

            case 'addr1': // 都道府県
            case 'addr2': // 市区町村
            case 'birth_year': // 生まれた年
                if ($("#" + input_key).length) {
                    var value = $.trim($("#" + input_key).val());
                    if (value == '' || value == 0) {
                        errors = $.validationMessages[input_key];
                    }
                }
                break;

            case 'req_date': // 希望転職時期
            case 'retirement_intention': // ご転職意向
            case 'license': //保有資格
            case 'req_emp_type': // 就業形態
            case 'entry_category_manual': // 登録カテゴリ
                var $this = $("#" + input_key);
                var $name = $("input[name^=" + input_key + "]");
                var value = $.trim($this.val());
                if ($this.is('select')) {
                    if ($this.length) {
                        if (value == '' || value == 0) {
                            errors = $.validationMessages[input_key];
                        }
                    }
                } else if ($name.is(':radio') || $name.is(':checkbox')) {
                    if (!$name.is(':checked')) {
                        errors = $.validationMessages[input_key];
                    }
                }
                break;

            case 'graduation_year': // 卒業年度
                if ($("#" + input_key).length) {
                    if ($('#license_44').prop('checked') || $('#license_45').prop('checked') || $('#license_46').prop('checked')) {
                        // 学生の場合にチェック
                        var value = $.trim($("#" + input_key).val());
                        if (value == '' || value == 0) {
                            errors = $.validationMessages[input_key];
                        }
                    }
                }
                break;

            case 'mob_phone': // 携帯電話番号
                var value = $.trim($("#mob_phone").val());
                // 全角数字->半角数字
                value = value.replace(/[０-９]/g, function(s) {
                    return String.fromCharCode(s.charCodeAt(0) - 65248);
                });
                // 半角数字以外の文字列は除去
                value = value.replace(/[^0-9]+/g, "");
                // validateを通った値を設定
                $("#mob_phone").val(value);

                errors = $.validateMobPhone(value);
                break;

            case 'mob_mail': // 携帯メールアドレス
                var value = $.trim($("#mob_mail").val());
                if (value == '') {
                    errors = $.validationMessages[input_key];
                } else if (!value.match(/^[\w\-]+[\w\.\-]*@[a-zA-Z]+[\w\-\.]*\.[a-zA-Z]{2,4}$/)) {
                    errors = $.validationMessages[input_key + "_format"];
                } else {
                    errors = $.checkMailDns(value);
                }
                break;

            case 'password': // パスワード
                var value = $.trim($("#" + input_key).val());
                if (value == '' || value == 0) {
                    errors = $.validationMessages[input_key];
                } else {
                    // 空白削除
                    var str = value.replace(/\s+/g, "");
                    // 全角英数字を半角に変換
                    str = str.replace(/[Ａ-Ｚａ-ｚ０-９]/g, function(s) {
                        return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
                    });
                    if (str.match(/[^0-9a-zA-Z]/g)) {
                        // 半角英数字以外がある場合
                        errors = $.validationMessages[input_key];
                    } else if (str == "" || str.length < 8) {
                        // 8文字未満の場合
                        errors = $.validationMessages[input_key];
                    } else {
                        // 補完した文字列をセット
                        $("#" + input_key).val(str);
                    }
                }
                break;

            default:
                break;
        }

        // エラーメッセージを返却
        return errors;
    };

    // ----------------------------------------------------------------------------
    // 携帯電話番号のチェック
    // ----------------------------------------------------------------------------
    $.validateMobPhone = function(mob_phone) {
        if (mob_phone == '') {
            return $.validationMessages["mob_empty"];
        }
        if (!isNaN(mob_phone) && mob_phone.length < 3) {
            return $.validationMessages["mob_min_length"];
        }
        if (mob_phone.match(/^050|^060|^070|^080|^090/)) {
            if(mob_phone.substr(3,1) === '0') {
                return $.validationMessages["mob_format"];
            };
            var maxlen = 11;
            var minlen = 11;
        } else {
            var maxlen = 10;
            var minlen = 10;
        }
        if (maxlen < mob_phone.length) {
            return $.validationMessages["mob_max_length"];
        }
        if (minlen > mob_phone.length) {
            return $.validationMessages["mob_min_length"];
        }
        if (!mob_phone.match(/^0{1}[0-9]{9,10}$/)) {
            // 0から始まる11or10桁の数値
            return $.validationMessages["mob_format"];
        }

        return "";
    }

    // ----------------------------------------------------------------------------
    // メールアドレスのドメインチェック
    // ----------------------------------------------------------------------------
    $.checkMailDns = function(mail) {
        var res = false;
        if (mail.length > 3) {
            $.ajax({
                type: "POST",
                url: "/woa/api/checkDns",
                async: false,
                data: {
                    mail: mail
                },
                dataType: "json",
                success: function(json) {
                    res = json.result;
                },
                error: function() {}
            });
        }
        if (res) res = "";  // trueの時は空文字に置換
        return res;
    }

});
