/** *************************************************************
 *  横スクロール型マルチステップ登録フォーム用
 *  入力チェック関数
 ***************************************************************/
jQuery(document).ready(function($) {
    // ----------------------------------------------------------------------------
    // 入力値のエラーメッセージ
    // ----------------------------------------------------------------------------
    $.validationMessages = {
        "license"				: '保有資格を選択してください',
        "graduation_year"		: '卒業年度を選択してください',
        "req_work_type"			: '希望職種を選択してください',
        "req_emp_type"			: '希望就業形態を選択してください',
        "cm_practice"			: '経験年数を選択してください',
        "req_date"				: '希望転職時期を選択してください',
        "name_kan"				: '氏名を入力してください',
        "name_kan_format"		: '正しい氏名を入力してください',
        "name_cana"				: 'ふりがなを入力してください',
        "name_cana_format"		: 'ふりがなをひらがなで入力してください',
        "birth_year"			: '生まれた年を選択してください',
        "addr1"					: '都道府県を選択してください',
        "addr2"					: '市区町村を選択してください',
        "addr3"					: '番地建物名を入力してください',
        "tel_empty"				: '電話番号を入力してください',
        "tel_max_length"		: '電話番号の桁数が多すぎます',
        "tel_min_length"		: '電話番号の桁数が足りていません',
        "tel_format"			: '電話番号を正しく入力してください',
        "mob_mail"				: 'メールアドレスを入力してください',
        "mob_mail_format"		: 'メールアドレスを正しく入力してください',
        "password"				: 'パスワード（半角英数8文字以上）を入力してください',
        "retirement_intention"	: 'ご転職意向を選択してください',
    };
    // ----------------------------------------------------------------------------
    //  氏名のNGワードパターンの作成
    // ----------------------------------------------------------------------------
    var lastNames = ["保育","ほいく","ホイク"];
    var firstNames = ["花子","はなこ","ハナコ"];
    var replacePatterns = [];
    for (var i = 0; i < lastNames.length; i++) {
        for (var j = 0; j < firstNames.length; j++) {
            replacePatterns.push(lastNames[i] + firstNames[j]);
        }
    }
    var replacePattern = replacePatterns.join("|");

    // ----------------------------------------------------------------------------
    // バリデーション実行
    // (引数の input_key には、idが渡される想定)
    // ----------------------------------------------------------------------------
    $.checkValidate = function (input_key) {
        var errors = "";

        switch (input_key) {
            case 'name_kan':  // 名前（漢字）
                var value = $.trim($("#" + input_key).val());
                if (value == '' || value == 0) {
                    errors = $.validationMessages[input_key];
                } else {
                    // 空白削除
                    var str = value.replace(/\s+/g, "");
                    // 全角英数字を半角に変換
                    str = str.replace(/[Ａ-Ｚａ-ｚ０-９]/g, function(s){
                        return String.fromCharCode(s.charCodeAt(0)-0xFEE0);
                    });
                    // エラー文言削除
                    str = str.replace(new RegExp(replacePattern, "g"), "");
                    if (str == '' || str == 0) {
                        errors = $.validationMessages[input_key + "_format"];
                    } else {
                        // 半角英数字削除
                        var str2 = str.replace(/\w/g, "");
                        if (str2 == '' || str2 == 0) {
                            // 半角英数字のみだったらエラー
                            errors = $.validationMessages[input_key + "_format"];
                        } else {
                            // 補完した文字列をセット
                            $("#" + input_key).val(str);
                        }
                    }
                }
                break;

            case 'name_cana':  // 名前（ふりがな）
                var name_cana = $.trim($("#name_cana").val());
                if (name_cana == '') {
                    errors = $.validationMessages[input_key];
                } else {
                    // 空白削除
                    name_cana = name_cana.replace(/\s+/g, "");
                    // 全角ひらがな以外削除
                    name_cana = name_cana.match(/^[ぁ-んー]+$/);
                    if (!name_cana) {
                        errors = $.validationMessages[input_key + "_format"];
                    } else {
                        // 補完した文字列をセット
                        $("#" + input_key).val(name_cana);
                    }
                }
                break;

            case 'addr1':       // 都道府県
            case 'addr2':       // 市区町村
            case 'addr3':       // 番地・建物
            case 'birth_year':  // 生まれた年
            case 'cm_practice': // 経験年数
            case 'req_date':    // 希望転職時期
            case 'retirement_intention':    // ご転職意向
                if ( $("#" + input_key).length ) {
                    var value = $.trim($("#" + input_key).val());
                    if (value == '' || value == 0) {
                        errors = $.validationMessages[input_key];
                    }
                }
                break;

            case 'tel': // 携帯電話番号
                var value = $.trim($("#tel").val());
                // 空白削除
                value = value.replace(/\s+/g, "");
                // 全角数字を半角に変換
                value = value.replace(/[０-９]/g, function(s){
                    return String.fromCharCode(s.charCodeAt(0)-0xFEE0);
                });
                // 半角数字以外削除
                value = value.replace(/[^0-9]/g, "");
                errors = $.validateMobPhone(value);
                // 補完した文字列をセット
                $("#" + input_key).val(value);
                break;

            case 'mob_mail':  // 携帯メールアドレス
                var value = $.trim($("#mob_mail").val());
                if (value == '' || value == 0) {
                    errors = $.validationMessages[input_key];
                } else {
                    if (!value.match(/^[\w\-]+[\w\.\-]*@[a-zA-Z]+[\w\-\.]*\.[a-zA-Z]{2,4}$/)) {
                        errors = $.validationMessages[input_key + "_format"];
                    }
                }
                break;

            case 'license':         //保有資格
            case 'req_emp_type':    // 就業形態
            case 'req_work_type':   // 希望職種
                if ( ! $("input[name^=" + input_key + "]").is(':checked') ) {
                    errors = $.validationMessages[input_key];
                }
                break;

            case 'graduation_year':
                // 卒業年 ※卒業年エリアが表示されている時のみ入力チェック
                if ($('#graduation_year_area').is(':visible')) {
                    var value = $('#graduation_year').val();
                    if ( value == '' ) {
                        errors = $.validationMessages[input_key];
                    }
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
                    str = str.replace(/[Ａ-Ｚａ-ｚ０-９]/g, function(s){
                        return String.fromCharCode(s.charCodeAt(0)-0xFEE0);
                    });
                    // 半角英数字以外削除
                    str = str.replace(/[^0-9a-zA-Z]/g, "");
                    if (str == "" || str.length < 8) {
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
    // 電話番号のチェック
    // ----------------------------------------------------------------------------
    $.validateMobPhone = function (tel){
        if ($.trim(tel) == '') {
            return $.validationMessages["tel_empty"];
        }
        if (!isNaN(tel) && tel.length < 3) {
            return $.validationMessages["tel_min_length"];
        }
        var tmp = tel.replace(/\(|\)|-/g, "");
        if (tmp.match(/^050|^060|^070|^080|^090/)) {
            var maxlen = 11;
            var minlen = 11;
        } else {
            var maxlen = 10;
            var minlen = 10;
        }
        if (maxlen < tmp.length) {
            return $.validationMessages["tel_max_length"];
        }
        if (minlen > tmp.length) {
            return $.validationMessages["tel_min_length"];
        }
        if (!tel.match(/^0{1}[1-9]{1}[0-9]{8,9}$/)) {
            // 0から始まる11or10桁の数値
            // 先頭「00」はNG
            return $.validationMessages["tel_format"];
        }

        return "";
    }
});
