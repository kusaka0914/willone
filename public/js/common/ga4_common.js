/**
 * GA4タグの送信処理
 */
$(function($) {

  var lpNo = '';
  // ------------------------------------------------------------------------
  // テンプレート番号を取得
  // ------------------------------------------------------------------------
  lpNo = $(':hidden[name="t"]').val();
  if (!lpNo) {
    // LP値をURLから取得
    var urls = location.href.match(/PC_\d{1,}|SP_\d{1,}/g);
    if (urls && urls[0]) {
      lpNo = urls[0];
    }
  }

  // ------------------------------------------------------------------------
  // GAタグ設定
  // ------------------------------------------------------------------------

  $.sendGA4 = function (label) {
    if (lpNo) {
      dataLayer.push({'event':'lp-event-push','lp-event-element':lpNo + '_' + label});
    }
  }

  // 資格
  $('input[name="license[]"]').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-license');
  });
  // 卒業年
  $('select[name="graduation_year"]').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-graduation_year');
  });
  // 紹介者氏名
  $('#introduce_name').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-introduce_name');
  });

  // 働き方
  $('input[name="req_emp_type[]"]').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-req_emp_type');
  });
  // 転職時期
  $('select[name="req_date"],input[name="req_date"]').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-req_date');
  });

  // 郵便番号
  $('#zip').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-zip');
  });
  // 都道府県
  $('#addr1').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-addr1');
  });
  // 市区町村
  $('#addr2').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-addr2');
  });
  // 番地建物
  $('#addr3').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-addr3');
  });
  // 転居可否
  $('input[name="moving_flg"]').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-moving');
  });
  // お名前（漢字）
  $('#name_kan').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-name_kan');
  });
  // お名前（ふりがな）
  $('#name_cana').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-name_cana');
  });
  // 生まれ年（プルダウン）
  $('#birth_year').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-birth_year');
  });

  // 生まれ年
  $('#input_birth_year').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-input_birth_year');
  });

  // 退職意向
  $('#retirement_intention,input[name="retirement_intention"]').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-retirement_intention');
  });
  // メールアドレス
  $('#mob_mail').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-mob_mail');
  });
  // 携帯電話番号
  $('#mob_phone').on('change', function(){
    $.sendGA4('STEP' + getPageNo() + '-mob_phone');
  });

  // 利用規約
  $('#kiyaku').on('click', function(){
    kiyakuGA4();
  });
  // 個人情報の取り扱いについて
  $('#kojin_joho').on('click', function(){
    $.sendGA4('kojin_joho');
  });
  // 運営会社
  $('#company').on('click', function(){
    $.sendGA4('company');
  });
  // プライバシーマーク
  $('#privacymark').on('click', function(){
    $.sendGA4('privacymark');
  });
  // お気持ち 近いうちに就職
  $('.branch_btn-A').on('click', function(){
    $.sendGA4('STEP' + getPageNo() + '_branchA');
  });
  // お気持ち 今は情報収集したい
  $('.branch_btn-B').on('click', function(){
    $.sendGA4('STEP' + getPageNo() + '_branchB');
  });

  // 次へ、登録、入力チェックエラー
  $(document).on('click', 'a.bx-next', function(){
      var step = $('body').attr('class').toUpperCase();
      // 入力チェックエラー
      if (!$.validation_result) {
        $("div[id*='_errmsg']").each(function(idx, elem){
          if ($(this).css('display') != 'none' && $(this).html().length > 0) {
            var id = $(this).attr('id').replace(/_errmsg\d*/, '');
            // 都道府県、市区町村に関しては値をチェックして特定する
            if (id == 'addr1or2') {
              if ($('#addr1').val().length > 0 && $('#addr2').val().length == 0) {
                id = 'addr2';
              } else {
                id = 'addr1';
              }
            }
            $.sendGA4(step + '_error-' + id);
          }
        });
      } else {
        if (step != 'STEP5') {
          // 次へ
          $.sendGA4('STEP' + (parseInt(getPageNo()) + 1) + '_OPEN');
        } else {
          // 登録
          $.sendGA4('CV');
        }
      }
  });

  // 戻る
  $(document).on('click', 'a.bx-prev', function(){
    var step = $('body').attr('class').toUpperCase();
    $.sendGA4('pre-' + step);
  });

  // 流入
  $.sendGA4($('body').attr('class').toUpperCase() + '_OPEN');

});

const exitProtectionOpenGa = function(){
  $.sendGA4('exit_protection_open');
}
const exitProtectionClickGa = function(){
  $.sendGA4('exit_protection_click');
}

function getPageNo(){
  return jQuery('body').attr('class').toUpperCase().replace('STEP', '');
}
function kiyakuGA4(){
  $.sendGA4('kiyaku');
}
