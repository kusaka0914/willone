;(function($){
  
  // アコーディオン
  var $accordionElem = $('[data-melon-accordion]');
  
  $accordionElem.on('click',function(e){
    
    var $elem = $(this);
    
    // もし<a>タグだったら
    if ( $elem.is('a')){
      e.preventDefault();
      location.hash = $(this).attr('href');
    }
    
    // ターゲットは、「data-melon-accordion」の値で指定している要素、なければ次の兄弟要素
    var target = $elem.attr('data-melon-accordion') ? $elem.attr('data-melon-accordion') : $elem.next();
    
    // もしアニメーション中なら中止
    if ( $elem.is(':animated')){ return;};
    
    $(target).slideToggle();
    $elem.toggleClass('open'); // アイコン切り替え用
    
    // ターゲットの高さの合計が、ウィンドウの高さよりも高かったら、その高さ分、上にスクロールする。
    var totalHeight = 0;
    $(target).each( function(){
      var heightSum = $(this).height();
      totalHeight = totalHeight + heightSum;
    });
    if ( totalHeight > $(window).height() ){
      $("html,body").animate({scrollTop: '-=' + totalHeight});
    }
    
    // もしテキストも変更したい場合に使用
    if ( $elem.is("[data-toggleText]")){
      var toggleTextElem = $elem;
    } else if ( $elem.find("[data-toggleText]") ){
      var toggleTextElem = $elem.find("[data-toggleText]");
    }
    if( toggleTextElem){
      var defaultText = toggleTextElem.html();
      var changeText = toggleTextElem.attr('data-toggleText') ? toggleTextElem.attr('data-toggleText') : '閉じる';
      toggleTextElem.html( changeText ).attr('data-toggleText', defaultText);
    }
  });
})(jQuery);