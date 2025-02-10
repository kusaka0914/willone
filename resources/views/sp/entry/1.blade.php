<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    @include('common._ogp')
    <title>{{ $headtitle }}</title>
    <script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/woa/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="/woa/js/jquery.autoKana.js"></script>
    <script type="text/javascript" src="/woa/js/common/ga_common.js"></script>
    <script src="{{addQuery('/woa/js/common/ga4_common.js')}}"></script>
    <script type="text/javascript" src="/woa/js/common/SearchCity/jquery.SearchCity.js?20180913"></script>
    <script type="text/javascript" src="/woa/js/entry/sp/form1/jq.bxslider_for_horizscrl_form1.js?20190919"></script>
    <script type="text/javascript" src="/woa/js/entry/sp/form1/validation_form1.js?20200706"></script>
    <script type="text/javascript" src="/woa/js/entry/sp/form1/horizontal_scroll_form1.js?20180109"></script>
    <script type="text/javascript" src="/woa/js/entry/sp/form1/modal_footer1.js?20181019"></script>
    <link href="{{addQuery('/woa/css/entry/sp/style1.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
@include('common._gtag')

    <link rel="icon" href="/woa/favicon.ico">
</head>
<body class="step1">
    <div class="header">
        <img src="/woa/images/logo.png" class="header-logo" alt="ウィルワン">
    </div>
    <div class="contents">
    <div class="form">
        <h1><img src="/woa/entry/img/title01.png" alt="人気の非公開求人をご紹介！"></h1>
        <form id="form" name="form1" action="@if (preg_match('/jinzaibank.com/', $_SERVER['HTTP_HOST'])){{ route('EntryFin')}}@else{{ route('EntryFinFromOld')}}@endif" method="post">

@include('common._hidden_entry')

          <div id="dialog_form" class="formContent" data-initialstate="false">
            <div id="dialog_header" class="formHeader">
              <div class="row rowTable">
                <div class="col last"><img id="form_status" data-page-num="1" src="/woa/entry/img/1.png"></div>
              </div>
            </div>
            <div id="dialog_content" style="clear : both;">
            <ul class="bxslider">

                <li style="float: left; list-style: none; position: relative; width: 530px;">
                    <div class="partial_form formBody" id="Step1" style="visibility: visible;">
                        <h3 class="formTitle">どの資格でお探しですか？<br class="sp-only"><span>（複数選択OK）</span></h3>
                        <div id="license_container" class="formItem">
                            <div class="row row3col">
                                @foreach($licenseList as $value)
                                <div class="col @if(@in_array($value->id, $license)) checked @endif">
                                    <input type="checkbox" name="license[]" id="license_{{$value->id}}" value="{{$value->id}}" class="checkboxCol" @if(@in_array($value->id, $license)) checked="checked" @endif >
                                    <label for="license_{{$value->id}}" class="checkbox">
                                        <span>{{$value->license}}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
@if($errors->has('license'))
                            <div class="error_message errorBox" id="license_errmsg" style="display: block;">{{ $errors->first('license') }}</div>
@else
                            <div class="error_message errorBox" id="license_errmsg"></div>
@endif
                        </div>
                        <div id="graduation_year_area" style="display:@if($student) block; @else none; @endif;">
                            卒業年：<select name="graduation_year" id="graduation_year" class="select02">
                                <option value="">選択してください</option>
                                @foreach($graduationYearList as $key => $value)
                                <option label="{{ $value }}" value="{{ $key }}" @if(old('graduation_year') == $key) selected @endif>{{ $value }}</option>
                                @endforeach
                            </select>
@if($errors->has('graduation_year'))
                            <div class="error_message errorBox" id="graduation_year_errmsg" style="display: block;">{{ $errors->first('graduation_year') }}</div>
@else
                            <div class="error_message errorBox" id="graduation_year_errmsg" style="display: none;"></div>
