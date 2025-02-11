$(function(){
  $('.toggle-btn').on('click',function(){
      $(this).parent().next($('.toggle-target')).toggle();

      if($(this).hasClass('toggle-btn-top')){
          if ($(this).hasClass('toggle-inverted')){
              $(this).removeClass('toggle-inverted');
              $(this).attr('src', '/woa/img/jobchangeagent/arrow-down-tap.png');
          } else {
              $(this).addClass('toggle-inverted');
              $(this).attr('src', '/woa/img/jobchangeagent/arrow-up.png');
          }
      } else {
          if ($(this).hasClass('toggle-inverted')){
              $(this).removeClass('toggle-inverted');
              $(this).attr('src', '/woa/img/jobchangeagent/arrow-down.png');
          } else {
              $(this).addClass('toggle-inverted');
              $(this).attr('src', '/woa/img/jobchangeagent/arrow-up.png');
          }
      }
  })
})
