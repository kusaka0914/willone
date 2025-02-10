$(function() {

  $('#rank_request_form').on('change' , 'input[type="file"]' , function(e) {
        var fd = new FormData;
        var obj = $(this);
        fd.append("file" , e.target.files[0]);
        $.ajax({
            type: "POST",
            url: "/rankrequestcsv",
            data: fd,
            dataType    : "text",
            processData : false,
            contentType : false,
            headers:
            {
                'X-CSRF-Token': $('input[name="_token"]').val()
            }
            }).done(function(data){
                alert("CSVファイルのアップロードが完了しました");
                location.href="/woa";
            }).fail(function(j_data){
                alert("CSVファイルのアップロードに失敗しました。");
            });

    });
});
