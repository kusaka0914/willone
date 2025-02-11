/**
 * JINZAISYSTEM-6797
 *
 *  横スクロール型登録フォーム共通処理
 */
pageNo = 0;
$(function() {
	// ------------------------------------------------------------------------
	// テンプレート番号を取得
	// ------------------------------------------------------------------------
	$.template_id = $(':hidden[name="t"]').val();

	// ------------------------------------------------------------------------
	// GAタグ設定
	// ------------------------------------------------------------------------
	// GAオブジェクト
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	$.sendGA = function (actionType, label) {
		var action = 'none';
		if (actionType == 1) {
			action = 'click';
		} else if (actionType == 2) {
			action = 'edit';
		}
		// LP番号をLabel値に追加
		var lp = $.template_id + '_';
		ga('send', 'event', 'listing', action, lp + label, 0, {'nonInteraction': 1});
	}
	// 郵便番号
	$('#zip').on('change', function(){
		$.sendGA(2, 'STEP1-zip');
	});
	$('#zip2').on('click', function(){
		$.sendGA(1, 'STEP1-zip2');
	});
	// 都道府県
	$('#addr1').on('change', function(){
		$.sendGA(2, 'STEP1-addr1');
	});
	// 市区町村
	$('#addr2').on('change', function(){
		$.sendGA(2, 'STEP1-addr2');
	});
	// 番地建物
	$('#addr3').on('change', function(){
		$.sendGA(2, 'STEP1-addr3');
	});
	// 資格
	$('input[name="license[]"]').on('change', function(){
		$.sendGA(1, 'STEP2-license');
	});
	// 働き方
	$('input[name="req_emp_type[]"]').on('change', function(){
		$.sendGA(1, 'STEP3-req_emp_type');
	});
	// 転職時期
	$('input[name="req_date"]').on('change', function(){
		$.sendGA(2, 'STEP3-req_date');
	});
	// お名前（漢字）
	$('#name_kan').on('change', function(){
		$.sendGA(2, 'STEP4-name_kan');
	});
	// お名前（ふりがな）
	$('#name_cana').on('change', function(){
		$.sendGA(2, 'STEP4-name_cana');
	});
	// 生まれ年
	$('#birth_year').on('change', function(){
		$.sendGA(2, 'STEP4-birth_year');
	});
	// 携帯電話番号
	$('#mob_phone_inp').on('change', function(){
		$.sendGA(2, 'STEP5-mob_phone');
	});
	// メールアドレス
	$('#mob_mail_inp').on('change', function(){
		$.sendGA(2, 'STEP5-mob_mail');
	});
	// 退職意向
	$('input[name="retirement_intention"]').on('change', function(){
		$.sendGA(2, 'STEP5-retirement_intention');
	});
	// ページ表示
	$.onSlideView = function(formId) {
		if (formId < 6) {
			// 次へGA
			$.sendGA(1, 'STEP' + formId + '_OPEN');
		} else {
			// CV GA
			$.sendGA(1, 'CV');
		}
	};
	// 次へ
	$.onSlideNextError = function(formId) {
		// バリデーションエラー
		$.sendGA(1, formId);
	};
	// 戻る
	$.onSlidePrev = function($slideElement, oldIndex, newIndex) {
		// 戻る
		$.sendGA(1, 'pre-STEP' + (oldIndex + 1));
	};

	// ------------------------------------------------------------------------
	// 登録を開始するボタン画像をタップされたら、表紙のdivをフェードアウト
	// ------------------------------------------------------------------------
	$("*.startBtn img").click(function(){
		//フェードアウト処理
		$('#_div_1').css({'height': 'auto','overflow': 'visible'});
		$("#_div_2").fadeOut("slow");

		// スタート画像に data-value属性があるとき、その値に対応する資格をチェック
		if ( $(this).attr("data-value") )
			$('input#license_' + $(this).attr("data-value") ).prop("checked", true);
	});

	// ------------------------------------------------------------------------
	// 入力要素の値をクリア
	// ------------------------------------------------------------------------
	$("input[name^=license]").removeAttr("checked");
	$("input[name^=req_emp_type]").removeAttr("checked");
	$("#zip").val("");
	$("#addr2").val("");
	$("#addr3").val("");
	$("input[name^=req_date]").removeAttr("checked");
	$("#name_kan").val("");
	$("#name_cana").val("");
	$("#birth_year").val("");
	$("#mob_phone_inp").val("");
	$("#mob_mail_inp").val("");

	// ------------------------------------------------------------------------
	// 入力要素のエラー状態をクリアする関数定義
	// ------------------------------------------------------------------------
	$.clearErrors = function (formId) {
		$(".error_message", "#" + formId ).text("").css({display: 'none'});
		$('#addr1or2_error_msgs, .error_message').css({display: 'none'});
		$(".err").removeClass('err');

		var style = $("#addr1").attr("data-prev-border");
		if (style)
			$("#addr1").css( {border: style} );

		style = $("#addr2").attr("data-prev-border");
		if (style)
			$("#addr2").css( {border: style} );
	};

	// ------------------------------------------------------------------------
	// 各ページごとのバリデーション
	// ------------------------------------------------------------------------
	$.isValid = function (formId) {
		// バリデーションの実行
		var result = $.checkValidate(formId);

		// バリデーションの結果をUIに反映
		for(key in result.errors){

			//エラーメッセージの反映
			$("#" + key + "_errmsg").text(result.errors[key]).css({display: 'block'});
			$("#" + key).addClass('err');

			$('h2 span.errIcon02').hide();
			$('h2 span.errIcon01').css({display: 'inline'});

			// addr1とaddr2の場合の処理
			if ( key.indexOf("addr1") == 0 || key.indexOf("addr2") == 0 ) {
				$('#addr1or2_error_msgs').css({display: 'block'});
				if (key.indexOf("addr1") == 0) {
					$("#addr1or2_errmsg0").text(result.errors[key]).css({display: 'block'});
				}
				else {
					if ( result.errors['addr1'] ) {
						$("#addr1or2_errmsg1").text(result.errors[key]).css({display: 'block'});
					} else {
						$("#addr1or2_errmsg0").text(result.errors[key]).css({display: 'block'});
						$('h3 span.errIcon02').hide();
					}
				}
			} else {
				$('h3 span.errIcon02').hide();
				$('h3 span.errIcon01').css({display: 'inline'});
			}
		}

		// バリデーション結果をアクセスログに送信
		$.sendLog(
				$(':hidden[name="t"]').val(),
				formId.slice(-1),
				(Object.keys(result.errors).length == 0 ? 1:0),
				result.error_cd
		);

		var no = formId.slice(-1);
		// error GA
		if (Object.keys(result.errors).length > 0) {
			var keys = Object.keys(result.errors);
			$.each(keys, function(index, val){
				$.sendGA(2, 'error-STEP' + no + '-' + val);
			});
			// バリデーションエラーGA
			$.onSlideNextError('STEP' + no);
		} else {
			// ページ表示GA
			$.onSlideView(Number(no) + 1);
		}

		// バリデーションエラーが無ければtrue, あればfalseを返す。
		return( Object.keys(result.errors).length == 0 );
	};

	// ------------------------------------------------------------------------
	// エントリーアクセスログ送信
	//  	t: フォーム番号（例：PC_332)
	//		step: ステップ番号(1,2,3 ...)
	//		check: バリデーション結果 (1:成功、0:失敗)
	//		error_cd: エラーの発生した箇所のコード配列
	// ------------------------------------------------------------------------
	$.sendLog = function (t, step, check, error_cd) {
		$.ajax( { url: "/?njb", type: "GET", async: true,
			data: {
				act: "entryaccesslog",
				step: step,
				check: check,
				t: t,
				error: error_cd.join("_")
			},
			success: function( data ){
				//console.log("setEntryAccessLog");
			}
		});
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

		if ( pagenum < 6 ) {
			// タブインデックスの更新
			$.setTabOrder(pagenum);

			// ページ表示画像の変更
			$('img#form_status')
				.attr('data-page-num', pagenum)
				.attr('src','/woa/img/sp/form/form9527/status/' + pagenum + '.png' );

			//1ページ目の場合、Prevリンクを非表示にする。
			$('a.bx-prev').css('visibility', (pagenum == 1 ? 'hidden':'visible') );

			// [次へ]ボタンか[登録する]ボタンを切り替え
			$('a.bx-next').attr('data-page-num', pagenum);
			if ( pagenum < 5 ) {
				$('a.bx-next').html('<span class="next">つぎへ</span>');
			} else {
				$('a.bx-next').html('<img src="/woa/img/sp/form/form9483/btn.gif" alt="利用規約に同意の上求人を探しにいく！">');
			}
			$('h2 span').hide();
			$('h3 span').hide();

			// コントローラー（.bx-controls）にインデックスに対応したクラスを追加
			var nextIndex = pagenum + 1;
			$('.bx-controls')
				.removeClass('index'+ newIndex)
				.removeClass('index'+ nextIndex)
				.addClass('index'+ pagenum);

			// 手の位置を調整
			var isNext = $.handsSetting(pageNo);
			// 次へボタンのON/OFF調整
			$.setNextBtn(isNext);

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

	// ------------------------------------------------------------------------
	// 手カーソルの位置を調整
	// ------------------------------------------------------------------------
	$.handsSetting = function(pagenum) {
		var isNext = false;

		// 表示
		$('.hands').show();

		// バリデーションの実行
		var result = $.checkValidate('Step' + pagenum);

		// 表示位置の設定
		switch (pagenum) {
		case 1:
			if (Object.keys(result.errors).length == 0) {
				// 次へ
				$('.hands').animate({'bottom': '-8%'}, 0);
				isNext = true;
			} else if (!$('#zip2').hasClass('on')) {
				// アコーディオンが閉じている
				// 郵便番号
				$('.hands').animate({'bottom': '44%'}, 0);
			} else {
				// アコーディオンが開いている
				if (!('addr1' in result.errors) && ('addr2' in result.errors) && ('addr3' in result.errors)) {
					// 都道府県
					$('.hands').animate({'bottom': '19%'}, 0);
				} else if ('addr1' in result.errors) {
					// 都道府県
					$('.hands').animate({'bottom': '19%'}, 0);
				} else if ('addr2' in result.errors) {
					// 市区町村
					$('.hands').animate({'bottom': '27%'}, 0);
				} else if ('addr3' in result.errors) {
					// 番地・建物
					$('.hands').animate({'bottom': '6%'}, 0);
				} else {
					// 1番上の項目
					$('.hands').animate({'bottom': '44%'}, 0);
				}
			}
			break;
		case 2:
			if (Object.keys(result.errors).length == 0) {
				// 次へ
				$('.hands').animate({'bottom': '-8%'}, 0);
				isNext = true;
			} else if ('license' in result.errors) {
				// 保有資格
				$('.hands').animate({'bottom': '28%'}, 0);
			} else {
				// 1番上の項目
				$('.hands').animate({'bottom': '28%'}, 0);
			}
			break;
		case 3:
			if (Object.keys(result.errors).length == 0) {
				// 次へ
				$('.hands').animate({'bottom': '-8%'}, 0);
				isNext = true;
			} else if ('req_date' in result.errors) {
				// 転職時期
				$('.hands').animate({'bottom': '52%'}, 0);
			} else if ('req_emp_type' in result.errors) {
				// 働き方
				$('.hands').animate({'bottom': '18%'}, 0);
			} else {
				// 1番上の項目
				$('.hands').animate({'bottom': '52%'}, 0);
			}
			break;
		case 4:
			if (Object.keys(result.errors).length == 0) {
				// 次へ
				$('.hands').animate({'bottom': '-8%'}, 0);
				isNext = true;
			} else if ('name_kan' in result.errors) {
				// 氏名
				$('.hands').animate({'bottom': '64%'}, 0);
			} else if ('name_cana' in result.errors) {
				// かな
				$('.hands').animate({'bottom': '48%'}, 0);
			} else if ('birth_year' in result.errors) {
				// 生まれ年
				$('.hands').animate({'bottom': '35%'}, 0);
			} else {
				// 1番上の項目
				$('.hands').animate({'bottom': '64%'}, 0);
			}
			break;
		case 5:
			if (Object.keys(result.errors).length == 0) {
				// 次へ
				$('.hands').animate({'bottom': '-8%'}, 0);
				isNext = true;
			} else if ('mob_phone' in result.errors) {
				// 電話番号
				$('.hands').animate({'bottom': '75%'}, 0);
			} else if ('retirement_intention' in result.errors) {
				// 退職意向
				$('.hands').animate({'bottom': '63%'}, 0);
			} else if ('mob_mail' in result.errors) {
				// メアド
				$('.hands').animate({'bottom': '10%'}, 0);
			} else {
				// 1番上の項目
				$('.hands').animate({'bottom': '75%'}, 0);
			}
			break;
		default:
			// 非表示
			$('.hands').hide();
			break;
		}

		return isNext;
	};

	// ------------------------------------------------------------------------
	// 手カーソルの位置を調整（アコーディオンの開閉イベント時）
	// ------------------------------------------------------------------------
	$.handsSettingAccordion = function(pagenum) {
		// 表示位置の設定
		switch (pagenum) {
		case 1:
			// 表示
			$('.hands').show();
			// バリデーションの実行
			var result = $.checkValidate('Step' + pagenum);
			if (Object.keys(result.errors).length == 0) {
				// 次へ
				$('.hands').animate({'bottom': '-8%'}, 0);
			} else if ($('#zip2').hasClass('on')) {
				// 開く　->　閉じる
				// 郵便番号
				$('.hands').animate({'bottom': '44%'}, 0);
			} else {
				// 閉じる　->　開く
				// 都道府県
				$('.hands').animate({'bottom': '29%'}, 0);
			}
			break;
		default:
			// 非表示
			$('.hands').hide();
			break;
		}
	};

	// ------------------------------------------------------------------------
	// 手カーソルの位置を調整
	// ------------------------------------------------------------------------
	$.setNextBtn = function(isNext) {
		$('.arrow_step5').hide();
		if (isNext) {
			// ON
			$('.btn-area > a').removeClass('off');
			if (pageNo == 5) {
				$('.arrow_step5').show();
				// 手カーソルを非表示
				$('.hands').hide();
			}
		} else {
			// OFF
			$('.btn-area > a').addClass('off');
			$('.arrow_step5').hide();
		}
	};


	$('div.partial_form').css({visibility: 'visible'});

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
		onSlideAfter: $.onSlideAfterHandler
	});

	$('.bx-controls').addClass('index1');

	//Nextのリンク内を[次のStepへ]の画像に変更
	$('a.bx-next').html('<span>つぎへ</span>');
	$('a.bx-next').addClass('off')

	//Prevのリンク内を変更
	$('a.bx-prev').html('<span><img src="img/btn_back.png"></span>');

	$('a.bx-next').wrap('<p class="btn-area"></p>')


	//初期状態では1ページ目の表示なので、Prevリンクを非表示にする。
	$('a.bx-prev').css('visibility', 'hidden');

	//
	$('.lastupdate').after('<div class="pMark"><p><a href="http://privacymark.jp/" target="_blank" onclick="$.sendGA(1, \'privacymark\');"><img src="/woa/img/pmark/100x100.gif" alt="プライバシーマーク" class="mar_rm"></a><b>安心してご登録いただくために</b><br>ナース専科 転職を運営する(株)エス・エム・エスはプライバシーマークを取得しております。</p></div>');


	// ------------------------------------------------------------------------
	//1ページ目のクローンに対する処理
	// ------------------------------------------------------------------------
	// チェックボックスの削除
	$('li.bx-clone input[type=checkbox]').remove();
	// labelのfor属性を削除
	$('li.bx-clone label').removeAttr("for");
	// id="license_container"のdivからid属性を削除
	$('li.bx-clone div#license_container').removeAttr("id");

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

	// 初期設定
	$.setTabOrder(1);

	// GAトラッカーを作成
	ga('create', 'UA-6903379-1', 'auto');
	// 流入数
	$.onSlideView(1);
	pageNo = 1;


	// ------------------------------------------------------------------------
	// 各入力イベント処理
	// ------------------------------------------------------------------------
	$('input[name^=license]').on('click', function(){
		// 手の位置を調整
		var isNext = $.handsSetting(pageNo);
		// 次へボタンのON/OFF調整
		$.setNextBtn(isNext);
	});

	$('input[name="req_date"]').on('change', function(){
		// 手の位置を調整
		var isNext = $.handsSetting(pageNo);
		// 次へボタンのON/OFF調整
		$.setNextBtn(isNext);

		if ($(this).prop('checked')) {
			$('#req_date_errmsg').hide();
		}
	});

	$('input[name^=req_emp_type]').on('change', function(){
		// 手の位置を調整
		var isNext = $.handsSetting(pageNo);
		// 次へボタンのON/OFF調整
		$.setNextBtn(isNext);

		if ($(this).prop('checked')) {
			$('#req_emp_type_errmsg').hide();
		}
	});

	$('#zip').bind('keydown keyup keypress change input', function() {
		var length = $(this).val().length;
		// 郵便番号が7ケタの場合のみ、枠線・背景の色を変える
		if (length == 7) {
		  $(this).addClass('on');
			if ($('#addr1').val() != 0) {
				if ($('#addr2').val() != 0) {
					if ($('#addr3').val() != 0) {
						$('#addr1or2_error_msgs, #addr3_errmsg').hide();
					}
				}
			}
			// 手の位置を調整
			var isNext = $.handsSetting(pageNo);
			// 次へボタンのON/OFF調整
			$.setNextBtn(isNext);
		}
	});
	$('#addr1').on('change', function(){
		// 手の位置を調整
		var isNext = $.handsSetting(pageNo);
		// 次へボタンのON/OFF調整
		$.setNextBtn(isNext);
	});
	$('#addr2').on('change', function(){
		$('#addr1or2_error_msgs').hide();
		// 手の位置を調整
		var isNext = $.handsSetting(pageNo);
		// 次へボタンのON/OFF調整
		$.setNextBtn(isNext);
	});
	$('#addr3').on('keydown keyup keypress change', function() {
		// 手の位置を調整
		var isNext = $.handsSetting(pageNo);
		// 次へボタンのON/OFF調整
		$.setNextBtn(isNext);

		var length = $(this).val().length;
		if (length > 0) {
			$('#addr3_errmsg').hide();
		}
	});

	$('#name_kan').on('keydown keyup keypress change input', function() {
		// 手の位置を調整
		var isNext = $.handsSetting(pageNo);
		// 次へボタンのON/OFF調整
		$.setNextBtn(isNext);

		var length = $(this).val().length;
		if (length > 0) {
			$(this).attr('class','on');

			if ($('#name_cana').val().length > 0) {
				$('#name_kan_errmsg, #name_cana_errmsg').hide();
			}
		}
		$('#name_kan').blur(function(){
			$('.formItem').show();
		});
	});
	$('#name_cana').on('keydown keyup keypress change input', function() {
		// 手の位置を調整
		var isNext = $.handsSetting(pageNo);
		// 次へボタンのON/OFF調整
		$.setNextBtn(isNext);

		var length = $(this).val().length;
		if (length > 0) {
			$('#name_cana_errmsg').hide();
		}
	});
	$('#birth_year').bind('keydown keyup keypress change input', function() {
		// 手の位置を調整
		var isNext = $.handsSetting(pageNo);
		// 次へボタンのON/OFF調整
		$.setNextBtn(isNext);

		var length = $(this).val().length;
		if (length == 4) {
		  $('.selectWrap.birth').addClass('on');
		}
	});
	$('input[name="retirement_intention"]').on('change', function() {
		// 手の位置を調整
		var isNext = $.handsSetting(pageNo);
		// 次へボタンのON/OFF調整
		$.setNextBtn(isNext);

		if ($(this).prop('checked')) {
			$(this).attr('class','on');
			$('#retirement_intention_errmsg').hide();

			if (isNext) {
				var step5Btn = ($('#footer_image').offset() && $('#footer_image').offset().top) || 0;
				$('html, body').animate({scrollTop: step5Btn}, 'slow');
			}
		}
	});
	$('#mob_phone_inp').on('change input', function() {
		// 手の位置を調整
		var isNext = $.handsSetting(pageNo);
		// 次へボタンのON/OFF調整
		$.setNextBtn(isNext);

		var length = $(this).val().length;
		if (length == 10 || length == 11) {
			$(this).attr('class','on');
			$('#mob_phone_errmsg').hide();

			if (isNext) {
				var step5Btn = ($('#footer_image').offset() && $('#footer_image').offset().top) || 0;
				$('html, body').animate({scrollTop: step5Btn}, 'slow');
			}
		}
	});
	$('#mob_mail_inp').on('change input', function() {
		// 手の位置を調整
		var isNext = $.handsSetting(pageNo);
		// 次へボタンのON/OFF調整
		$.setNextBtn(isNext);
	});

	//Step1 アコーディオン
	$('#zip2').on("click" , function(){
		// 手の位置を調整
		$.handsSettingAccordion(pageNo);

		if($(this).hasClass('on')){
			$(this).removeClass('on');
			$('p.addTxt').css('display', 'block');
			$('.acoArea').slideUp(300);
		}else{
			$(this).addClass('on');
			$('p.addTxt').css('display', 'none');
			$('.acoArea').slideDown(300);
		}
	});

});


