// モーダルウィンドウ
jQuery(document).ready(function ($) {
  var wn = "";

  $(document).on("click", "[data-modal]", function (event) {
    event.preventDefault();
    wn = "." + $(this).data("modal");
    var mW =
      $(wn)
        .find(".modalBody")
        .innerWidth() / 2;
    var mH =
      $(wn)
        .find(".modalBody")
        .innerHeight() / 2;
    $(wn)
      .find(".modalBody")
      .css({ "margin-left": -mW, "margin-top": -mH });
    $(wn).fadeIn(200);
    $("body").addClass("modal-on");
  });
  $(".close > *,.modalBK,.close").click(function () {
    $(wn).fadeOut(200);
    $("body").removeClass("modal-on");
  });

  //利用規約リンク等
  $("#rule").load("/woa/include/ct/_rule.html");
  $("#privacy").load("/woa/include/ct/_privacy-policy.html");
  $("#access").load("/woa/include/ct/_company.html");
});

pageNo = 0;
let slider;
let flag = true;

$(function () {
    // ------------------------------------------------------------------------
  // テンプレート番号を取得
  // ------------------------------------------------------------------------
  $.template_id = $(':hidden[name="t"]').val();

  // ------------------------------------------------------------------------
  // GLPID
  // ------------------------------------------------------------------------
  var glpId = $(':hidden[name="lp_id"]').val();

  // ------------------------------------------------------------------------
  // GAラベルのLP名を作成
  // ------------------------------------------------------------------------
  var lpNo = '';
  if (glpId) {
      lpNo = glpId + '-';
  }
  lpNo += $.template_id;

  // bxSlider 初期化
  slider = $(".bxslider").bxSlider({
    mode: "horizontal",
    useCSS: false,
    infiniteLoop: false,
    hideControlOnEnd: true,
    touchEnabled: false,
    speed: 200,
    easing: "easeInOutQuart",
    onSlidePrev: $.onSlidePrev,
    onSlideAfter: $.onSlideAfterHandler
  });

  $(".bx-controls").addClass("index1");

  //Nextのリンク内を[次のStepへ]の画像に変更
  $("a.bx-next").html('<span class="nextBtn">残り4STEP</span>');

  //Prevのリンク内を変更
  $("a.bx-prev").html('<span>戻る</span>');

  $("a.bx-next").wrap('<p class="btn-area"></p>');

  //初期状態では1ページ目の表示なので、Prevリンクを非表示にする。
  $("a.bx-prev").css("visibility", "hidden");

  $('.bx-controls').addClass('index1');
  $('.bx-controls').before('<div class="cover"></div>');
  $('.btn-area > a').addClass('off');

  // ------------------------------------------------------------------------
  //1ページ目のクローンに対する処理
  // ------------------------------------------------------------------------
  // チェックボックスの削除
  $("li.bx-clone input[type=checkbox]").remove();
  // labelのfor属性を削除
  $("li.bx-clone label").removeAttr("for");
  // id="license_container"のdivからid属性を削除
  $("li.bx-clone div#license_container").removeAttr("id");

  pageNo = 1;

  // 初期表示の高さを指定
  $('.bx-viewport').css('min-height', 490);

  // 資格
  $('input[name="license[]"]').on("change", function () {
    if ($("#slide_student_btn").is(":hidden")) {
      $(".bxslider > li").height("");
    }

    if (typeof $.dispGradYear === "function") {
      $.dispGradYear();
    }
    var result = $.validate_form(pageNo);

    // 手の位置を調整
    var isNext = $.handsSetting(pageNo, result);
    $.setNextBtn(isNext);
  });
  // 「学生」アコーディオンの展開
  $("#slide_student_btn").on("click", function () {
    $("#slide_student_btn").attr("style", "display:none !important");
    $('.bx-viewport').css('min-height', 690);
    $('html, body').animate({scrollTop: 50}, 300);
    $(".bxslider > li").height("");
    $(".hands").hide();
    $(".formItemStudent").slideToggle("normal", function () {
      // 手の位置を調整（「つぎへ」選択後だと位置がずれるため、コールバックで対応）
      var pageNo = 1;
      var result = $.validate_form(pageNo);
      var isNext = $.handsSetting(pageNo, result);
    });
  });

  // 卒業年
  $("#graduation_year").on("change", function () {
    var result = $.validate_form(pageNo);
    // 手の位置を調整
    var isNext = $.handsSetting(pageNo, result);

    $.setNextBtn(isNext);
  });

  // 転職時期
  $('input[name="req_date"]').on("change", function () {
    var result = $.validate_form(pageNo);
    // 手の位置を調整
    var isNext = $.handsSetting(pageNo, result);

    if ($(this).prop('checked')) {
      $('#req_date_errmsg').hide();
          var step3Btn = $("#req_emp_types_area").offset().top;
          $('html, body').animate({scrollTop: step3Btn}, 'slow');
    }
    $.setNextBtn(isNext);
  });

  // 働き方
  $('input[name="req_emp_type[]"]').on("change", function () {
    var result = $.validate_form(pageNo);
    // 手の位置を調整
    var isNext = $.handsSetting(pageNo, result);

    $.setNextBtn(isNext);
  });

  $('input#zip, input#name_kan, input#input_birth_year, input#mob_phone').bind('focus focusin keydown keyup keypress change',function(){
    if (!$(this).is(".placeOn")) {
        $(this).addClass('placeOn');
    }
  });

  // 郵便番号
  // changeはフォーカス外れないと検知しない
  // keyupはキー操作検知なん
  $("#zip").on("change keyup", function () {
    if ($("#zip").val().length == 7) {
      $("#addr_txt_area").hide();
      $("#addr_area").show();
      $("#zip2").addClass("on");
      $('.bx-viewport').css('min-height', 490);
    }
  });
  $("#zip3,#addr_txt_area").on("click", function () {
    $.dispAddr();
    $('.bx-viewport').css('min-height', 510);
  });
  // 都道府県
  $("#addr1").on("change", function () {
    var result = $.validate_form(pageNo);
    // 手の位置を調整
    var isNext = $.handsSetting(pageNo, result);

    $.setNextBtn(isNext);
  });
  // 市区町村
  $("#addr2").on("change", function () {
    var result = $.validate_form(pageNo);
    // 手の位置を調整
    var isNext = $.handsSetting(pageNo, result);

    $.setNextBtn(isNext);
  });

  // お名前（漢字）
  $("#name_kan").on("change", function () {
    var result = $.validate_form(pageNo);
    // 手の位置を調整
    var isNext = $.handsSetting(pageNo, result);

    $.setNextBtn(isNext);

    $("#name_kan").blur(function () {
      $(this).addClass('on');
      $(".name_cana-frame").show();
      $('.bx-viewport').css('min-height', 430);
    });
  });
  // お名前（ふりがな）
  $("#name_cana").on("change", function () {
    var result = $.validate_form(pageNo);
    // 手の位置を調整
    var isNext = $.handsSetting(pageNo, result);

    $.setNextBtn(isNext);
  });
  // 生まれ年
  $("#input_birth_year").on("change", function () {
    var val = $(this).val();
    // 全角数字を半角数字に変換
    var str = val.replace(/[０-９]/g, function (s) {
      return String.fromCharCode(s.charCodeAt(0) - 0xfee0);
    });
    // 数値以外を除去
    var rep = str.replace(/[^0-9]/g, "");
    // 変換した値で入力値を上書き
    $(this).val(rep);

    var result = $.validate_form(pageNo);
    // 手の位置を調整
    var isNext = $.handsSetting(pageNo, result);
    $('.selectWrap.birth').addClass('on');

    $.setNextBtn(isNext);
  });

  // 退職意向
  $('input[name="retirement_intention"]').on("change", function () {
    var result = $.validate_form(pageNo);
    // 手の位置を調整
    var isNext = $.handsSetting(pageNo, result);

    $.setNextBtn(isNext);
  });
  // メールアドレス
  $("#mob_mail").on("change", function () {
    // 任意項目なので入力値があったらチェック
    if ($(this).val().length > 0) {
      var result = $.checkMail($(this).val(), true);
    } else {
      // 未入力になったらエラーメッセージとかクリアしておく
      $.errmsg_off("mob_mail");
    }
  });

  // 携帯電話番号
  $("#mob_phone").on("change", function () {
    var result = $.validate_form(pageNo);
    // 手の位置を調整
    var isNext = $.handsSetting(pageNo, result);
    $(this).addClass('on');

    $.setNextBtn(isNext);
  });

  // 転居可否
  $('input[name="moving_flg"]').on("change", function () {
    var result = $.validate_form(pageNo);
    // 手の位置を調整
    var isNext = $.handsSetting(pageNo, result);

    $.setNextBtn(isNext);

    $('.cover').fadeIn();
  });
});

