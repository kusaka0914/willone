/** *************************************************************
 *  横スクロール型マルチステップ登録フォーム用
 *  入力チェック関数
 *
 *  2014/12/8：PC_332, PC_344 で使用しているものを流用
 ***************************************************************/
jQuery(document).ready(function($) {
	// ----------------------------------------------------------------------------
	// 各ステップごとの入力項目定義
	//（ステップ数が変わったり、各ステップのチェック項目が変わったら要修正）
	// ----------------------------------------------------------------------------
	$.targetIds = [
    /* Step1 */ ["addr1","addr2","addr3"],
	/* Step2 */ 	["license"],
	/* Step3 */ 	["req_emp_type","req_date"],
	/* Step4 */	["name_kan","name_cana","birth_year"],
	/* Step5 */	["mob_phone","mob_mail","retirement_intention"]
	];
	// ----------------------------------------------------------------------------
	// 入力値のエラーメッセージ
	// ----------------------------------------------------------------------------
	$.validationMessages = {
			"name_kan"		: 'お名前の入力をお願いします',
			"name_cana"		: 'ふりがなの入力をお願いします',
			"name_cana_format"	:	'ふりがなは、ひらがなで入力をお願いします',
			"birth_year"		: '正しい生まれ年を入力してください',
			"addr"		: 'お住まいの選択または入力をお願いします',
			"addr1"		: '都道府県の選択をお願いします',
			"addr2"		: '市区町村の選択をお願いします',
			"addr3"		: '番地・建物の入力をお願いします',
			"mob_empty"			: '携帯電話番号の入力をお願いします',
			"mob_max_length"	: '携帯電話番号の桁数が多すぎます',
			"mob_min_length"		: '携帯電話番号の桁数が足りていません',
			"mob_format_pos"		: '携帯電話番号の-（ハイフン）、()（カッコ）の入力が正しくありません',
			"mob_format"			: '携帯電話番号に使用できるのは数字と-（ハイフン）、()（カッコ）だけです',
			"mob_mail"				: 'メールアドレスを正しく入力してください',
			"license"					: '保有資格をひとつ以上ご選択ください',
			"req_work_type"		: '就業形態をひとつ以上ご選択ください',
            "req_emp_type"      : '就業形態をご選択ください',
            "req_date"      : '転職時期をご選択ください',
			"introducer_name"		: '紹介者氏名の入力をお願いします',
			"retirement_intention"		: 'お仕事のご状況をご選択ください'
		};
	// ----------------------------------------------------------------------------
	 //  項目別コード(アクセスログの送信時に使用)
	// ----------------------------------------------------------------------------
	$.error_codes = {
			"name_kan":		1,		// お名前
			"name_cana":	2,		// ふりがな
			"addr1": 			3,		// 都道府県
			"addr2": 			4,		// 市区町村
			"addr3": 			5,		// 市区町村以下
			"mob_phone": 	6,		// 携帯電話番号
			"mob_mail":		7,		// メールアドレス
			"license":			8,		// 保有資格
			"req_emp_type": 9,	// 就業形態
			"birth_year": 	10,		// 生まれた年
			"retirement_intention": 	11,		// 退職意向
            "req_date": 12  // 転職時期
	};
    // ----------------------------------------------------------------------------
    //  氏名のNGワードパターンの作成
    // ----------------------------------------------------------------------------
    var lastNames = ["看護","かんご","カンゴ"];
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
	// (引数の viewPage には、"Step1"のような文字列が渡される想定)
	// ----------------------------------------------------------------------------
    $.checkValidate = function checkValidate(viewPage) {

    	// エラーの発生した箇所のキーとメッセージのマップを初期化（この関数の返り値）
    	var errors = {};

    	// 入力フォームのステップ番号（最初が 0）
        var stepindex = Number(viewPage.slice(-1)) - 1 ;

        // 入力チェック項目の配列をループ
        for ( var i = 0;  i < $.targetIds[stepindex].length; ++ i ) {

        	var input_key = $.targetIds[stepindex][i];

        	switch (input_key) {

        		case 'name_kan':  // 名前（漢字）：エラーコード=1
                    var value = $.trim($("#" + input_key).val());
                    if ( value == '' || value == 0) {
                        errors[input_key] = $.validationMessages[input_key];
                    } else {
                        // 空白削除
                        var str = value.replace(/\s+/g, "");
                        // エラー文言削除
                        str = str.replace(new RegExp(replacePattern, "g"), "");
                        if ( str == '' || str == 0) {
                            errors[input_key] = '正しいお名前をご入力ください';
                        }
                    }
                    break;

        		case 'addr1':  // 都道府県：エラーコード=3
        		case 'addr2':  // 都道府県：エラーコード=4
        		case 'addr3':  // 都道府県：エラーコード=5
        			if ( $("#" + input_key).length ) {
        				var value = $.trim($("#" + input_key).val());
        				if ( value == '' || value == 0) {
        					errors[input_key] = $.validationMessages[input_key];
        				}
        			}
        			break;

        		case 'birth_year': // 生まれた年：エラーコード=10
        			if ( $("#" + input_key).length ) {
        				var value = $.trim($("#" + input_key).val());
        				if ( value == '' || value == 0) {
            				errors[input_key] = $.validationMessages[input_key];
            			} else {
                            // 全角数字->半角数字
                            value = value.replace(/[０-９]/g, function (s) {
                                return String.fromCharCode(s.charCodeAt(0) - 65248);
                            });
                            // 半角数字以外の文字列は除去
                            value = value.replace(/[^0-9]+/g, "");

            				if (!parseInt(value) || 1937 > parseInt(value) || parseInt(value) > 1999) {
            					errors[input_key] = $.validationMessages[input_key];
            				} else {
                				// validateを通った値を設定
                				$("#" + input_key).val(value);
            				}
        				}
        			}
        			break;

        		case 'name_cana':  // 名前（ふりがな）：エラーコード=2
        			var name_cana = $("#name_cana").val();
        			if ($.trim(name_cana) == '') {
        				errors[input_key] = $.validationMessages[input_key];
        			} else {
        				var match = ($.trim(name_cana)).match(/^[ぁ-んーァ-ヴ　 ]+$/);
        				if (!match) {
        					errors[input_key] = $.validationMessages[input_key + "_format"];
        				}
        			}
        			break;

        		case 'mob_phone': // 携帯電話番号：エラーコード=6
        			var mob_phone_inp = document.getElementById("mob_phone_inp");
                    var mob_phone_err = $.validateMobPhone($.trim(mob_phone_inp.value));
                    if (mob_phone_err != '') {
                    	errors["mob_phone"] = mob_phone_err;
                    }
        			break;

        		case 'mob_mail':  // 携帯メールアドレス：エラーコード=7
        			var mob_mail = document.getElementById("mob_mail_inp").value;
                    if ($.trim(mob_mail) != '' && !mob_mail.match(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/)) {
                    	errors[input_key] = $.validationMessages[input_key];
                    }
        			break;

        		case 'license': //保有資格：エラーコード=8
        		case 'req_emp_type':  // 就業形態：エラーコード=9
                case 'req_date':  // 就業形態：エラーコード=12
                case 'retirement_intention': // 退職意向：エラーコード=11
        			if ( ! $("input[name^=" + input_key + "]").is(':checked') ) {
        				errors[input_key] = $.validationMessages[input_key];
                    }
        			break;
        		default:
        			break;
        	}
        }

        // エラーコードの配列を作成
        var error_cd = new Array();
        for ( var key in errors ) {
        	error_cd.push($.error_codes[key]);
        }

        // エラーメッセージとエラーコードを返却
        return { "errors": errors, "error_cd": error_cd };
    };

	// ----------------------------------------------------------------------------
    // 携帯電話番号のチェック
	// ----------------------------------------------------------------------------
    $.validateMobPhone = function (mob_phone){
    	if ($.trim(mob_phone) == '') {
    		return $.validationMessages["mob_empty"];
    	}
    	if (!isNaN(mob_phone) && mob_phone.length < 3) {
    		return $.validationMessages["mob_min_length"];
    	}
    	if (mob_phone.match(/[^0-9\(\)\-]/)) {
    		return $.validationMessages["mob_format"];
    	}
    	if (mob_phone.match(/\(.*\(|\).*\)/) || mob_phone.match(/\)$/) || mob_phone.match(/\-.*\-.*\-.*/) || mob_phone.match(/^\-|\-$/)) {
    		return $.validationMessages["mob_format_pos"];
    	}
    	if (!mob_phone.match(/^\([0-9]+\)[\-]?[0-9]+[\-]?[0-9]+$/) && !mob_phone.match(/^[0-9]+[\-]?\([0-9]+\)[\-]?[0-9]+$/)
    			&& !mob_phone.match(/^[0-9]+[\-]?[0-9]+[\-]?[0-9]+$/)) {
    		return $.validationMessages["mob_format_pos"];
    	}
    	var tmp = mob_phone.replace(/\(|\)|-/g, "");
    	if (tmp.match(/^050|^060|^070|^080|^090/)) {
    		var maxlen = 11;
    		var minlen = 11;
    	} else {
    		var maxlen = 10;
    		var minlen = 10;
    	}
    	if (maxlen < tmp.length) {
    		return $.validationMessages["mob_max_length"];
    	}
    	if (minlen > tmp.length) {
    		return $.validationMessages["mob_min_length"];
    	}

    	return "";
    }
});
