jQuery(document).ready(function($) {
  var wn = '';

  $('[data-modal]').click(function(event){
      event.preventDefault();
      wn = '.' + $(this).data('modal');
      var mW = $(wn).find('.modalBody').innerWidth() / 2;
      var mH = $(wn).find('.modalBody').innerHeight() / 2;
      //$(wn).find('.modalBody').css({'margin-left':-mW,'margin-top':-mH});
      $(wn).fadeIn(200);
      $('body').addClass('modal-on');
  });
  $('.close > *,.modalBK,.close').click(function(){
      $(wn).fadeOut(200);
      $('body').removeClass('modal-on');
  });

  //利用規約リンク等
  $("#rule").load('/woa/include/ct/_rule.html');
  $("#privacy").load('/woa/include/ct/_privacy-policy.html');
  $("#access").load('/woa/include/ct/_company.html');
});
