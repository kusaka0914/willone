/**
 * GAタグの送信処理
 */
$(function($) {
    var feedAbPatter = '' ;
    feedAbPatter = $('#feedAbPattern').val();

    $.sendGA = function (label) {
        if (feedAbPatter) {
            _ga('send', 'event', 'feedlp', 'click', feedAbPatter + '_' + label, 0, { 'nonInteraction': 1 });
        }
    }

    // 詳細
    $('.c-button').on('click', function () {
        $.sendGA('detail');
    });

    // おすすめ求人
    $('.nearOfficeLink').on('click', function () {
        $.sendGA('detail');
    });

    // もっと見る
    $('.c-button-more').on('click', function () {
        $.sendGA('more');
    });
});
