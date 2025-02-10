jQuery(document).ready(function($) {
    var pre_zip = $("#zip").val();

    setInterval(function(){
        var zip = $("#zip").val();
        if (zip == null) {
            zip = "";
        }
        if (zip != pre_zip) {
            if ((zip.indexOf("-") < 0 && zip.length == 7) || (zip.indexOf("-") >= 0 && zip.length == 8)) {
                $(this).changeCityByZipCode('zip','addr1','addr2','addr3');
            } else if ($('#zip_errormsg') && $('#zip_errflag')) {
                if (zip.length > 0) {
                    $('#zip_errormsg').text('存在しない郵便番号です').css({'display': 'block'});
                    $('#zip_errflag').val('1');
                } else if ($('#zip_errflag').val() == '1') {
                    $('#zip_errormsg').text('').css({'display': 'none'});
                    $('#zip_errflag').val('0');
                }
            }
        }
        pre_zip = zip;
    }, 100);
});