// ページ番号を与えてそのページ内に制限する関数
$.setTabOrder = function (pagenum) {
  for (var step = 1; step <= 5; step++) {
    var div = "div#Step" + step;
    var sel = div + " input," + div + " select";
    if (pagenum == step) $(sel).removeAttr("disabled");
    else $(sel).attr("disabled", "disabled");
  }
};

// ------------------------------------------------------------------------
// blur(),focus()を実行
// ------------------------------------------------------------------------
$.setBlur = function () {
  $("#zip").blur();
  $("#addr3").blur();
  $("#name_kan").blur();
  $("#name_cana").blur();
  $("#mob_phone").blur();
  $("#mob_mail").blur();
};

// ------------------------------------------------------------------------
// スライドの移動が完了した直後に呼ばれるハンドラ
// ------------------------------------------------------------------------
$.onSlideAfterHandler = function ($slideElement, oldIndex, newIndex) {
  var pagenum = newIndex + 1;

  pageNo = pagenum;

  // フォーカス外し
  $.setBlur();

  /* validation 解除*/
  $("#license_errmsg").text("");
  $("#license_errmsg").hide();
  $("#graduation_year_errmsg").text("");
  $("#graduation_year_errmsg").hide();

  if (pagenum < 6) {
    // タブインデックスの更新
    $.setTabOrder(pagenum);
    $('.btn-area > a').addClass('off');

    // ページ表示画像の変更
    $("img#form_status")
      .attr("data-page-num", pagenum)
      .attr("src", "/woa/entry/img/" + pagenum + ".png");

    //1ページ目の場合、Prevリンクを非表示にする。
    $("a.bx-prev").css("visibility", pagenum == 1 ? "hidden" : "visible");

    // [次へ]ボタンか[登録する]ボタンを切り替え
    $("a.bx-next").attr("data-page-num", pagenum);

    $formNo = $.template_id.replace(/PC_|SP_/g,'');
    $imgPath = "/woa/entry/sp/form" + $formNo + "/img";

    // コントローラー（.bx-controls）にインデックスに対応したクラスを追加
    var nextIndex = pagenum + 1;
    $(".bx-controls")
      .removeClass("index" + newIndex)
      .removeClass("index" + nextIndex)
      .addClass("index" + pagenum);
    jQuery('#job_text').css('display','none');
    switch (pagenum) {
      case 1:
        $("body")
          .removeClass("step2 step3 step4 step5")
          .addClass("step1");
        $("#retirement_intention_area").hide();
        $(".terms-link, .cover, .arrow_step5").hide();
        $('a.bx-next').html('<span class="nextBtn">残り4STEP</span>');
        jQuery('#job_text').css('display','block');

        // 学生モードを判定してスライドの高さを指定
        if($('#slide_student_btn').is(':visible')){
          $('.bx-viewport').css('min-height', 490);
        } else {
          $('.bx-viewport').css('min-height', 690);
        }

        break;
      case 2:
        $("body")
          .removeClass("step1 step3 step4 step5")
          .addClass("step2");
        $("#retirement_intention_area").hide();
        $("#req_date_area").show();
        $(".terms-link, .cover, .arrow_step5").hide();
        $('a.bx-next').html('<span class="nextBtn">残り3STEP</span>');
        $('.bx-viewport').css('min-height', 810);
        break;
      case 3:
        $("body")
          .removeClass("step1 step2 step4 step5")
          .addClass("step3");
        $("#retirement_intention_area, #req_date_area").hide();
        $(".terms-link, .cover, .arrow_step5").hide();
        $('a.bx-next').html('<span class="nextBtn">残り2STEP</span>');
        $('.bx-viewport').css('min-height', 'inherit');
        break;
      case 4:
        $("a.bx-next").removeClass("newBtn03");
        $("body")
          .removeClass("step1 step2 step3 step5")
          .addClass("step4");
        $(".terms-link, .cover, .arrow_step5").hide();
        $('a.bx-next').html('<span class="nextBtn">残り1STEP</span>');
        $('.bx-viewport').css('min-height', 390);
        break;
      case 5:
        $("a.bx-next").addClass("newBtn03");
        $("body")
          .removeClass("step1 step2 step3 step4")
          .addClass("step5");
        $("#retirement_intention_area").show();
        $('.cover').hide();
        $('a.bx-next').html('<img alt="利用規約に同意の上　該当の求人を探しに行く" class="btn1"><img alt="利用規約に同意の上　該当の求人を探しに行く" class="btn2">');
        $('.btn1').attr('src', $imgPath + '/btn.gif' );
        $('.btn2').attr('src', $imgPath + '/btn.png' );
        $('.bx-viewport').css('min-height', 780);
        break;
    }

    var result = $.validate_form(pageNo);

    if (pagenum == 1) {
      // Step1が表示された場合、高さを再設定
      var step1height = $("#Step1").outerHeight(true);
      $(".bxslider > li").height(step1height);
    } else {
      // それ以外は外す
      $(".bxslider > li").height("");
    }

    // 手の位置を調整
    var isNext = $.handsSetting(pageNo, result);
  }

  if (pagenum == 5) {
    // 規約リンクの表示
    $(".bx-controls-direction").after(
      '<div class="terms-link"><a href="/woa/rule" rel="dialog nofollow" id="form_kiyaku" data-modal="rule" data-transition="pop">利用規約</a></div>'
    );
    // 利用規約
    $('#form_kiyaku').on('click', function(){
      kiyakuGA4();
    });
  }
};

