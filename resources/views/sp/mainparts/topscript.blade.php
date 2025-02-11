<script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="/woa/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="/woa/js/jq.bxslider_for_horizscrl_form9527.js"></script>
    <script type="text/javascript" src="/woa/js/horizontal_scroll_form1227.js"></script>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
    <script type="text/javascript" src="/woa/js/jquery.autoKana.js"></script>
    <script>
$( document ).ready(function( $ ) {
    $(window).load(function(){
        $('input').on('change',function () {
            $('input:checkbox:checked').parent().addClass('checked');
            $('input:radio:checked').parent().addClass('checked');
            $('input:not(:checked)').parent().removeClass('checked');
        });

        $('#license_2').prop('checked', 'checked');
        $('#license_2').parent('div').addClass('checked');
    });

    //　「求人を検索するエリア」のイベント　　JINZAI-15042
    $('.p-license-select-item').on('click', function () {
      // ボタンの装飾を変更
      $('.p-license-select-item').each(function () {
        if ($(this).hasClass('active')) {
          $(this).removeClass('active');
        }
      })
      $(this).addClass('active');
      $(".c-variable-title").text($(this).text());
      const name = $(this).attr('data-license');
      // hrefの記述を変更
      areaLinkReplace(name);
    })
    // hrefの記述を変更する関数
    function areaLinkReplace(name) {
      $('.c-area .c-area-link').each(function () {
        const originalURL = $(this).attr('href');
        const result = originalURL.search("area/");
        if (result > -1) {
          const modifiedURL = originalURL.replace('area/', `job/${name}/`);
          $(this).attr('href', modifiedURL);
        } else {
          const modifiedURL = originalURL.replace(/job\/.*?\//, `job/${name}/`);
          $(this).attr('href', modifiedURL);
        }
      })
    }
    // デフォルト hrefの記述を柔整師に変更
    areaLinkReplace('judoseifukushi');
    //　「求人を検索するエリア」のイベント　　JINZAI-15042　　ここまで

});
</script>
<script>
$(function() {
    $.fn.autoKana('#name_kan', '#name_cana', {
        katakana : false  //true：カタカナ、false：ひらがな（デフォルト）
    });
});
</script>
    <script src="/woa/js/common.js"></script>
    <script src="/woa/js/slick.min.js"></script>
    <script src="/woa/js/calendar.js"></script>
	<script src="/woa/js/jquery.matchHeight.js"></script>
	<script src="/woa/js/system.js"></script>

	<script>
	$(function(){
		$('.matchHeight').matchHeight();
	});
	</script>
    <script type="text/javascript">
    $('.c-job').slick();
    </script>
	<script type="text/javascript">
		function changeMapImage(imgPath) {
		document.getElementById('map').src = imgPath;
		}
	</script>
