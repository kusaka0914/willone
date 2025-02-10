/**
 * GA4タグの送信処理
 */
$(function($) {
    var feedAbPatter = '' ;
    feedAbPatter = $('#feedAbPattern').val();

    $.sendGA4 = function (label) {
        if (feedAbPatter) {
            dataLayer.push({'event':'lp-event-push','lp-event-element':feedAbPatter + '_' + label});
        }
    }

    // 流入
    $.sendGA4('open');

    // 詳細
    $('.c-button').on('click', function () {
        $.sendGA4('detail');
    });

    // おすすめ求人
    $('.nearOfficeLink').on('click', function () {
        $.sendGA4('detail');
    });

    // もっと見る
    $('.c-button-more').on('click', function () {
        $.sendGA4('more');
    });
});