@endif
                        </div>
                    </div>
                </li>{{-- /STEP1 --}}

                <li style="float: left; list-style: none; position: relative; width: 530px;">
                    <div class="partial_form formBody" id="Step2" style="visibility: visible;">
                        <h3 class="formTitle">ご希望の働き方・時期を選択してください。<br><span>（1つ選択）</span></h3>
                        <div id="req_emp_types_selection" class="formItem">
                            <div class="row row3col s3Col">
                            @foreach ($reqEmpTypeList as $value)
                                <div class="col col01 @if(@in_array($value->id, $req_emp_type)) checked @endif">
                                <input type="radio" value="{{$value->id}}" id="req_emp_type_{{$loop->iteration}}" name="req_emp_type[]" class="radioCol"@if(@in_array($value->id, $req_emp_type)) checked checked="checked" @endif disabled="disabled">
                                <label for="req_emp_type_{{$loop->iteration}}" class="radio row01">{!! nl2br($value->emp_type_br) !!}</label>
                                </div>
                            @endforeach
                            </div>
@if($errors->has('req_emp_type'))
                            <div id="req_emp_type_errmsg" class="error_message errorBox" style="display: block;">{{ $errors->first('req_emp_type') }}</div>
@else
                            <div id="req_emp_type_errmsg" class="error_message errorBox" style="display: none;"></div>
@endif

                            <div id="req_emp_type_errmsg" class="error_message errorBox"></div>
                        </div>
                        <div class="formItem ">
                            <h4 class="itemTitle"><span class="required">必須</span></h4>
                            <div class="col255">
                                <select name="req_date" id="req_date" disabled="disabled">
                                    <option value="">選択してください</option>
                                @foreach ($req_dateList as $value)
                                    <option label="{{$value->req_date}}" value="{{$value->id}}" @if($value->id == $req_date) selected @endif>{{$value->req_date}}</option>
                                @endforeach
                                </select>
@if($errors->has('req_date'))
                                <div id="req_date_errmsg" class="error_message errorBox" style="left: 0px; display: block;">{{ $errors->first('req_date') }}</div>
@else
                                <div id="req_date_errmsg" class="error_message errorBox" style="left: 0px; display: none;"></div>
@endif
                            </div>
                        </div>
                    </div>
                </li>{{-- /STEP2 --}}

                <li style="float: left; list-style: none; position: relative; width: 530px;">
                    <div class="partial_form formBody" id="Step3" style="visibility: visible;">
                        <h3 class="formTitle"><span class="required">必須</span><span>郵便番号を入力してください。</span></h3>
                        <div id="zip_area" class="formItem addr">
                            <div class="offset42">
                                <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
                                <input type="text" value="{{old('zip')}}" name="zip" id="zip" style="ime-mode: disabled;" class="width150zip col150" placeholder="例：〒1234567" disabled="disabled" maxlength="7"/>
                            </div>
@if($errors->has('zip'))
                            <div id="zip_errmsg" class="error_message errorBox err_zip" style="display: block;">{{ $errors->first('zip') }}</div>
@else
                            <div id="zip_errmsg" class="error_message errorBox err_zip" style="display: none;"></div>