$(function() {
	var init = function() {
		$('a.bx-next').on('touchstart touchend', touchEventHandler2);
	};

	$('a.bx-next').addClass('newBtn01');
	var touchEventHandler2 = function(e) {
		if (e.type === 'touchstart') {
			$('a.newBtn01').css('position', 'relative');
			$('a.newBtn01').css('top', '2px');
		} else {
			$('a.newBtn01').css('position', 'relative');
			$('a.newBtn01').css('top', '0px');
		}
	};

	$(init);
});


/**
 * 郵便番号・電話番号の「あと○桁」の表示処理、他
 */
$(function(){
	var countMax = 7;
	var countMaxTel = 11;
	$('input#zip').bind('focus focusin keydown keyup keypress change',function(){
		var thisValueLength = $(this).val().length;
		var countDown = (countMax)-(thisValueLength);

		if(countDown < 0){
				$('.zip-count').css({color:'#ff0000',fontWeight:'bold',display:'inline'});
				$('.zip-count').html(countDown + '桁');
		} else {
			if(countDown == 0){
				 $('.zip-count').css({display:'none'});
				 $('#addr1').css({color:'#333'});
				 $('#addr2').css({color:'#333'});
			}else {
				$('.zip-count').css({color:'#555555',fontWeight:'normal',display:'inline'});
				$('.zip-count').html('ハイフンなし あと' + countDown + '桁');
			}
		}
	});


	$('#name_kan')
	.focusin(function(e) {
		$('.name_message').css('display', 'inline');
		$('.name_message').html('スペースなし');
	})
	.focusout(function(e) {
		$('.name_message').css('display', 'none');
	});

	$('#name_cana')
	.focusin(function(e) {
		$('.name_message2').css('display', 'inline');
		$('.name_message2').html('スペースなし');
	})
	.focusout(function(e) {
		$('.name_message2').css('display', 'none');
	});


	$('input#mob_phone_inp').bind('focus focusin keydown keyup keypress change',function(){
		var thisValueLength = $(this).val().length;
		if(thisValueLength>=3){
			if($(this).val().match(/^090|^080|^070|^050|^060/gi)){
				countMaxTel = 11;
			}
			else{
				countMaxTel = 10;
			}
		}
		else{
			$('.tel-count').css('display', 'inline');
			$('.tel-count').html('ハイフンなし');
			return;
		}

		var countDown = (countMaxTel)-(thisValueLength);

		if(countDown < 0){
			$('.tel-count').css({color:'#ff0000',fontWeight:'bold',display:'inline'});
			$('.tel-count').html( countDown + '桁');
		} else {
			if(countDown == 0){
				$('.tel-count').css('display', 'none');
			}else {
				$('.tel-count').css({color:'#555555',fontWeight:'normal',display:'inline'});
				$('.tel-count').html('ハイフンなし あと' + countDown + '桁');
			}
		}
	});
	$(window).load(function(){
		$('.zip-count').html('ハイフンなし あと' + countMax + '桁');
		$('.tel-count').html('ハイフンなし');
	});


});