// 卒業年エリアの表示/非表示を切替え
$.dispGradYear = function () {
  var checked = 0;
  var student = 0;
  $('input[name^="license[]"]:checked').each(function () {
    var str = $(this).val();
    var student_values = ["44", "45", "46"];
    if (student_values.indexOf(str) >= 0) {
      student++;
    }
    checked++;
  });
  if (checked > 0) {
    $("#license_errmsg").text("");
    $("#license_errmsg").hide();
    // 「学生」ではない資格が押された際は、画面の高さを再取得する
    if ($("#slide_student_btn").is(":visible")) {
      var step1height = $("#Step1").outerHeight(true);
      $(".bxslider > li").height(step1height);
    }
  }
  if (student == 0) {
    $("#graduation_year_area").hide();
    $("#graduation_year_errmsg").text("");
    $("#graduation_year_errmsg").hide();
    $("#graduation_year").val("");
  } else {
    $("#graduation_year_area").show();
    $('html, body').animate({scrollTop: 200}, 300);
  }
  return true;
};

// 住所入力エリアの表示/非表示を切替え
$.dispAddr = function (forcedDisplay = false) {
  if (!$("#addr_area").is(":visible") || forcedDisplay) {
    // 表示
    $("#addr_txt_area").hide();
    $("#addr_area").show();
    $("#zip3").addClass("on");
    $('.bx-viewport').css('height', 'max-content');
  } else {
    // 非表示
    $("#addr_area").hide();
    $("#addr_txt_area").show();
    $("#zip3").removeClass("on");
  }
  return true;
};

