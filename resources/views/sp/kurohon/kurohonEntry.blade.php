<!DOCTYPE html>
<html>
<head>
<meta name="robots" content="noindex,nofollow">
@include('common._ogp')
<title>【ウィルワン】ご希望条件入力フォーム</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" >
<link rel="stylesheet" type="text/css" href="/woa/css/entry/sp/style1.css?20201014">
<link rel="stylesheet" type="text/css" href="/woa/css/common/base.css">
<link rel="stylesheet" type="text/css" href="/woa/css/common/reentry.css">
<link rel="stylesheet" type="text/css" href="/woa/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="/woa/css/common/form/swiper.min.css">
@include('common._gtag')
  <link rel="icon" href="/woa/favicon.ico">
</head>
<body id="pagetop" class="likeModal bg-completionPage sp">
　  <a data-modal="branch" style="display: none;">お気持ちはどちらに近いですか？</a>
    <div class="modal branch">
        <div class="modalBody intro">
            <div id="branch">
                <h1>お気持ちはどちらに近いですか？</h1>
                <a class="close branch_btn-A"><span>近いうちに転職したい</span></a>
                <a class="close branch_btn-B"><span>今は情報収集したい</span></a>
                <div class="hands_modal"></div>
            </div>
        </div>
    <div class="modalBK"></div>
  </div>
  <div class="header">
    <img src="/woa/images/logo.png" class="header-logo" alt="ウィルワン">
  </div>
  <div class="likeModal_contents ani-showFloaty style-mamiya">
    <div id="wrap">
      <!-- 3秒転職アンケート -->
      <div class="formMuscle" style="height: auto !important; margin-bottom: 200px;">
        <div class="formMuscle_box" style="padding-top: 1em; height: auto !important;">
          <div class="formMuscle_form" style="margin-top: 0;">
            <form name="frm" action="{{config('app.url')}}/re/kurohon/complete" method="post" onsubmit="disableButton();">
              @include ( 'common/_hidden_reentry' )
              <input type="hidden" id="changetype" name="changetype" value="転職アンケート">
              <div class="swiper-container">
                <div class="swiper-wrapper">

                  <!-- 〓〓〓　スライド　〓〓〓 -->
                  <div id="stepLicense" class="swiper-slide swiper-no-swiping" style="position: relative; min-height: 350px;">
                    <h2 class="formMuscle_title" style="margin: 0 auto 5px; color: #0dc582; text-align: center;"><strong>お持ちの資格</strong>を教えてください！</h2>
                    <div id="license_container" class="formMuscle_formItem formItemLicense" style="padding:0 4px;">
                      <div class="formMuscle_error" id="errorMsg_license" style="display: none;">お持ちの資格を下記から選んでください！</div>
                      <div class="formMuscle_error" id="graduation_year_errmsg" style="display: none;">学生の場合は卒業年を下記から選んでください！</div>
                      @if($errors->has('license'))
                      <div class="error">
                        <ul><li><span class="required_error">※{{$errors->first('license')}}</span></li></ul>
                      </div>
                      @endif
                      @if($errors->has('graduation_year'))
                      <div class="error">
                        <ul><li><span class="required_error">※{{$errors->first('graduation_year')}}</span></li></ul>
                      </div>
                      @endif
                      <div class="partial_form formBody" id="Step1" style="visibility: visible; padding: 0 5px 0 5px;">
                        <div id="license_container" class="formItem">
                          <div class="row row3col" style="margin:0px 0px 10px 0px;">
                            @foreach($licenseList as $value)
                            @if(!in_array($value->id, $licenseStudent))
                            <div class="col @if(@in_array($value->id, old('license', []))) checked @endif">
                              <input type="checkbox" name="license[]" id="license_{{$value->id}}" value="{{$value->id}}" class="checkboxCol" @if(@in_array($value->id, old('license', []))) checked="checked" @endif >
                              <label for="license_{{$value->id}}" class="checkbox">
                                <img src="/woa/entry/sp/form18/img/license_{{$value->id}}.png" width="30px"><br>{{$value->license}}
                              </label>
                            </div>
                            @endif
                            @endforeach
                            <div class="formItemStudent row row3col" style="display: none; width: 100%; margin:0;">
                              @foreach($licenseList as $value)
                              @if(in_array($value->id, $licenseStudent))
                              <div class="col @if(@in_array($value->id, old('license', []))) checked @endif" style="text-align: left; width: 100%;">
                                <input type="checkbox" name="license[]" id="license_{{$value->id}}" value="{{$value->id}}" class="checkboxCol" @if(@in_array($value->id, old('license', []))) checked="checked" @endif >
                                <label for="license_{{$value->id}}" class="checkbox">
                                  <img src="/woa/entry/sp/form18/img/license_{{$value->id}}.png" width="30px"><br>{{$value->license}}
                                </label>
                              </div>
                              @endif
                              @endforeach
                            </div>
                            <div class="col checkbox" id="slide_student_btn" style="width: 100%;">
                              <label class="checkbox"><img src="/woa/entry/sp/form18/img/license_student.png" width="30px"><br>学生</label>
                            </div>
                          </div>
                        </div>
                        <div id="graduation_year_area" style="margin:0; display:@if($student) block; @else none; @endif;">卒業年：
                          <select name="graduation_year" id="graduation_year" class="select02">
                            <option value="">選択してください</option>
                            @foreach($graduationYearList as $key => $value)
                            <option label="{{ $value }}" value="{{ $key }}" @if(old('graduation_year') == $key) selected @endif>{{ $value }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div style="padding:0 4px; margin-top: 10px; width: 100%;">
                          <button type="button" id="button-next1" class="formMuscle_submit disabled" style="width: 100%;">次へ <i class="fa fa-chevron-right" style="margin: 0 -0.5em 0 0.5em;"></i></button>
                        </div>
                      </div>
                    </div><!-- /.formMuscle_formItem -->
                  </div><!-- /.swiper-slide -->

                  <!-- 〓〓〓　スライド　〓〓〓 -->
                  <div class="swiper-slide swiper-no-swiping">
                    <h2 class="formMuscle_title" style="margin: 0 auto 5px; text-align: center;"><strong>希望雇用形態</strong>を教えてください！</h2>
                    <div class="formMuscle_formItem" style="padding:0 4px;">
                      <div class="formMuscle_error" id="errorMsg_req_emp_type_recent" style="display: none;">ご希望の雇用形態を下記から選んでください！</div>
                      @if($errors->has('req_emp_type'))
                      <div class="error">
                        <ul><li><span class="required_error">※{{$errors->first('req_emp_type')}}</span></li></ul>
                      </div>
                      @endif
                      <ul class="select-list-recent2 oneColumn" style=" padding-left: 0;">
                        @foreach ($req_emp_type_list as $key => $row )
                        <li>
                          <input type="radio" name="req_emp_type_recent[]" id="req_emp_type_recent[{{ $key }}]" value="{{ $key }}" @if (old('req_emp_type') == $key ) checked="checked"@endif>
                          <label for="req_emp_type_recent[{{ $key }}]"><i class="fa fa-circle"></i> {{ $row }}</label>
                        </li>
                        @endforeach
                      </ul>
                    </div><!-- /.formMuscle_formItem -->
                    <div class="formMuscle_formItem" style="padding: 0 4px;">
                      <button type="button" id="button-next" class="formMuscle_submit disabled">次へ</button>
                    </div><!-- /.formMuscle_formItem -->
                  </div><!-- /.swiper-slide -->

                  <!-- 〓〓〓　スライド　〓〓〓 -->
                  <div class="swiper-slide swiper-no-swiping">
                    <h2 class="formMuscle_title" style="margin: 0 auto 5px; text-align: center;"><strong>転職希望時期</strong>を教えてください！</h2>
                    <div class="formMuscle_formItem" style="padding:0 4px;">
                      <p style="font-size: 11px; color: #999; margin: 0.1em 0 0.5em;">※ご希望に沿った情報提供をするため、アンケートを実施しています。ご協力お願いします！</p>
                      <div class="formMuscle_error" id="errorMsg_req_date_recent" style="display: none;">転職希望時期を下記から選んでください！</div>
                      @if($errors->has('req_date'))
                      <div class="error">
                        <ul><li><span class="required_error">※{{$errors->first('req_date')}}</span></li></ul>
                      </div>
                      @endif
                      <ul class="select-list-recent2 oneColumn" style=" padding-left: 0;">
                        @foreach ($req_date_list as $key => $row )
                        <li>
                          <input type="radio" name="req_date_recent[]" id="req_date_recent[{{ $key }}]" value="{{ $key }}" @if (old('req_date') == $key ) checked="checked"@endif>
                          <label for="req_date_recent[{{ $key }}]"><i class="fa fa-circle"></i> {{ $row }}</label>
                        </li>
                        @endforeach
                      </ul>
                    </div><!-- /.formMuscle_formItem -->
                    <div class="formMuscle_formItem" style="padding: 0 4px;">
                      <button type="button" id="button-next2" class="formMuscle_submit disabled">次へ</button>
                    </div><!-- /.formMuscle_formItem -->
                  </div><!-- /.swiper-slide -->

                  <!-- 〓〓〓　スライド　〓〓〓 -->
                  <div class="swiper-slide swiper-no-swiping">
                    <div class="formMuscle_formItem" style="padding:0 4px;">
                      <h2 class="formMuscle_title" style="margin: 0 auto 5px; text-align: center;"><strong>退職のご意向</strong>を教えてください！</h2>
                      <div class="formMuscle_error" id="errorMsg_req_retirement_recent" style="display: none;">退職のご意向を下記から選んでください！</div>
                      @if($errors->has('retirement_intention'))
                      <div class="error">
                        <ul><li><span class="required_error">※{{$errors->first('retirement_intention')}}</span></li></ul>
                      </div>
                      @endif
                      <ul class="select-list-recent2 oneColumn" style=" padding-left: 0;">
                        @foreach ($req_retirement_list as $key => $value)
                        <li>
                          <input type="radio" name="req_retirement_recent[]" id="req_retirement_recent[{{ $loop->iteration }}]" value="{{ $key }}"@if (old('req_retirement_recent') == $key ) checked="checked"@endif>
                          <label for="req_retirement_recent[{{ $loop->iteration }}]"><i class="fa fa-circle"></i> {{ $value }}</label>
                        </li>
                        @endforeach
                      </ul>
                    </div><!-- /.formMuscle_formItem -->

                    <div class="formMuscle_formItem" style="padding:0 4px;">
                      <h2 class="formMuscle_title" style="margin: 0 auto 5px; text-align: center;"><strong>電話番号</strong>を入力してください！</h2>
                      <p class="key"><small>公開されません</small></p>
                      <div class="formMuscle_error" id="tel_errmsg" style="display: none; margin-top: 5px;"></div>
                      @if($errors->has('tel'))
                      <div class="error">
                        <ul><li><span class="tel_error">※{{$errors->first('tel')}}</span></li></ul>
                      </div>
                      @endif
                      <ul>
                        <li>
                          <input type="tel" name="tel" id="tel" value="{{ old('tel')}}" size="14" placeholder="例：09012345678" maxlength="11">
                          <div id="suggest" style="display:none;" tabindex="-1"></div>
                        </li>
                      </ul>
                    </div><!-- /.formMuscle_formItem -->

                    <div class="formMuscle_formItem" style="padding:0 4px;">
                      <button type="button" id="submitMob" class="formMuscle_submit disabled" name="submitMob" onclick="setBtnAction('job_detail'); submitOnce();"><small>利用規約に同意の上</small><br>理想の職場を探しに行く!</button>
                    </div>
                    <div style="text-align: right;">
                      <a href="https://www.jinzaibank.com/woa/rule" rel="dialog nofollow" id="kiyaku" data-modal="rule" data-transition="pop" data-modal="rule">利用規約</a>
                    </div>
                  </div><!-- /.swiper-slide -->

                </div><!-- /.swiper-wrapper -->
              </div><!-- /.swiper-container -->
            </form>
          </div>
          <!-- /.formMuscle_form -->
        </div><!-- /.formMuscle_box -->
      </div><!-- 3秒転職アンケート -->
    </div><!-- /wrap -->
  </div>
<!-- / .boxFlex_contents -->

@include('pc.contents.include._modal_footer')

<script aysnc type="text/javascript" src="/woa/js/jquery-1.11.1.min.js?20180401"></script>
<script aysnc type="text/javascript" src="/woa/js/common/form/disableButton.js?20180401"></script>
<script aysnc type="text/javascript" src="/woa/js/common/form/enterBlock.js?20180401"></script>
<script aysnc type="text/javascript" src="/woa/js/common/form/LAB.js?20180401"></script>
<script aysnc type="text/javascript" src="/woa/js/common/form/itemSelected.js?20180401"></script>
<script type="text/javascript" src="/woa/js/common/form/ga_normal.js?20180401"></script>
<script type="text/javascript" src="/woa/js/entry/sp/form1/modal_footer1.js?20181019"></script>
<script type="text/javascript">
// hidden値を設定
function setBtnAction(act) {
  var btnact = act;
  document.getElementById("btn_action").value = btnact;
}
// エラーメッセージ表示（指定IDをdisplay: block にする）
function showErrorMsg(elem){
  var target = document.getElementById(elem);
  if (target.style.display != 'block') {
    target.style.display = 'block';
    setTimeout( function(){
      target.style.display = 'none';
    }, 2500);

  }
}
// エラーメッセージ表示（指定IDをdisplay: none にする）
function hiddenErrorMsg(elem){
  var target = document.getElementById(elem);
  if (target.style.display != 'none') {
    target.style.display = 'none';
  }
}

var cnt_submit=0;
// 二重登録防止
function submitOnce() {
  if (cnt_submit ==0) {
    if ($.validate(3) && $.validate('tel')){
      $.sendGA(1, 'step4_PASS');
      cnt_submit++;
    }
  }
}
</script>
<script aysnc type="text/javascript" src="/woa/js/common/form/jquery.validate_multiStepForm.js?20200928"></script>
<script src="/woa/js/common/form/swiper.jquery.min.js?20180221"></script>
<script>
$(function() {
  var swiper = null;
  // Slider
  if (swiper == null) {
    swiper = new Swiper('.swiper-container', {
      speed: 300,
      pagination: '.swiper-pagination',
      paginationClickable: false,
      preventClicks: false,
// TODO 一旦コメント
//      autoHeight: true,
      onlyExternal: false,
    });
  } else {
    swiper.slideTo(0, 0);
  }

  $.validate = function (step) {
    if (step == 'license') {
      // 保有資格
      var res = true;
      if ( ! $("input[name='license[]']").is(':checked') ) {
        showErrorMsg('errorMsg_license');
        return false;
      } else {
        hiddenErrorMsg('errorMsg_license');
      }
      return res;
    } else if (step == 'graduation_year') {
      // 卒業年
      var result = $.checkValidate('graduation_year');
      if (result == '') {
        hiddenErrorMsg('graduation_year_errmsg');
      } else {
        showErrorMsg('graduation_year_errmsg');
        return false;
      }
      return true;
    } else if (step == 1) {
      // 希望雇用形態
      var res = true;
      if ( ! $("input[name='req_emp_type_recent[]']").is(':checked') ) {
        showErrorMsg('errorMsg_req_emp_type_recent');
        return false;
      } else {
        hiddenErrorMsg('errorMsg_req_emp_type_recent');
      }
      return res;
    } else if ( step == 2) {
      // 転職希望時期
      var res = true;
      if ( ! $("input[name='req_date_recent[]']").is(':checked') ) {
        showErrorMsg('errorMsg_req_date_recent');
        return false;
      } else {
        hiddenErrorMsg('errorMsg_req_date_recent');
      }
      return res;
    } else if ( step == 3) {
      // 退職意向
      var res = true;
      if ( ! $("input[name='req_retirement_recent[]']").is(':checked') ) {
        showErrorMsg('errorMsg_req_retirement_recent');
        res = false;
      } else {
        hiddenErrorMsg('errorMsg_req_retirement_recent');
      }
      return res;
    } else if (step == 'tel') {
      // 電話番号
      var result = $.checkValidate('tel');
      if (result == '') {
        hiddenErrorMsg('tel_errmsg');
        return true;
      } else {
        $("#tel_errmsg").css({display: 'block'}).text(result);

        setTimeout( function(){
          $("#tel_errmsg").css({display: 'none'});
        }, 2500);
        return false;
      }
    }
    return true;
  }

  $.template_id = $(':hidden[name="t"]').val();

  var formSubmitted = false;
  // トラッキングブロック等で送信されなかった場合、再送信を行う
  function submitGaSendForm() {
    if (!formSubmitted) {
      formSubmitted = true;
      document.frm.submit();
    }
  }

  $.sendGA = function (actionType, label) {
    var action = 'none';
    if (actionType == 1) {
      action = 'click';
    } else if (actionType == 2) {
      action = 'edit';
    }
    // LP番号をLabel値に追加
    var lp = 'LP' + $.template_id + '_';
    if(label == "step4_PASS"){
      // 1秒後に再送信するように設定
      setTimeout(submitGaSendForm, 1000);
      ga('send', 'event', 'listing', action, lp + label, 0, {
        'nonInteraction': 1,
        hitCallback: submitGaSendForm
      });
      // GA4イベント発火用
      // 計測が済んだあと、または計測が済んでいないが1000ミリ秒経過した場合、submitGaSendFormを呼ぶ
      dataLayer.push({ 'event': 'lp-event-push', 'lp-event-element': lp + label, eventCallback: submitGaSendForm, eventTimeout: 1000 });
    } else {
      ga('send', 'event', 'listing', action, lp + label, 0, {'nonInteraction': 1});
      // GA4イベント発火用
      dataLayer.push({ 'event': 'lp-event-push', 'lp-event-element': lp + label });
    }
  }
  var license_recent_changed = 0;
  var req_emp_type_recent_changed = 0;
  var req_date_recent_changed = 0;
  var req_retirement_recent_changed = 0;
  var tel_changed = 0;

  $(window).load(function(){
    $('input').on('change',function () {
      $('input:checkbox:checked').parent().addClass('checked');
      $('input:radio:checked').parent().addClass('checked');
      $('input:not(:checked)').parent().removeClass('checked');
    });
  });
  // 保有資格
  $('input[name="license[]"]').on('change', function(){
    // $.validate_item('license');
    if(license_recent_changed == 0) {
      license_recent_changed = 1;
      $.sendGA(1, 'step1_selected');
    }
    if (typeof $.dispGradYear === 'function') {
      $.dispGradYear();
    }
  });

  // 卒業年エリアの表示/非表示を切替え
$.dispGradYear = function() {
    var checked = 0;
    var student = 0;
    $('input[name^="license[]"]:checked').each(function(){
        var str = $(this).val();
        var student_values = ['44', '45', '46'];
        if (student_values.indexOf(str) >= 0) {
            student++;
        }
        checked++;
    });
    if (checked > 0) {
        $('#license_errmsg').hide();
    }
    if (student == 0) {
        $('#graduation_year_area').hide();
        $('#graduation_year_errmsg').hide();
        $('#graduation_year').val('');
    } else {
        $('#graduation_year_area').show();
    }
    return true;
};

  // 転職希望時期
  $('input[name="req_emp_type_recent[]"]').on('change', function(){
    if(req_emp_type_recent_changed == 0) {
      req_emp_type_recent_changed = 1;
      $.sendGA(1, 'step2_selected');
    }
  });

  // 転職希望時期
  $('input[name="req_date_recent[]"]').on('change', function(){
    if(req_date_recent_changed == 0) {
      req_date_recent_changed = 1;
      $.sendGA(1, 'step3_selected');
    }
  });
  // 退職のご意向
  $('input[name="req_retirement_recent[]"]').on('change', function(){
    if(req_retirement_recent_changed == 0) {
      req_retirement_recent_changed = 1;
      $.sendGA(1, 'step4_taisyokuikou_selected');
    }
  });
  // 電話番号
  $('input[name="tel"]').on('change', function(){
    if(tel_changed == 0) {
      tel_changed = 1;
      $.sendGA(1, 'step4_tel_selected');
    }
  });
  // イベント
  $("#button-next1").on('click', function() {
    if ( ! $.validate('license')) {
      return false;
    }
    if ( ! $.validate('graduation_year')) {
      return false;
    }
    // 次へ
      swiper.slideNext();
      $(".formMuscle_box").removeClass('stepLicense');
      $(".formMuscle_box").addClass('step1');
      $('html,body').scrollTop(0);
      return true;
  });
  $("#button-next").on('click', function() {
    if ($.validate(1)) {
      // 次へ
      swiper.slideNext();
      $(".formMuscle_box").removeClass('step1');
      $(".formMuscle_box").addClass('step2');
      $('html,body').scrollTop(0);
      return true;
    }
    return false;
  });
  $("#button-next2").on('click', function() {
    if ($.validate(2)) {
      swiper.slideNext();
      $(".formMuscle_box").removeClass('step2');
      $(".formMuscle_box").addClass('step3');
      $('html,body').scrollTop(0);
      return true;
    }
    return false;
  });
  $("#submitMob").on('click', function() {
    var validateResult = true;
    if (!$.validate(3)) {
      validateResult = false;
    }
    if (!$.validate('tel')) {
      validateResult = false;
    }
    return validateResult;
  });
  $("input[name='license[]']").change(function() {
    // $('#license').val($(this).val());
    // hiddenErrorMsg('errorMsg_license');
    // $("#button-next1").removeClass('disabled');
    if ( $.validate('license') ){
      $('#license').val($(this).val());
      if($("#button-next1").size() > 0) {
        $("#button-next1").removeClass('disabled');
      }
    } else {
      if($("#button-next1").size() > 0) {
        $("#button-next1").addClass('disabled');
      }
    }
  });
  $("input[name='req_emp_type_recent[]']").change(function() {
    if ( $.validate(1) ){
      $('#req_emp_type').val($(this).val());
      if($("#button-next").size() > 0) {
        $("#button-next").removeClass('disabled');
      }
    } else {
      if($("#button-next").size() > 0) {
        $("#button-next").addClass('disabled');
      }
    }
  });
  $("input[name='req_date_recent[]']").change(function() {
    if ( $.validate(2) ){
      $('#req_date').val($(this).val());
      if($("#button-next2").size() > 0) {
        $("#button-next2").removeClass('disabled');
      }
    } else {
      if($("#button-next2").size() > 0) {
        $("#button-next2").addClass('disabled');
      }
    }
  });
  $("input[name='req_retirement_recent[]']").change(function() {
    if ($.validate(3) && $.checkValidate('tel') == '') {
      $('#retirement_intention').val($(this).val());
      $('#tel').val($('#tel').val());
      $("#submitMob").removeClass('disabled');
    } else {
      $("#submitMob").addClass('disabled');
    }
  });
  $("input[name='tel']").change(function() {
    if ($.validate('tel') && $("input[name='req_retirement_recent[]']").is(':checked')) {
      $('#retirement_intention').val($("input[name='req_retirement_recent[]']:checked").val());
      $('#tel').val($(this).val());
      $("#submitMob").removeClass('disabled');
    } else {
      $("#submitMob").addClass('disabled');
    }
  });

  // 値保持用
  if($("input[name='license[]']").is(':checked') ) {
    $('#license').val($("input[name='license[]']:checked").val());
    $("#button-next1").removeClass('disabled');
  }
  if($("input[name='req_emp_type_recent[]']").is(':checked') ) {
    $('#req_emp_type').val($("input[name='req_emp_type_recent[]']:checked").val());
    $("#button-next").removeClass('disabled');
  }
  if($("input[name='req_date_recent[]']").is(':checked') ) {
    $('#req_date').val($("input[name='req_date_recent[]']:checked").val());
    if($("#button-next2").size() > 0) {
      $("#button-next2").removeClass('disabled');
    }
  }
  if($("input[name='req_retirement_recent[]']").is(':checked') || $("#tel").val()) {
    $('#retirement_intention').val($("input[name='req_retirement_recent[]']:checked").val());
    $('#tel').val($("#tel").val());
    $("#submitMob").removeClass('disabled');
  }

    // 「学生」アコーディオンの展開
  $("#slide_student_btn").on("click", function() {
    $("#stepLicense").attr("style", "min-height: 520px");
    $("#slide_student_btn").attr("style", "display:none !important");
    $(".bxslider > li").height("");
    $(".hands").hide();
    $(".formItemStudent").slideToggle("normal");
  });
});

  jQuery(function($) {
    // 「お気持ちはどちらに近いですか？」の処理
    $('[data-modal="branch"]').trigger('click');
    // 「近いうちに転職したい」のとき
    $('.branch_btn-A').on('click', function() {
        $('html, body').scrollTop(0);
    })
    // 「今は情報収集したい」のとき
    $('.branch_btn-B').on('click', function() {
        $('html, body').scrollTop(0);
    })
  });
</script>

<!-- 共通タグ -->
{{-- @include('template_pc.include._commonTag') --}}
</body>
</html>
