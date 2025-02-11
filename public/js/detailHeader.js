$(function () {
  $(window).scroll(function () {

    if ($(this).scrollTop() > 110) {
      $('.p-job-detail-nav').addClass('is-fixed');
    } else {
      $('.p-job-detail-nav').removeClass('is-fixed');
    }
  });
});