// ------------------------------------------------------------------------
// 手カーソルの位置を調整
// ------------------------------------------------------------------------
$.handsSetting = function (pagenum, result) {
  var isNext = false;

  // 表示
  $(".hands").show();

  // 表示位置の設定
  switch (pagenum) {
    case 1:
      if (Object.keys(result.errors).length == 0) {
        isNext = true;
        // 次へ
        var position = $(".nextBtn").offset();
        $(".hands").animate({ top: position.top }, 0);
        $('.btn-area > a').removeClass('off');
      } else if ("license" in result.errors) {
        // 保有資格
        var position = $("#license_area").offset();
        $(".hands").animate({ top: position.top + 120 }, 0);
      } else if ("graduation_year" in result.errors) {
        // 卒業年度
        var position = $("#graduation_year").offset();
        $(".hands").animate({ top: position.top }, 0);
      } else {
        // 1番上の項目
        var position = $("#license_area").offset();
        $(".hands").animate({ top: position.top + 120 }, 0);
      }
      break;
    case 2:
      $(".hands").css("right", "2%");
      if (Object.keys(result.errors).length == 0) {
        isNext = true;
        // 次へ
        var position = $(".nextBtn").offset();
        $(".hands").animate({ top: position.top }, 0);
        $('.btn-area > a').removeClass('off');
        if(flag){
          slider.goToNextSlide();
          flag = false;
          $('ul.status').attr('data-page-num', 3);
          var uniqueId = (new Date).getTime() + '-';
          // add history
          history.pushState(uniqueId + 3, null, null);
        }
      } else if ("req_date" in result.errors) {
        // 転職時期
        var position = $("#req_date_area").offset();
        $(".hands").animate({ top: position.top + 40 }, 0);
      } else if ("req_emp_type" in result.errors) {
        // 働き方
        var position = $("#req_emp_types_area").offset();
        $(".hands").animate({ top: position.top + 40 }, 0);
      } else {
        // 1番上の項目
        var position = $("#req_date_area").offset();
        $(".hands").animate({ top: position.top + 40 }, 0);
      }
      break;
    case 3:
      if (Object.keys(result.errors).length == 0) {
        // 次へ
        var position = $(".nextBtn").offset();
        $(".hands").animate({ top: position.top }, 0);
        $('.btn-area > a').removeClass('off');
        isNext = true;
      } else if("moving_flg" in result.errors) {
        // 転居可否
        var position = $("#moving_flg_area").offset();
        $(".hands").animate({ top: position.top + 40 }, 0);
      } else {
        if (!$("#zip2").hasClass("on")) {
          // アコーディオンが閉じている
          // 郵便番号
          var position = $("#zip").offset();
          $(".hands").animate({ top: position.top }, 0);
        } else {
          // アコーディオンが開いている
          if (
            !("addr1" in result.errors) &&
            "addr2" in result.errors &&
            "addr3" in result.errors
          ) {
            // 都道府県
            var position = $("#addr1").offset();
            $(".hands").animate({ top: position.top }, 0);
          } else if ("addr1" in result.errors) {
            // 都道府県
            var position = $("#addr1").offset();
            $(".hands").animate({ top: position.top }, 0);
          } else if ("addr2" in result.errors) {
            // 市区町村
            var position = $("#addr2").offset();
            $(".hands").animate({ top: position.top }, 0);
          } else if ("addr3" in result.errors) {
            // 番地・建物
            var position = $("#addr3").offset();
            $(".hands").animate({ top: position.top }, 0);
          } else {
            // 1番上の項目
            var position = $("#addr1").offset();
            $(".hands").animate({ top: position.top }, 0);
          }
        }
      }
      break;
    case 4:
      if (Object.keys(result.errors).length == 0) {
        // 次へ
        var position = $(".nextBtn").offset();
        $(".hands").animate({ top: position.top }, 0);
        $('.btn-area > a').removeClass('off');
        isNext = true;
      } else if ("name_kan" in result.errors) {
        // 氏名
        var position = $("#name_kan").offset();
        $(".hands").animate({ top: position.top }, 0);
      } else if ("name_cana" in result.errors) {
        // かな
        var position = $("#name_cana").offset();
        $(".hands").animate({ top: position.top }, 0);
      } else if ("input_birth_year" in result.errors) {
        // 生まれ年
        var position = $("#input_birth_year").offset();
        $(".hands").animate({ top: position.top }, 0);
      } else {
        // 1番上の項目
        $(".hands").animate({ top: position.top }, 0);
      }
      break;
    case 5:
      if (Object.keys(result.errors).length == 0) {
        // 次へ
        var position = $(".newBtn03 ").offset();
        $(".hands").animate({ top: position.top }, 0);
        $('.btn-area > a').removeClass('off');
        isNext = true;
      } else if ("mob_phone" in result.errors) {
        // 電話番号
        var position = $("#mob_phone").offset();
        $(".hands").animate({ top: position.top }, 0);
      } else if ("retirement_intention" in result.errors) {
        // 退職意向
        var position = $("#retirement_intention_area").offset();
        $(".hands").animate({ top: position.top + 30 }, 0);
      } else if ("mob_mail" in result.errors) {
        // メアド
        var position = $("#mob_mail").offset();
        $(".hands").animate({ top: position.top }, 0);
      } else {
        // 1番上の項目
        var position = $("#mob_phone").offset();
        $(".hands").animate({ top: position.top }, 0);
      }
      break;
    default:
      // 非表示
      $(".hands").hide();
      break;
  }

  return isNext;
};

