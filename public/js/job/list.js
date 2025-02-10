/* 画面の指定した位置までスクロールしたらポップアップを表示 */
(function () {
    const documentH = jQuery(document).innerHeight();
    const now = new Date();
    // 現在年月日(月日左0埋め)
    const ymd = now.getFullYear() + (now.getMonth() + 1).toString().padStart(2, '0') + now.getDate().toString().padStart(2, '0');
    const popUpYMD = localStorage.getItem('memberRegistrationPopUpYMD');
    if(popUpYMD === ymd){
        return;
    }
    let isExecuted = false;
    window.addEventListener('scroll', function(){
        if(isExecuted){
            return;
        }
        // 画面高さ(documentH)の何分の一までスクロールしたか判定
        if((documentH / 4) < jQuery(window).scrollTop()){
            isExecuted = true;
            jQuery('.popup').css('display','flex');
            localStorage.setItem('memberRegistrationPopUpYMD', ymd);
        }
    });
})();

jQuery(document).on('click', '.popup', function(){
  jQuery(this).css('display','none');
});
