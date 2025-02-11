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
    <script type="text/javascript" src="/woa/js/common/ga_common.js?20221206"></script>
    <script src="{{addQuery('/woa/js/common/ga4_common.js')}}"></script>
    <script type="text/javascript" src="/woa/js/common/SearchCity/jquery.SearchCity.js?20180913"></script>
    <script type="text/javascript" src="/woa/entry/pc/form3008/js/jq.bxslider_for_horizscrl_form.js?20211207"></script>
    <script type="text/javascript" src="/woa/entry/pc/form3008/js/validation_form.js?20211207"></script>
    <script type="text/javascript" src="{{addQuery('/woa/entry/pc/form3008/js/horizontal_scroll_form.js')}}"></script>
    <script src="{{addQuery('/woa/entry/pc/form3008/js/pc_modal_browserback.js')}}"></script>
    <script src="{{addQuery('/woa/js/common/enterBlock.js')}}"></script>
    <link href="{{addQuery('/woa/entry/pc/form3008/css/style.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
@include('common._gtag')

    <link rel="icon" href="/image_file/kurohon.ico">
</head>
<body class="step1">
<div class="wrapper">
    <div class="main">
    <div class="header">
        <img src="/woa/entry/pc/form3008/img/logo.svg" class="header-logo" alt="国試黒本">
    </div>
    <div class="header-description">就職支援だけでなく、<span class="font-red">国家試験</span>、<span class="font-red">就職フェア</span>まで幅広く<span class="font-blue">治療家学生</span>を支援！</div>
    <div class="contents">
    <div class="form">
@include('common._error_message')
        <form id="form" name="form2" action="@if (preg_match('/jinzaibank.com/', $_SERVER['HTTP_HOST'])){{ route('EntryFin')}}@else{{ route('EntryFinFromOld')}}@endif" method="post">

@include('common._hidden_entry')
          <input type="hidden" name="thanks_lp" value="1">
          <div id="dialog_form" class="formContent" data-initialstate="false">
            <div id="dialog_header" class="formHeader">
              <div class="row rowTable">
                <div class="last">
                    <img id="form_status" data-page-num="1" src="/woa/entry/pc/form3008/img/1.png">
                </div>
              </div>
            </div>
            @if(!empty($office_name) || (!empty($addr2_name) && !empty($business)))
            <p id="job_text" class="job-text">{{$office_name ?? (($addr2_name ?? '') . ($business ?? ''))}}<br>の詳しい情報をお届けします。</p>
            @endif
            <div id="dialog_content" style="clear : both;">
            <ul class="bxslider">

                <li style="float: left; list-style: none; position: relative; width: 530px;">
                    <div class="partial_form formBody" id="Step1" style="visibility: visible;">

                        <div class="labelHeading">
                            <h2 class="itemTitle">どんな資格をお持ちですか？<small>（複数選択可）</small></h2>
                        </div>

                        <div id="license_container" class="formItem">
                            <div class="row row3col">
                                @foreach($licenseList as $key => $value)
                                    @if($key === 4)
                                        <div class="license-heading-center">
                                            <h3 class="license-heading">学生の方はこちら</h3>
                                        </div>
                                        <div class="other-licenses">
                                    @endif
                                    <div class="col @if(@in_array($value->id, $license)) checked @endif">
                                    <input type="checkbox" name="license[]" id="license_{{$value->id}}" value="{{$value->id}}" class="checkboxCol" @if(@in_array($value->id, $license)) checked="checked" @endif >

                                        <label for="license_{{$value->id}}" class="checkbox">
                                            <div class="col-img-grid">
                                                <div class="col-img-box col-img-{{ $value->id }} @if($key >= 4) col-img-student @endif"></div>
                                                <span>{{$value->license}}</span>
                                            </div>
                                        </label>
                                    </div>
                                    @if($key === count($licenseList) - 1 && $key >= 4)
                                        </div>
                                    @endif
                                @endforeach
                            </div>
@if($errors->has('license'))
                            <div class="error_message errorBox" id="license_errmsg" style="display: block;">{{ $errors->first('license') }}</div>
@else
                            <div class="error_message errorBox" id="license_errmsg"></div>