@endif
                            <div style="margin-top: 10px;">
                                <span class="col btn_area">
                                    <p id="zip2"><small>郵便番号がわからない場合はコチラ</small></p>
                                </span>
                            </div>
                            <p id="addr_txt_area" class="addTxt">お住まいを中心に近隣の非公開求人をお届けいたします。 <br>希望勤務エリアがある方は、ご登録後に設定可能です。</p>
                            <div id="addr_area" class="acoArea" style="display: none;">
                                <div class="row3col">
                                    <h4 class="itemTitle"><span class="required">必須</span></h4>
                                    <div class="col150">
                                        <select name="addr1" id="addr1" class="width180 selectElem" style="ime-mode:active;" disabled="disabled">
                                            <option value="">選択してください</option>
                                            @foreach ($prefectureList as $value)
                                            <option label="{{$value->addr1}}" value="{{$value->id}}" @if($value->id == $addr1) selected @endif >{{$value->addr1}}</option>
                                            @endforeach
                                        </select>
                                        <div id="addr1or2_errmsg0" class="errorBox error_message err_pos01" @if($errors->has('addr1')) style="display: block;" @endif >{{ $errors->first('addr1') }}</div>
                                    </div>
                                    <div class="col215">
                                        <h4 class="itemTitle" id="itemTitle_addr2"><span class="required">必須</span></h4>
                                        <div class="col295">
                                            <select name="addr2" id="addr2" class="width180 selectElem" disabled="disabled">
                                                <option value="">選択して下さい</option>
                                                @foreach ($cityList as $value)
                                                <option label="{{$value->addr2}}" value="{{$value->id}}" @if($value->id == $addr2) selected @endif>{{$value->addr2}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" id="form_addr2" value="{{old('addr2')}}">
                                        </div>
                                    </div>
                                    <div id="addr1or2_errmsg1" class="errorBox error_message err_pos02" @if($errors->has('addr2')) style="display: block;" @endif >{{ $errors->first('addr2') }}</div>
                                </div>

                                <div id="" class="formItem box_pos">
                                    <div>
                                        <h4 class="itemTitle"><span class="required2">任意</span></h4>
                                        <input type="text" name="addr3" value="{{ old('addr3')}}" id="addr3" class="width175_err width_87" style="ime-mode: active;" placeholder="例：1-2-3　AAマンション101" disabled="disabled" maxlength="255"/>
                                    </div>
                                    <div id="addr3_errmsg" class="error_message errorBox" @if($errors->has('addr3')) style="display: block;" @endif >{{ $errors->first('addr3') }}</div>
                                </div>
                            </div>
                        </div>{{-- /zip_area --}}
                    </div>{{-- /Step3 --}}
                </li>{{--/STEP3--}}

                <li style="float: left; list-style: none; position: relative; width: 530px;">
                    <div class="partial_form formBody" id="Step4" style="visibility: visible;">
                        <h3 class="formTitle">お名前・お生まれの年を入力してください。</h3>
                        <div class="formItem">
                            <h4 class="itemTitle"><span class="required">必須</span></h4>
                            <div class="col255">
                                <input type="text" size="20" name="name_kan" id="name_kan" value="{{ old('name_kan')}}" placeholder="例：整体花子" disabled="disabled" maxlength="64"/>
                            </div>
                            <div class="error_message errorBox02" id="name_kan_errmsg" @if($errors->has('name_kan')) style="display: block;" @endif>{{ $errors->first('name_kan') }}</div>
                        </div>
                        <div class="formItem">
                            <h4 class="itemTitle"><span class="required">必須</span></h4>
                            <div class="col255">
                                <input type="text" size="20" name="name_cana" id="name_cana" value="{{ old('name_cana')}}" placeholder="例：せいたいはなこ" disabled="disabled" maxlength="64"/>
                            </div>
                            <div class="error_message errorBox02" id="name_cana_errmsg" @if($errors->has('name_cana')) style="display: block;" @endif>{{ $errors->first('name_cana') }}</div>
                        </div>
                        <div id="" class="formItem">
                            <h4 class="itemTitle"><span class="required">必須</span></h4>
                            <div class="col255">
                                <select id="birth_year" name="birth_year" class="width180 selectElem" disabled="disabled">
                                    <option value="" selected="selected">選択してください</option>
                                    @foreach($birthYearList as $key => $value)
                                        <option label="{{$value}}" value="{{$key}}" @if(old('birth_year')==$key) selected @endif>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="error_message errorBox02" id="birth_year_errmsg" @if($errors->has('birth_year')) style="display: block;" @endif>{{ $errors->first('birth_year') }}</div>
                        </div>
                    </div>{{-- /Step4 --}}
                </li>{{-- /STEP4 --}}

                <li style="float: left; list-style: none; position: relative; width: 530px;">
                    <div class="partial_form formBody" id="Step5" style="visibility: visible;">
                        <h3 class="formTitle">退職意向・ご連絡先を入力してください。</h3>
                        <div class="formItem">
                            <h4 class="itemTitle"><span class="required">必須</span></h4>
                            <div class="col255">
                                <select name="retirement_intention" id="retirement_intention">
                                    <option label="" value="">選択してください</option>
                                    @foreach($retirement_intentionList as $key => $value)
                                    <option label="{{$value}}" value="{{$key}}" @if(old('retirement_intention')==$key) selected @endif>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="error_message errorBox02 err_pos03" id="retirement_intention_errmsg" @if($errors->has('retirement_intention')) style="display: block;" @endif>{{ $errors->first('retirement_intention') }}</div>
                        </div>
                        <div class="formItem">
                            <h4 class="itemTitle"><span class="required2">任意</span></h4>
                            <div class="col255">
                                <input id="mob_mail" name="mail" type="email" size="22" value="{{ old('mob_mail')}}" maxlength="80" style="ime-mode: disabled;" placeholder="例：aaa@aaa.ne.jp(任意)" disabled="disabled"/>
                                <div id="suggest" style="display:none;" tabindex="-1"></div>
                            </div>
                            <div class="error_message errorBox02 err_pos03" id="mob_mail_errmsg" @if($errors->has('mob_mail')) style="display: block;" @endif>{{ $errors->first('mob_mail') }}</div>
                        </div>
                        <div class="formItem">
                            <h4 class="itemTitle"><span class="required">必須</span></h4>
                            <div class="col255">
                                <input id="mob_phone" name="mob_phone" type="tel" value="{{ old('mob_phone')}}" style="ime-mode: disabled;" size="14" placeholder="例：09012345678" disabled="disabled" maxlength="11"/>
                            </div>
                            <div class="error_message errorBox02 err_pos03" id="mob_phone_errmsg" @if($errors->has('mob_phone')) style="display: block;" @endif>{{ $errors->first('mob_phone') }}</div>
                        </div>

                        {{-- HUBSPOT連携のため、submmitをjsからではなく、HTMLから発火させる必要があるので、非表示項目、非表示ボタン配置 --}}
                        <div style="display: none">
                            <label for="expected_qualification_anma">取得資格(予定)_あん摩マッサージ指圧師<input type="text" id="expected_qualification_anma" name="expected_qualification_anma" value=""></label>
                            <label for="expected_qualification_jyusei">取得資格(予定)_柔道整復師<input type="text" id="expected_qualification_jyusei" name="expected_qualification_jyusei" value=""></label>
                            <label for="expected_qualification_sinkyu">取得資格(予定)_鍼灸師<input type="text" id="expected_qualification_sinkyu" name="expected_qualification_sinkyu" value=""></label>
                            <label for="state2">都道府県</label><input type="text" id="state2" name="state2" value="">
                            <label for="graduation_year_woa">卒業年度（WOA）</label><input type="text" id="graduation_year_woa" name="graduation_year_woa" value="">
                            <button type="submit" id="btn-submmit"></button>
                        </div>

                    </div>{{-- /Step5 --}}
                </li>{{-- /STEP5 --}}
                <li style="float: left; list-style: none; position: relative; width: 530px;">
                  <div class="partial_form formBody" id="dialog_page6" style="visibility: visible;"><br>
                    <div align="center"><br>
                      <br>
                      <br>
                      <span class="message"><!--  登録処理中です・・・ --></span></div>
                  </div>
                </li>
            </ul>
            </div>
          </div>
        </form>
    </div>
    </div>

    <footer>
        <div class="pMark"><a href="https://privacymark.jp/" target="_blank" onclick="$.sendGA(1, 'privacymark');" id="privacymark"><img src="/woa/img/pmark.gif" alt="プライバシーマーク" class="mar_rm"></a><b>安心してご登録いただくために</b><br>ウィルワンエージェントを運営する(株)エス・エム・エスはプライバシーマークを取得しております。</div>
        @include('sp.contents.include._modal_footer')
    </footer>
<!-- adwordsリマケタグ -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 822859503;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/822859503/?guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- /adwordsリマケタグ -->
@include('common._common_tag')
</body>
<script>
$( document ).ready(function( $ ) {
    $(window).load(function(){
        $('input').on('change',function () {
            $('input:checkbox:checked').parent().addClass('checked');
            $('input:radio:checked').parent().addClass('checked');
            $('input:not(:checked)').parent().removeClass('checked');
        });
    });
});
</script>
<script>
$(function() {
    $.fn.autoKana('#name_kan', '#name_cana', {
        katakana : false  //true：カタカナ、false：ひらがな（デフォルト）
    });
});
</script>
</html>