// ------------------------------------------------------------------------
// 手カーソルの位置を調整（アコーディオンの開閉イベント時）
// ------------------------------------------------------------------------
$.handsSettingAccordion = function (pagenum) {
  // 表示位置の設定
  switch (pagenum) {
    case 3:
      // 表示
      $(".hands").show();
      // バリデーションの実行
      var result = $.validate_form(pagenum);
      if (Object.keys(result.errors).length == 0) {
        // 次へ
        $(".hands").animate({ bottom: "8%" }, 0);
      } else if ($("#zip2").hasClass("on")) {
        // 開く->閉じる
        // 郵便番号
        $(".hands").animate({ bottom: "75%" }, 0);
      } else {
        // 閉じる->開く
        // 都道府県
        $(".hands").animate({ bottom: "60%" }, 0);
      }
      break;
    default:
      // 非表示
      $(".hands").hide();
      break;
  }
};

// ------------------------------------------------------------------------
// 手カーソルの位置を調整
// ------------------------------------------------------------------------
$.setNextBtn = function(isNext) {
    $('.arrow_step5').hide();
    if (isNext) {
        // ON
        //$('.btn-area > a').removeClass('off');
        if (pageNo !== 2) {
          $('.cover').fadeIn();
          $('.cover').on('click', function(){
            $(this).fadeOut();
          });
        } else if (pageNo == 5) {
            $('.arrow_step5').show();
            // 手カーソルを非表示
            $('.hands').hide();
        }
    } else {
        // OFF
        $('.btn-area > a').addClass('off');
        $('.arrow_step5').hide();
    }
};

  // searchcity用コールバック
function callbackZip(select_city, _value) {
  // フォーカス外し
  $.setBlur();

  // 手の位置を調整
  var result = $.validate_form(pageNo);
  var isNext = $.handsSetting(pageNo, result);
}