@endif
                        </div>

                        <div id="license_container" class="formItem">
                            <div id="graduation_year_area" style="display:@if($student) block; @else none; @endif;">

                                <div class="labelHeading">
                                    <h2 class="itemTitle">卒業年</h2>
                                </div>

                                <div class="formBgWhite">
                                    <select name="graduation_year" id="graduation_year" class="select02">
                                        <option value="">選択してください</option>
                                        @foreach($graduationYearList as $key => $value)
                                        <option label="{{ $value }}" value="{{ $key }}" @if(old('graduation_year') == $key) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
    @if($errors->has('graduation_year'))
                                <div class="error_message errorBox" id="graduation_year_errmsg" style="display: block;">{{ $errors->first('graduation_year') }}</div>
    @else
                                <div class="error_message errorBox" id="graduation_year_errmsg" style="display: none;"></div>
    @endif
                            </div>
                        </div>
                    </div>
                </li>{{-- /STEP1 --}}

                <li style="float: left; list-style: none; position: relative; width: 530px;">
                    <div class="partial_form formBody" id="Step2" style="visibility: visible;">

                        <div class="labelHeading">
                            <h2 class="itemTitle">ご希望の働き方</h2>
                        </div>

                        <div id="req_emp_types_selection" class="formItem">
                            <div class="row row3col">
                            @foreach ($reqEmpTypeList as $value)
                                <div class="col col01 @if(@in_array($value->id, $req_emp_type)) checked @endif">
                                <input type="radio" value="{{$value->id}}" id="req_emp_type_{{$loop->iteration}}" name="req_emp_type[]" class="radioCol"@if(@in_array($value->id, $req_emp_type)) checked="checked" @endif disabled="disabled">
                                <label for="req_emp_type_{{$loop->iteration}}" class="radio row01">
                                    <div class="col-img-grid">
                                        <div class="col-img-box col-img-{{ $value->id }}"></div>
                                        <span>{!! nl2br($value->emp_type_br) !!}</span>
                                    </div>
                                </label>
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
                        <div class="formItem mt30">
                            <div class="labelHeading">
                                <h2 class="itemTitle">いつ頃の求人をお探しですか？</h2>
                            </div>

                            <div class="formBgWhite mt15">
                                <select name="req_date" id="req_date" disabled="disabled" class="select02">
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

                        <div class="labelHeading">
                            <h2 class="itemTitle">お住まいの郵便番号</h2>
                        </div>

                        <div id="zip_area" class="formItem addr">
                            <div class="offset42">
                                <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
                                <div class="zip-box tag-required">
                                    <input type="text" value="{{old('zip')}}" name="zip" id="zip" style="ime-mode: disabled;" class="" placeholder="例：〒1234567" disabled="disabled" maxlength="7"/>
                                </div>
                            </div>
@if($errors->has('zip'))
                            <div id="zip_errmsg" class="error_message errorBox err_zip" style="display: block;">{{ $errors->first('zip') }}</div>
@else
                            <div id="zip_errmsg" class="error_message errorBox err_zip" style="display: none;"></div>
