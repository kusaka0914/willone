 // ■ボタンの2度押し禁止制御処理
 // formタイプと aタグタイプでメソッド分け

//++++++++  1.登録ボタン(form型)の非活性化
// → <form>タグに onsubmit="disableButton(); を追加して呼び出す。
function disableButton(){

  jQuery(function($){
    //下記タイプのインプット / クラス を非活性にする。
    $("input[type='image']").prop("disabled",true);
    $("input[type='submit']").prop("disabled",true);
    $(".btn_once").prop("disabled",true);
  });
}


//+++++++++++ 2-1. Aタグリンクの非活性
// <a>タグにonclick="disableHref(); を追加する。 class に"btn_once"を付与して呼び出す。


var cnt = 0;
// Aタグの2度押しを制御  (対象 ボタンのクラス: btn_once )
function disableHref(){
  
	jQuery(function($){
		if(cnt == 1 ){ 
		// 2度目の押下時にhrefを無効にして、キャンセルする
			$(".btn_once").removeAttr("href");
		}
		cnt++;
	});
}

//+++++++++++ 2-2. Aタグリンクの非活性 (onclickに google analytics処理と併用する場合）
// <a>タグにonclick="disableHref(); を追加する。 class に"btn_once"を付与して呼び出す。
  
// Aタグの2度押しを制御  (対象 ボタンのクラス: btn_once )
function disableHrefWithGoogleAnalytics(){

  jQuery(function($){
    //押下時にhrefを無効にして、キャンセルする
      $(".btn_once").removeAttr("href");
  });
}

  