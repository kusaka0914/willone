$(function() {
    $('#pref').change(function() {
    	var val = $(this).val();

        $.ajax({
            url: '/woa/stateget',
            type: 'get',
            dataType: 'html',
            // フォーム要素の内容をハッシュ形式に変換
            data: { "pref" : val},
            timeout: 5000,
          })
          .done(function(data) {
              $('#state').empty();
              $('#state').append(data);
          });
    });
});