@endif
                            <p id="addr_txt_area" class="addTxt">お住まいを中心に近隣の非公開求人をお届けいたします。 <br>希望勤務エリアがある方は、ご登録後に設定可能です。</p>
                            <div id="addr_area" class="acoArea" style="display: none;">
                                <div class="">
                                    <div class="add-box tag-required">
                                        <select name="addr1" id="addr1" class="width180 selectElem" style="ime-mode:active;" disabled="disabled">
                                            <option value="">選択してください</option>
                                            @foreach ($prefectureList as $value)
                                            <option label="{{$value->addr1}}" value="{{$value->id}}" @if($value->id == $addr1) selected @endif >{{$value->addr1}}</option>
                                            @endforeach
                                        </select>
                                        <div id="addr1or2_errmsg0" class="errorBox error_message err_pos01" @if($errors->has('addr1')) style="display: block;" @endif >{{ $errors->first('addr1') }}</div>
                                    </div>
                                    <div class="add-box tag-required">
                                        <select name="addr2" id="addr2" class="width180 selectElem" disabled="disabled">
                                            <option value="">選択して下さい</option>
                                            @foreach ($cityList as $value)
                                            <option label="{{$value->addr2}}" value="{{$value->id}}" @if($value->id == $addr2) selected @endif>{{$value->addr2}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="form_addr2" value="{{old('addr2')}}">
                                        <div id="addr1or2_errmsg1" class="errorBox error_message err_pos02" @if($errors->has('addr2')) style="display: block;" @endif >{{ $errors->first('addr2') }}</div>
                                    </div>
                                    <div class="add-box tag-any">
                                        <input type="text" name="addr3" value="{{ old('addr3')}}" id="addr3" class="" style="ime-mode: active;" placeholder="例：1-2-3　AAマンション101" disabled="disabled" maxlength="255"/>
                                        <div id="addr3_errmsg" class="error_message errorBox" @if($errors->has('addr3')) style="display: block;" @endif >{{ $errors->first('addr3') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>{{-- /zip_area --}}

                        <div class="btn_area">
                            <p id="zip2">郵便番号がわからない場合はコチラ</p>
                        </div>

                        <div id="moving_flg_area" class="formItem">
                            <h3 class="formTitle">県外への転居はご検討されてますか？</h3>
                            <div class="moving_flg-box">
                                <div class="col col01 @if(old('moving_flg') == '可') checked @endif">
                                    <input type="radio" value="可" id="moving_flg_1" name="moving_flg" class="radioCol" @if(old('moving_flg') == '可') checked="checked" @endif disabled="disabled">
                                    <label for="moving_flg_1" class="radio row01">はい / 求人次第</label>
                                </div>
                                <div class="col col01 @if(old('moving_flg') == '否') checked @endif">
                                    <input type="radio" value="否" id="moving_flg_2" name="moving_flg" class="radioCol" @if(old('moving_flg') == '否') checked="checked" @endif disabled="disabled">
                                    <label for="moving_flg_2" class="radio row01">いいえ</label>
                                </div>
                            </div>
@if($errors->has('moving_flg'))
                            <div id="moving_flg_errmsg" class="error_message errorBox" style="display: block;">{{ $errors->first('moving_flg') }}</div>
@else
                            <div id="moving_flg_errmsg" class="error_message errorBox" style="display: none;"></div>
@endif
                        </div>
                    </div>{{-- /Step3 --}}
                </li>{{--/STEP3--}}

                <li style="float: left; list-style: none; position: relative; width: 530px;">
                    <div class="partial_form formBody" id="Step4" style="visibility: visible;">

                        <div class="labelHeading">
                            <h2 class="itemTitle">お名前</h2>
                        </div>

                        <div class="bgWhite mt15">
                            <div class="formItem mt0">
                                <h4 class="itemSubTitle">お名前</h4>
                                <div class="col255 tag-required">
                                    <input type="text" size="20" name="name_kan" id="name_kan" value="{{ old('name_kan')}}" placeholder="例：整体花子" disabled="disabled" maxlength="64"/>
                                </div>
                            </div>
                            <div class="error_message errorBox02" id="name_kan_errmsg" @if($errors->has('name_kan')) style="display: block;" @endif>{{ $errors->first('name_kan') }}</div>

                            <div class="formItem">
                                <h4 class="itemSubTitle">ふりがな</h4>
                                <div class="col255 tag-required">
                                    <input type="text" size="20" name="name_cana" id="name_cana" value="{{ old('name_cana')}}" placeholder="例：せいたいはなこ" disabled="disabled" maxlength="64"/>
                                </div>
                            </div>
                            <div class="error_message errorBox02" id="name_cana_errmsg" @if($errors->has('name_cana')) style="display: block;" @endif>{{ $errors->first('name_cana') }}</div>
                        </div>

                        <div class="labelHeading mt30">
                            <h2 class="itemTitle">生まれ年</h2>
                        </div>

                        <div class="bgWhite mt15">
                            <div id="" class="">
                                    <div class="colBirthYear tag-required">

                                    <select id="birth_year" name="birth_year" class="width180 selectElem" disabled="disabled">
                                        <option value="" selected="selected">選択してください</option>
                                        @foreach($birthYearList as $key => $value)
                                            <option label="{{$value}}" value="{{$key}}" @if(old('birth_year')==$key) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="error_message errorBox02" id="birth_year_errmsg" @if($errors->has('birth_year')) style="display: block;" @endif>{{ $errors->first('birth_year') }}</div>
                            </div>
                        </div>
                    </div>{{-- /Step4 --}}
                </li>{{-- /STEP4 --}}

                <li style="float: left; list-style: none; position: relative; width: 530px;">
                    <div class="partial_form formBody" id="Step5" style="visibility: visible;">

                        <div class="stepBart">
                            <p>この質問で<span>ラスト</span>です！</p>
                        </div>

                        <div class="labelHeading">
                            <h2 class="itemTitle">退職意向</h2>
                        </div>

                        <div class="bgWhite mt15">

                            <div class="formItem mt0">
                                <div class="col255 tag-required">
                                    <select name="retirement_intention" id="retirement_intention">
                                        <option label="" value="">選択してください</option>
                                        @foreach($retirement_intentionList as $key => $value)
                                        <option label="{{$value}}" value="{{$key}}" @if(old('retirement_intention')==$key) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="error_message errorBox02 err_pos03" id="retirement_intention_errmsg" @if($errors->has('retirement_intention')) style="display: block;" @endif>{{ $errors->first('retirement_intention') }}</div>
                            </div>

                        </div>

                        <div class="labelHeading mt30">
                            <h2 class="itemTitle">携帯番号・メールアドレス</h2>
                        </div>
                        <div class="bgWhite mt15">
                            <div class="formItem mt0">
                                <div class="col255 tag-required">
                                    <input id="mob_phone" name="mob_phone" type="tel" value="{{ old('mob_phone')}}" style="ime-mode: disabled;" size="14" placeholder="例：09012345678" disabled="disabled" maxlength="11"/>
                                </div>
                                <div class="error_message errorBox02 err_pos03" id="mob_phone_errmsg" @if($errors->has('mob_phone')) style="display: block;" @endif>{{ $errors->first('mob_phone') }}</div>
                            </div>

                            <div class="formItem formItem--mail">
                                <div class="col255 tag-any">
                                    <input id="mob_mail" name="mail" type="email" size="22" value="{{ old('mail')}}" maxlength="80" style= "ime-mode: disabled;" placeholder="メールアドレス(任意)" disabled="disabled"/>
                                    <div id="suggest" style="display:none;" tabindex="-1"></div>
                                </div>
                                <div class="error_message errorBox02 err_pos03" id="mob_mail_errmsg" @if($errors->has('mail')) style="display: block;" @endif>{{ $errors->first('mob_mail') }}</div>
                            </div>

                            <div class="modal willone-toha" style="display: none;">
                            <div class="modalBody" style="height: 50%;padding: 2em; box-sizing: border-box;">
                                <div class="scroll_box" id="tos" style="height: 80%;">
                                <div style="text-align: center">
                                    <h2>「ウィルワンエージェント」とは・・・</h2><br><br>
                                    国試黒本と同じ会社が運営する治療家専門の人材紹介サービスです。<br>国試黒本には掲載のない非公開求人があるため、求職者様の選択肢を広げることができます（ご利用は完全無料です）
                                </div>
                                </div>
                                <p class="close">×close</p>
                            </div>
                            <div class="modalBK"></div>
                            </div>
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

<!-- ブラウザバックモーダル -->
<div class="modal_banner" data-modal-banner="browser_back">
    <div class="modal_wrap">
        <div class="modal_wrap_inner">
            <section class="modal_wrap_inner_contents">
                <button type="button" class="modal_wrap_inner_contents_button" data-close-button="browser_back"><span></span></button>
                <p class="ex">このページを離れると、入力した内容が消えてしまいます。</p>
                <div class="browser_back_cont">
                    <div class="link_btn" data-close-button="browser_back"><span>入力に戻る</span></div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- /ブラウザバックモーダル -->

</div>
<footer>
    <div class="pMark"><a href="https://privacymark.jp/" target="_blank" onclick="$.sendGA(1, 'privacymark');" id="privacymark"><img src="/woa/img/pmark.gif" alt="プライバシーマーク" class="mar_rm"></a><b>安心してご登録いただくために</b><br>国試黒本を運営する(株)エス・エム・エスはプライバシーマークを取得しております。</div>
    @include('pc.contents.include._modal_footer')
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
</div>
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
