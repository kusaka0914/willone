
jQuery(function($) {

	// .melon-modal の中に必要なタグを追加
	$.each( $('.melon-modal'), function(){
		if ( $(this).find('.melon-modal_body').length == false ){
			$(this).wrapInner('<div class="melon-modal_scroll"></div>')
				.append('<p class="melon-modal_closeBtn">×close</p>')
				.wrapInner('<div class="melon-modal_body"></div>')
				.wrapInner('<div class="melon-modal_inn"></div>');
		}
	});

	// クリックでモーダル開始
	$('[data-melon-modal]').on('click' , function(e){
		var androidVer = AndroidVersion();

		if (androidVer == undefined || androidVer > 2.4){ // android かつ バージョンが 2.4 以上の場合、処理実行
			var wn = $(this).data('melon-modal');
			var mH = $(window).height() - 120;

			e.preventDefault();
			$(".melon-modal_scroll").css("max-height", mH + "px");
			$(wn).fadeIn().css("display", "table");
			$('body').addClass('melon-modal-on');
		}
	});

	$('.melon-modal').on('click', function(e){
		$(this).fadeOut();
//		$(this).toggleClass('melon-modal-open melon-modal-close');
		$('body').removeClass('melon-modal-on');
	});

	// ウィンドウの中身をクリックしても、閉じないようにする
	$('.melon-modal').on('click', '.melon-modal_scroll', function (event) {
		event.stopPropagation();
	});

});

