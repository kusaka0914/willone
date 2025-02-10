/**
 * 利用規約リンクなどが、ポップアップ仕様
 **/
$(document).ready(function() {
    var wn = '';

    $('[data-modal]').click(function(event) {
        var androidVer = AndroidVersion();
        // android かつ バージョンが 2.4 以上の場合、処理実行
        if (androidVer == undefined || androidVer > 2.4) {
            event.preventDefault();
            wn = '.' + $(this).data('modal');
            var mW = $(wn).find('.modalBody').innerWidth() / 2;
            var mH = $(wn).find('.modalBody').innerHeight() / 2;
            $(wn).find('.modalBody').css({ 'margin-left': -mW, 'margin-top': -mH });
            $(wn).fadeIn(200);
            $('body').addClass('modal-on');
        }
    });
    $('.close > *,.modalBK,.close').click(function() {
        $(wn).fadeOut(200);
        $('body').removeClass('modal-on');
    });

});

// Androidのバージョン取得
function AndroidVersion() {
    var ua = navigator.userAgent;
    if (ua.indexOf("Android") > 0) {
        var androidversion = parseFloat(ua.slice(ua.indexOf("Android") + 8));
        return androidversion;
    }
}

$(function() {
    //利用規約リンク等
    $("#tos").load('/woa/include/ct/_rule.html');
    $("#pmark").load('/woa/include/ct/_privacy-policy.html');
    $("#access").load('/woa/include/ct/_company.html');
});

// 日付の自動更新
var dateUpdate = function(){
    var date = new Date();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var hour = date.getHours();

    var string = '';
    if (hour >= 8) {
        string = month + '月' + day + '日' + '更新';
    } else {
        day = date.getDate() - 1;
        if (day < 10) {
            day = '0' + day;
        }
        string = month + '月' + day + '日' + '更新';
    }
    $('.popup-LP_btn_label').text(string);
}

// ブラウザバック対応
var browserBack = function() {
    // History API が使えるブラウザかどうかをチェック
    if (window.history && window.history.pushState) {
        //. ブラウザ履歴にダミーを１つ追加する
        history.pushState( null, null, null );
        // 一度だけ、モーダル実行
        $(window).one( "popstate", function(event) {
            $('[data-modal="popupHistoryBack"]').trigger('click');
            return;
        });
    }
};

$(function() {
    // ページ読み込み後の処理実施
    $(window).on('load', function() {
        // 日付の自動更新
        dateUpdate();
        // ブラウザバック対応
        browserBack();
    });
});
