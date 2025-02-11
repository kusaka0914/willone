// anchorLink
$(function(){
    $('[href*=#]').click(function(){
        var link = $(this).prop('href').split('#');
        var url = $(location).attr('href').split('#');
        if(link[0] == url[0]) {
            var speed = 500;
            var href= $(this).attr('href');
            var target = $(href == '#' || href == '' ? 'html' : this.hash);
            var position = target.offset().top;
            $('html, body').animate({scrollTop:position}, speed, 'swing');
            return false;
        } else {
            return;
        }
    });
});

// slideToggle
$(function(){
    $(".js-toggle-next").click(function(){
        $(this).next().slideToggle(300);
        $(this).toggleClass('is-toggle-click');
    });
});

// scrollTop
$(function() {
    var topBtn = $('#pagetop');
    topBtn.hide();
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            topBtn.fadeIn();
        } else {
            topBtn.fadeOut();
        }
    });
});

function municipalitiesChange(){

  const searchForm = document.getElementById('searchForm');
  const value = document.getElementById('municipalitiesSelect').value;

  if(!value){
      alert('市区町村を選択してください');
      return false;
  } else {
      searchForm.submit();
  }

}
