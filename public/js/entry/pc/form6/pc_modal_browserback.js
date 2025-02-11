$(document).ready(function () {
    var historyBackFlag = false;
    var beforeRes = false;
    history.pushState(null, null, null);
    /**
     * ブラウザバック時バナー表示用
     */
    $(window).on("popstate", function (event) {
        if ($('body').hasClass('step1')) {
            var state = event.originalEvent.state;
            // 値がない、または最初のページは「-1」の値が入っているので、その値かどうか確認
            if (!state || state != '' && state.match(/\-1/)) {
                // 一度modalを閉じた場合は離脱許可
                if (historyBackFlag) {
                    history.back();
                    return;
                }
                var res = dispModalBanner();

                // ブラウザバックをブロックした時点で popstate が再度走るため、前回の結果と比較
                if (res && !beforeRes) {
                    // 離脱ブロック
                    history.go(1);
                } else if (!res && !beforeRes) {
                    // modalが表示されているときは離脱許可
                    history.back();
                }
                beforeRes = res
            }
            return;
        }
        return;
    });

    /**
     * モーダルの表示
     */
    var dispModalBanner = function () {
        if ($('[data-modal-banner="browser_back"]').css("display") == 'none') {
            $('[data-modal-banner="browser_back"]').fadeIn(200);
            $.sendGA(1, 'exit_protection_open');
            exitProtectionOpenGa();
            return true;
        }
        return false;
    }

    /**
     * モーダルの非表示
     */
    var hideModalBanner = function () {
        if ($('[data-modal-banner="browser_back"]').css("display") == 'block') {
            $('[data-modal-banner="browser_back"]').fadeOut(200);
            $.sendGA(1, 'exit_protection_click');
            exitProtectionClickGa();
            historyBackFlag = true;
        }
        return false;
    }

    /**
     * モーダル閉じる
     */
    // window.addEventListener('popstate', browserBack)
    $('[data-modal-banner="browser_back"]').on('click', function (e) {
        hideModalBanner();
        e.stopPropagation();
    });
    $('[data-close-button="browser_back"]').on('click', function (e) {
        hideModalBanner();
        e.stopPropagation();
    });
});
