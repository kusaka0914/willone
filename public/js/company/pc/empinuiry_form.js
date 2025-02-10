//採用担当問合せ画面 制御JS
$( document ).ready(function( $ ) {

   // 郵便番号検索の初期化
    if (typeof($('#zip').val()) != 'undefined' && typeof($('#addr1').val()) != 'undefined' && typeof($('#addr2').val()) != 'undefined' && typeof($('#addr3').val()) != 'undefined') {
        $(document).searchCityInit("zip", "addr1", "addr2", "addr3");
    }
   // 郵便番号検索の初期化 事業所メルマガ用
    if (typeof($('#zip').val()) != 'undefined' && typeof($('#addr1').val()) != 'undefined'&& typeof($('#addr2').val()) == 'undefined' && typeof($('#addr3').val()) == 'undefined') {
        $(document).searchCityInit("zip", "addr1", "", "");
    }

    $(window).load(function(){
        $('input:checkbox:checked').parent().addClass('checked');
        $('input:radio:checked').parent().addClass('checked');
        $('input:not(:checked)').parent().removeClass('checked');

        $('input').on('change',function () {
            $('input:checkbox:checked').parent().addClass('checked');
            $('input:radio:checked').parent().addClass('checked');
            $('input:not(:checked)').parent().removeClass('checked');
        });
        // 採用担当問合せ画面 活性非活性制御
        control_regist_btn_company();
    });
});

    // 二重押下防止
    $("form").submit(function() { 
      var self = this;
      $(":submit", self).prop("disabled", true);
      setTimeout(function() {
        $(":submit", self).prop("disabled", false);
      }, 10000);
    });

// その他入力 活性非活性制御
function control_regist_btn_company () {
    var regist_chk = true;
    var testarea_chk = true;
    jQuery(".req_call_time").each(function() {
        if ( jQuery(this).attr( 'id' ) == 'tel_time_id_6') {

            if ( jQuery(this).prop( 'checked' ) ) {
                testarea_chk = false;
            }
        }
    });
    jQuery("#tel_time_note").attr( "disabled" , testarea_chk );
}

