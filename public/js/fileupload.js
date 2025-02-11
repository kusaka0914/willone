$(function(){

	$('#koushiImageFile').change(function(){
		if (!this.files.length) {
            return;
        }

        var file = $(this).prop('files')[0];
        var fr = new FileReader();
        $('#koushi_image').empty();
        fr.onload = function() {
        	$('#koushi_image').append('<img width="200" src="' + fr.result + '">')
        }
        fr.readAsDataURL(file);

	});
});

document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.querySelector('[name="file"]');

    // 要素が存在するか確認
    if (fileInput) {
        fileInput.addEventListener('change', function () {
            if (fileInput.files.length > 0) {
                var fileSize = fileInput.files[0].size; // ファイルサイズ（バイト）

                // ファイルサイズが2MBを超える場合
                if (fileSize > 2 * 1024 * 1024) {
                    var totalSizeKB = fileSize / 1024;
                    totalSizeKB = (Math.floor(totalSizeKB * 100)) / 100;
                    alert("画像ファイルサイズが大き過ぎます。\nファイルサイズは2000KB以内にしてください。\nアップ画像ファイルサイズ: " + totalSizeKB + "KB");

                    // ファイル選択をクリア
                    fileInput.value = '';
                }
            }
        });
    }
});
