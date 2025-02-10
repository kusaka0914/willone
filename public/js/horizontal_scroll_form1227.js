$(function(){
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
	$('a.bx-next').html('<span class="nextBtn">つぎへ</span>');
	$('a.bx-next').addClass('off')

	//Prevのリンク内を変更
	$('a.bx-prev').html('<span><img src="/woa/img/btn_back.png"></span>');

	$('a.bx-next a.bx-prev').wrapAll('<p class="btn-area"></p>')


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
        $('#license_errmsg').css("visibility" , "hidden");
        $('#addr1or2_errmsg0').text('');
        $('#addr1or2_errmsg0').css("visibility" , "hidden");
        $('#addr1or2_errmsg1').text('');
        $('#addr1or2_errmsg1').css("visibility" , "hidden");

        $('#name_kan_errmsg').text('');
        $('#name_kan_errmsg').css("visibility" , "hidden");
        $('#name_cana_errmsg').text('');
        $('#name_cana_errmsg').css("visibility" , "hidden");
        $('#birth_year_errmsg').text('');
        $('#birth_year_errmsg').css("visibility" , "hidden");
        $('#retirement_intention_errmsg').text('');
        $('#retirement_intention_errmsg').css("visibility" , "hidden");
        $('#mob_phone_errmsg').text('');
        $('#mob_phone_errmsg').css("visibility" , "hidden");

		if ( pagenum < 6 ) {
			// タブインデックスの更新
			$.setTabOrder(pagenum);

			// ページ表示画像の変更
			$('img#form_status')
				.attr('data-page-num', pagenum)
				.attr('src','/woa/img/' + pagenum + '.png' );

			//1ページ目の場合、Prevリンクを非表示にする。
			$('a.bx-prev').css('visibility', (pagenum == 1 ? 'hidden':'visible') );

			// [次へ]ボタンか[登録する]ボタンを切り替え
			$('a.bx-next').attr('data-page-num', pagenum);
			if ( pagenum < 5 ) {
				$('a.bx-next').html('<span class="nextBtn">つぎへ</span>');
			} else {
				$('a.bx-next').html('<span class="lastBtn"><small>利用規約に同意の上</small><br>理想の職場を探しに行く!</span>');
			}
			$('h2 span').hide();
			$('h3 span').hide();

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
