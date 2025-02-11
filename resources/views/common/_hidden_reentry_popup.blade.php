@if(!empty(session()->get('reCompleteData')))
<form id="re-popup-form">
  <div class="popup reclamation reclamation-second style-mamiya">
    <div class="popup-content select-list-recent2">
        <div class="popup__close" style="position: absolute;top: 15px;right: 15px;z-index: 2;cursor: pointer;">
            <i class="fa fa-times" aria-hidden="true"></i>
        </div>
        <div class="formMuscle_formItem" style="padding:0 4px;">
            <div class="c-form-title-wrap">
                <span class="form-label form-label-any">任意</span>
                <p class="c-form-base-title" style="color: #707070;">連絡希望時間帯（複数選択可）</p>
            </div>
            <div class="reclamation-notation">
                ご状況に合わせて専任スタッフよりメールかお電話でご連絡させていただきます。ご都合のつきやすいお時間をお選びください。
            </div>
            <ul class="select-list-recent2 oneColumn" style=" padding-left: 0;">
            @foreach(config('ini.REENTRY_CONTACT_TIME') as $key => $one)
            <li>
                <input type="checkbox" name="reentry_contact_time[]" id="reentry_contact_time[{{ $key }}]" value="{{ $key }}">
                <label for="reentry_contact_time[{{ $key }}]">
                <i class="fa fa-check"></i>{{ $one }}</label>
            </li>
            @endforeach
            </ul>
        </div>
        <div style="width: 100%;">
            <div class="c-form-title-wrap">
                <span class="form-label form-label-any">任意</span>
                <p class="c-form-base-title" style="color: #707070;">お問い合わせ内容</p>
            </div>
            <textarea name="toiawase" class="c-form-base-txtbox"></textarea>
        </div>
        <div class="reclamation-notation" style="background: #fff;">
            <p class="reclamation-natation-title" style="color: #707070;">
            【個人情報の取扱いについて】
            </p>
            <ul>
            <li style="max-width: 100%;color: #707070;">
            ・本フォームからお客様が記入・登録された個人情報は、資料送付・電子メール送信・電話連絡などの目的で利用・保管します。
            </li>
            <li style="max-width: 100%;color: #707070;">
            ・<a href="/woa/privacy">プライバシーポリシー</a>に同意の上、下記ボタンを押してください。
            </li>
            </ul>
        </div>
        <div class="formMuscle_formItem" style="padding:0 4px;width: 100%;">
            <button type="button" class="formMuscle_submit" id="pop-send-btn" onclick="sendContactTime()" disabled>希望時間帯を送信</button>
        </div>
        <div class="formMuscle_formItem" style="padding:0 4px;width: 100%;">
            <button type="button" id="pop-close-btn" class="formMuscle_submit">閉じる</button>
        </div>
    </div>
</div>
</form>
<link rel="stylesheet" type="text/css" href="{{addQuery('/woa/css/common/reentry.css')}}">
<style>
.popup{
    height: 100vh;
    width: 100%;
    background: rgba(0,0,0,0.5);
    position: fixed;
    top:0;
    left: 0;
    display: flex;
    z-index: 200;
    justify-content: center;
    align-items: center;
}

.popup-content{
    background-color: #fff;
    position: relative;
    width: 50%;
    padding: 1rem;
    overflow-y: scroll;
    height: 70vh;
}
.style-mamiya .formMuscle_submit[disabled] {
    background-color: gray;
    box-shadow: 0 6px 0 rgb(161 150 139);
}
@media screen and (max-width:768px) {
    .popup-content{
        width: 80%;
    }
}
</style>
<script>
    function sendContactTime() {
		$.ajax( {
            url: '/woa/api/reRegistReContactTime',
            type: "GET",
            dataType: "json",
			data: jQuery(jQuery('#re-popup-form')).serialize(),
			complete: function( data ){ // 成功、失敗どっちでも処理する
                jQuery('.popup').css('display','none');
			}
		});
	};
    jQuery(document).on('click', '#pop-close-btn,.popup__close', function(){ // modal閉じる
        jQuery('.popup').css('display','none');
    });
    jQuery('[name="reentry_contact_time[]"], [name="toiawase"]').on('change blur', function(){ // 任意項目に入力されたらボタン活性
        if(jQuery('[name="reentry_contact_time[]"]:checked').length > 0 || jQuery('[name="toiawase"]').val().trim() !== ''){
            jQuery('#pop-send-btn').prop('disabled', false);
        }else{
            jQuery('#pop-send-btn').prop('disabled', true);
        }
    });
</script>
@endif