function zentohan(obj){
	if(typeof(obj.value)!="string")return false;
		var han= '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@-.,:';
		var zen= '１２３４５６７８９０ａｂｃｄｅｆｇｈｉｊｋｌｍｎｏｐｑｒｓｔｕｖｗｘｙｚＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺ＠－．，：';
		var word = obj.value; for(i=0;i<zen.length;i++){ var regex = new RegExp(zen[i],"gm");
		word = (word).replace(/,/g, "");
		word = word.replace(regex,han[i]);
	}
	obj.value = word;
}

function selectColor() {
	// 現在選択されてる項目によって色設定
	if ($('select').find('option:selected').hasClass('not-select')) {
		$('select').css({'color': '#999'});
	}

	// 項目が変更された時、条件によって色変更
	$('select').on('focus focusin keydown keyup keypress change selected checked', function() {
		$(this).css({'color': '#333'});
	});
}
selectColor();


// #7337
$("#retirementIntention").hide();


$(function() {
	/**
	 * 郵便番号から住所を取得した際に、callback
	 * ソフトウェアキーボードの非表示対応
	 * njb_common_jquery -> jquery.SearchCityからcallbackされる
	 */
	$.fn.callbackZip2 = function(){
		var pagenum = $('a.bx-next').attr('data-page-num');
		if (typeof pagenum === 'undefined') {
			return;
		}

		if (pagenum == 3) {
			// フォーカス外し
			$.setBlur();
		}
	};
});
