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
    <script type="text/javascript" src="/woa/js/entry/sp/form2/jq.bxslider_for_horizscrl_form.js?20190919"></script>
    <script type="text/javascript" src="/woa/js/entry/sp/form2/validation_form.js?20200706"></script>
    <script type="text/javascript" src="/woa/js/entry/sp/form2/horizontal_scroll_form.js?20"></script>
    <script type="text/javascript" src="/woa/js/entry/sp/form2/modal_footer.js"></script>
	<link href="{{addQuery('/woa/css/entry/sp/style2.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
@include('common._gtag')

	<link rel="icon" href="/woa/favicon.ico">
</head>
<body class="step1">
    <div class="contents">
        <form id="form" name="form1" action="@if (preg_match('/jinzaibank.com/', $_SERVER['HTTP_HOST'])){{ route('EntryFin')}}@else{{ route('EntryFinFromOld')}}@endif" method="post">
            <input type="hidden" name="t" value="{{ $t }}">
            <input type="hidden" name="action" id="action" value="{{ $action }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="birth_day" value="1">
            <input type="hidden" name="birth_month" value="1">
            <input type="hidden" name="entry_order" value="{{ $entry_order }}">
            <input type="hidden" name="entry_category_manual" value="">
            <input type="hidden" name="job_id" value="{{ $job_id }}">
            <input type="hidden" name="agreement_flag" value="1">

          <div id="dialog_form" data-initialstate="false">
            <div id="dialog_header">
				<h1><img src="/woa/images/logo.png" class="header-logo" alt="ウィルワン"></h1>
				<p>ハローワーク非掲載も多数！新着・会員限定のレア求人をご紹介！</p>
				<ul class="status" data-page-num="1">
					<li>1.資格選択</li>
					<li>2.時期と働き方</li>
					<li>3.通勤エリア</li>
					<li>4.名前と生年</li>
					<li>5.お仕事状況</li>
				</ul>
            </div>
            <div id="dialog_content">
				<!-- div class="hands"></div -->
				<ul class="bxslider">

					<li>
						<div id="Step1" style="visibility: visible;">
							<p class="intro">求人情報をお見せするため希望内容を教えてください</p>
							<div id="license_area">
								<h2>どの資格でお探しですか？</h2>
								<ul class="formItem">
									@foreach($licenseList as $value)
									<li class="col @if(@in_array($value->id, $license)) checked @endif">
										<input type="checkbox" name="license[]" id="license_{{$value->id}}" value="{{$value->id}}" class="checkboxCol" @if(@in_array($value->id, $license)) checked="checked" @endif >
										<label for="license_{{$value->id}}" class="checkbox">
											<span>{{$value->license}}</span>
										</label>
									</li>
									@endforeach
								</ul>
								@if($errors->has('license'))
								<div class="error_messageF" id="license_errmsg" style="display: block;">{{ $errors->first('license') }}</div>
								@else
								<div class="error_message" id="license_errmsg"></div>
								@endif
							</div>
							<div id="graduation_year_area" style="display:@if($student) block; @else none; @endif;">
								<h3>卒業年：</h3>
								<select name="graduation_year" id="graduation_year">
									<option value="">選択してください</option>
									@foreach($graduationYearList as $key => $value)
									<option label="{{ $value }}" value="{{ $key }}" @if(old('graduation_year') == $key) selected @endif>{{ $value }}</option>
									@endforeach
								</select>
								@if($errors->has('graduation_year'))
								<div class="error_message" id="graduation_year_errmsg" style="display: block;">{{ $errors->first('graduation_year') }}</div>
								@else
								<div class="error_message" id="graduation_year_errmsg" style="display: none;"></div>
								@endif
							</div>
						</div>
					</li>{{-- /STEP1 --}}

					<li>
						<div id="Step2" style="visibility: visible;">
							<div id="req_emp_types_area">
								<h2>ご希望の働き方</h2>
								<ul class="formItem">
								@foreach ($reqEmpTypeList as $value)
									<li class="col @if(@in_array($value->id, $req_emp_type)) checked @endif">
									<input type="radio" value="{{$value->id}}" id="req_emp_type_{{$loop->iteration}}" name="req_emp_type[]" @if(@in_array($value->id, $req_emp_type)) checked checked="checked" @endif disabled="disabled">
									<label for="req_emp_type_{{$loop->iteration}}" class="radio">{!! nl2br($value->emp_type_br) !!}</label>
									</li>
								@endforeach
								</ul><!-- end formItem -->
								@if($errors->has('req_emp_type'))
								<div id="req_emp_type_errmsg" class="error_message" style="display: block;">{{ $errors->first('req_emp_type') }}</div>
								@else
								<div id="req_emp_type_errmsg" class="error_message" style="display: none;"></div>
								@endif
								<div id="req_emp_type_errmsg" class="error_message"></div>
							</div><!-- end_emp_types_selection -->
							<div id="req_date_area">
								<h2>転職時期</h2>
								<div class="formItem">
								<select name="req_date" id="req_date" disabled="disabled">
									<option value="">選択してください</option>
								@foreach ($req_dateList as $value)
									<option label="{{$value->req_date}}" value="{{$value->id}}" @if($value->id == $req_date) selected @endif>{{$value->req_date}}</option>
								@endforeach
								</select>
								</div>
								@if($errors->has('req_date'))
								<div id="req_date_errmsg" class="error_message" style="display: block;">{{ $errors->first('req_date') }}</div>
								@else
								<div id="req_date_errmsg" class="error_message" style="display: none;"></div>
								@endif
							</div><!-- end req_date_area // formItem -->
						</div>
					</li>{{-- /STEP2 --}}

					<li>
						<div id="Step3" style="visibility: visible;">
							<div id="zip_area">
								<div class="formItem">
								<h3>お住まいの<br>郵便番号</h3>
									<span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
									<input type="text" value="{{old('zip')}}" name="zip" id="zip" style="ime-mode: disabled;" class="width150zip" placeholder="例：〒1234567" disabled="disabled" maxlength="7"/>
								</div>
								<div class="error_message" id="zip_errmsg" @if($errors->has('zip')) style="display: block;" @endif>{{ $errors->first('zip') }}</div>

                                <div id="zip2">
    								<div id="zip3"><small>郵便番号がわからない場合はコチラ</small></div>
                                    <p id="addr_txt_area" class="addTxt">お住まいを中心に近隣の非公開求人をお届けいたします。 <br>希望勤務エリアがある方は、ご登録後に設定可能です。</p>
                                    <div id="addr_area" class="acoArea" style="display: none;">
										<h2>住所</h2>
										<div class="formItem">
											<select name="addr1" id="addr1" style="ime-mode:active;" disabled="disabled">
												<option value="">選択してください</option>
												@foreach ($prefectureList as $value)
												<option label="{{$value->addr1}}" value="{{$value->id}}" @if($value->id == $addr1) selected @endif >{{$value->addr1}}</option>
												@endforeach
											</select>
											<select name="addr2" id="addr2" disabled="disabled">
												<option value="">選択して下さい</option>
												@foreach ($cityList as $value)
												<option label="{{$value->addr2}}" value="{{$value->id}}" @if($value->id == $addr2) selected @endif>{{$value->addr2}}</option>
												@endforeach
											</select>
										</div><!-- end formItem -->
										<input type="hidden" id="form_addr2" value="{{old('addr2')}}">
										<div id="addr1or2_errmsg0" class="errorBox error_message err_pos01" @if($errors->has('addr1')) style="display: block;" @endif >{{ $errors->first('addr1') }}</div>
										<div id="addr1or2_errmsg1" class="errorBox error_message err_pos02" @if($errors->has('addr2')) style="display: block;" @endif >{{ $errors->first('addr2') }}</div>

										<h2>番地建物名</h2>
										<div id="" class="formItem">
											<input type="text" name="addr3" value="{{ old('addr3')}}" id="addr3" class="width175_err width_87" style="ime-mode: active;" placeholder="例：1-2-3　AAマンション101" disabled="disabled" maxlength="255"/>
										</div>
										<div id="addr3_errmsg" class="error_message" @if($errors->has('addr3')) style="display: block;" @endif >{{ $errors->first('addr3') }}</div>

										</div><!-- end aco area -->
									</div>
							</div>{{-- /zip_area --}}
						</div>{{-- /Step3 --}}
					</li>{{--/STEP3--}}

					<li>
						<div id="Step4" style="visibility: visible;">
							<p class="key"><small>公開されません</small></p>
							<h2>お名前</h2>
							<input type="text" size="20" name="name_kan" id="name_kan" value="{{ old('name_kan')}}" placeholder="例：整体花子" disabled="disabled" maxlength="64"/>
							<div class="error_message" id="name_kan_errmsg" @if($errors->has('name_kan')) style="display: block;" @endif>{{ $errors->first('name_kan') }}</div>
							<input type="text" size="20" name="name_cana" id="name_cana" value="{{ old('name_cana')}}" placeholder="例：せいたいはなこ" disabled="disabled" maxlength="64"/>
							<div class="error_message" id="name_cana_errmsg" @if($errors->has('name_cana')) style="display: block;" @endif>{{ $errors->first('name_cana') }}</div>

							<h2>生まれ年</h2>
							<select id="birth_year" name="birth_year" class="width180 selectElem" disabled="disabled">
								<option value="" selected="selected">選択してください</option>
								@foreach($birthYearList as $key => $value)
									<option label="{{$value}}" value="{{$key}}" @if(old('birth_year')==$key) selected @endif>{{$value}}</option>
								@endforeach
							</select>
							 <div class="error_message" id="birth_year_errmsg" @if($errors->has('birth_year')) style="display: block;" @endif>{{ $errors->first('birth_year') }}</div>
						</div>{{-- /Step4 --}}
					</li>{{-- /STEP4 --}}

					<li>
						<div id="Step5" style="visibility: visible;">
							<p class="key"><small>公開されません</small></p>
							<h2>携帯番号</h2>
							 <input id="mob_phone" name="mob_phone" type="tel" value="{{ old('mob_phone')}}" style="ime-mode: disabled;" size="14" placeholder="例：09012345678" disabled="disabled" maxlength="11"/>
							 <div class="error_message" id="mob_phone_errmsg" @if($errors->has('mob_phone')) style="display: block;" @endif>{{ $errors->first('mob_phone') }}</div>

							<h2>お仕事のご状況</h2>
							<select name="retirement_intention" id="retirement_intention">
								<option label="" value="">選択してください</option>
								@foreach($retirement_intentionList as $key => $value)
								<option label="{{$value}}" value="{{$key}}" @if(old('retirement_intention')==$key) selected @endif>{{$value}}</option>
								@endforeach
							</select>
							<div class="error_message" id="retirement_intention_errmsg" @if($errors->has('retirement_intention')) style="display: block;" @endif>{{ $errors->first('retirement_intention') }}</div>

							<h2>メールアドレス<span class="optional">任意</span></h2>
							<input id="mob_mail" name="mail" type="email" size="22" value="{{ old('mob_mail')}}" maxlength="80" style="ime-mode: disabled;" placeholder="例：aaa@aaa.ne.jp(任意)" disabled="disabled">
							<div id="suggest" style="display:none;" tabindex="-1"></div>
							<div class="error_message" id="mob_mail_errmsg" @if($errors->has('mob_mail')) style="display: block;" @endif>{{ $errors->first('mob_mail') }}</div>

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

					<li>
					  <div id="dialog_page6" style="visibility: visible;"><br>
						<div align="center"><br>
						  <br>
						  <br>
						  <span class="message"><!--  登録処理中です・・・ --></span></div>
					  </div>
					</li>
				</ul>
            </div>
		</div><!-- end dialog_form -->
    </form>
</div><!-- end contents -->

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
