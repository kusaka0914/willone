<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
	<meta charset="utf-8">
	<meta name="robots" content="noindex,nofollow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
    @include('common._ogp')
	<title>{{ $headtitle }}</title>
	<link rel="stylesheet" type="text/css" href="/woa/css/entry/pc/style3.css?20210108">
	<link rel="stylesheet" type="text/css" href="/woa/css/melon.min.css">
	<script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="/woa/js/jquery.autoKana.js"></script>
	<script type="text/javascript" src="/woa/js/entry/pc/form3/jquery.validate_multiStepForm.js?20200706"></script>
	<script type="text/javascript" src="/woa/js/common/SearchCity/jquery.SearchCity.js?20181030"></script>
	<script type="text/javascript" src="/woa/js/entry/pc/form3/form.js?20181030"></script>
    <script src="{{addQuery('/woa/js/common/enterBlock.js')}}"></script>
@include('common._gtag')
	<link rel="icon" href="/woa/favicon.ico">
</head>
<body class="step1">
	<div class="header">
		<a href="{{ route('Top') }}" rel="follow"><img src="/woa/entry/img/logo.jpg" class="header-logo"></a>
	</div>
	<div class="mb80"></div>
	<div class="contentsArea">
		<a id="basePosition" name="basePosition"></a>
		<form id="form" name="form1" action="@if (preg_match('/jinzaibank.com/', $_SERVER['HTTP_HOST'])){{ route('EntryFin')}}@else{{ route('EntryFinFromOld')}}@endif" method="post" target="_self" enctype="application/x-www-url-encoded">
			<input type="hidden" name="t" value="{{ $t }}">
			<input type="hidden" name="action" id="action" value="{{ $action }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="birth_day" value="1">
			<input type="hidden" name="birth_month" value="1">
			<input type="hidden" name="entry_order" value="{{ $entry_order }}">
			<input type="hidden" id="form_addr2" value="{{ old('addr2') }}">

			<div class="formContents">
			<!-- 登録フォーム入力欄ここから　-->
@if (!empty($errors->all()))
				<div class="error_area">
					<div class="right_required">
						<p>入力内容に間違いがありますので、ご確認をお願いします。</p>
						<ul>
@foreach($errors->all() as $message)
							<li><span class="required_error">※{{ $message }}</span></li>
@endforeach
						</ul>
					</div><!-- /right_required -->
				</div><!-- /error_area -->
@endif

				<p class="noteTxt">※は必須項目です</p>
				<div class="commonForm" id="Step1">
					<div class="form">
						<div class="content">
							<table cellspacing="0" cellpadding="0" border="0">
								<tr id="name_kan_area" class="@if ($errors->has('name_kan'))err @endif">
									<th class="tit"><div class="melon-btn-flat-king melon-btn-block">お名前</div></th>
									<td class="essential">※</td>
									<td>
										<input id="name_kan" name="name_kan" type="text" value="{{ old('name_kan')}}" class="@if ($errors->has('name_kan'))nameInput_err @elseif (!$errors->has('name_kan') && !old('name_kan'))nameInput_err @else nameInput @endif" style="ime-mode:active;" id="name_kan" placeholder="例：整体花子" maxlength="64" />
										<div class="required_error" id="name_kan_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="name_cana_area" class="@if ($errors->has('name_cana'))err @endif">
									<th class="tit"><div class="melon-btn-flat-king melon-btn-block">ふりがな</div></th>
									<td class="essential">※</td>
									<td>
										<input type="text" id="name_cana" name="name_cana" value="{{ old('name_cana')}}" class="@if ($errors->has('name_cana'))nameInput_err @elseif (!$errors->has('name_cana') && !old('name_cana'))nameInput_err @else nameInput @endif" style="ime-mode:active;" id="name_cana" placeholder="例：せいたいはなこ" maxlength="64" />
										<div class="required_error" id="name_cana_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="birth_year_area" class="@if ($errors->has('birth') || $errors->has('birth_year'))err @endif">
									<th class="tit"><div class="melon-btn-flat-king melon-btn-block">生まれた年</div></th>
									<td class="essential">※</td>
									<td>
										<select id="birth_year" class="birth_year" name="birth_year">
											<option value="" selected="selected">選択</option>
@foreach($birthYearList as $key => $value)
											<option label="{{$value}}" value="{{$key}}" @if(old('birth_year')==$key) selected @endif>{{$value}}</option>
@endforeach
										</select>
										<div class="required_error" id="birth_year_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="zip_area" class="@if ($errors->has('zip'))err @endif noborder">
									<th class="tit" rowspan="4"><div class="melon-btn-flat-king melon-btn-block">お住まい</div></th>
									<td class="arbitrariness2 noborder">&nbsp;</td>
									<td>
										<span style="padding-right:2.5em;">郵便番号</span>
										 <input type="text" value="{{ old('zip') }}" name="zip" id="zip" style="ime-mode: disabled;" class="@if ($errors->has('zip'))zipInput_err @elseif (!$errors->has('name_zip') && !old('zip'))zipInput @else zipInput @endif" placeholder="例：1234567" maxlength="7" />
										<span style="color:#666666; font-size:10px;">&nbsp;&nbsp;入力すると住所が自動で表示されます</span>
										<div class="required_error" id="zip_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="addr1_area" class="@if ($errors->has('addr') || $errors->has('addr1'))err @endif">
									<td class="essential">※</td>
									<td>
										<span style="padding-right:2.5em;">都道府県</span>
										 <select name="addr1" id="addr1" class="selectAddr">
											<option value="" style="background-color: #FFDFDF;">選択してください</option>
@foreach ($prefectureList as $value)
											<option label="{{$value->addr1}}" value="{{$value->id}}" @if($value->id == $addr1) selected @endif >{{$value->addr1}}</option>
@endforeach
										</select>
										<div class="required_error" id="addr1_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="addr2_area" class="@if ($errors->has('addr') || $errors->has('addr2'))err @endif noborder">
									<td class="essential">※</td>
									<td>
										<span style="padding-right:2.5em;">市区町村</span>
										 <select name="addr2" id="addr2" class="selectAddr">
											<option value="" style="background-color: #FFDFDF;">選択してください</option>
@foreach ($cityList as $value)
											<option label="{{$value->addr2}}" value="{{$value->id}}" @if($value->id == $addr2) selected @endif>{{$value->addr2}}</option>
@endforeach
										</select>
										<div class="required_error" id="addr2_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="addr3_area" class="@if ($errors->has('addr') || $errors->has('addr3'))err @endif">
									<td class="essential">※</td>
									<td>
										<span>番地・建物名等</span>
										 <input type="text" name="addr3" value="{{ old('addr3') }}" id="addr3" class="@if ($errors->has('addr3'))addrInput_err @elseif (!$errors->has('addr3') && !old('addr3'))addrInput_err @else addrInput @endif" style="ime-mode: active;" placeholder="例：芝公園2-11-1 ABCマンション101" maxlength="255"/>
										<span style="color:#666; font-size:10px; padding-left:10px;">例：芝公園2-11-1 ABCマンション101</span><br />
										<div class="required_error" id="addr3_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="mob_phone_area" class="@if ($errors->has('mob_phone'))err @endif">
									<th class="tit" rowspan="2"><div class="melon-btn-flat-king melon-btn-block">ご連絡先</div></th>
									<td class="essential">※</td>
									<td>
										<span style="padding-right:2em;">携帯電話番号</span>
										 <input name="mob_phone" id="mob_phone" type="tel" value="{{ old('mob_phone') }}" class="@if ($errors->has('mob_phone'))mobileInput_err @elseif (!$errors->has('mob_phone') || !old('mob_phone'))mobileInput_err @else mobileInput @endif" style="ime-mode: disabled;" placeholder="例：09012345678" maxlength="11" />
										<div class="required_error" id="mob_phone_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="mob_mail_area" class="@if ($errors->has('mob_mail'))err @endif">
									<td class="essential"></td>
									<td>
										<span>携帯メールアドレス</span>
										 <input name="mail" id="mob_mail" type="email" size="22" value="{{ old('mob_mail') }}" class="@if ($errors->has('mob_mail'))mobileInput_err @else mobileInput @endif" maxlength="80" style="ime-mode: disabled;" placeholder="例：aaa@aaa.ne.jp(任意)" />
										<div class="required_error" id="mob_mail_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="license_area" class="@if ($errors->has('license'))err @endif">
									<th class="tit"><div class="melon-btn-flat-king melon-btn-block">保有資格</div></th>
									<td class="essential">※</td>
									<td id="license_container">
@foreach($licenseList as $value)
										<label for="license_{{$value->id}}">
											<input type="checkbox" name="license[{{$value->id}}]" id="license_{{$value->id}}" value="{{$value->id}}" @if(@in_array($value->id, $license)) checked="checked" @endif>
											 {{$value->license}}
										</label>
										&nbsp;&nbsp;
@endforeach
										<div class="required_error" id="license_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="graduation_year_area" class="@if ($errors->has('graduation_year'))err @endif">
									<th class="tit"><div class="melon-btn-flat-king melon-btn-block">卒業年度</div></th>
									<td class="essential"></td>
									<td>
										<select name="graduation_year" id="graduation_year" class="select02">
											<option value="">選択してください</option>
@foreach($graduationYearList as $key => $value)
											<option label="{{ $value }}" value="{{ $key }}" @if(old('graduation_year') == $key) selected @endif>{{ $value }}</option>
@endforeach
										</select>
										<div class="required_error" id="graduation_year_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="employ_area" class="@if ($errors->has('req_emp_type'))err @endif">
									<th class="tit"><div class="melon-btn-flat-king melon-btn-block">希望雇用形態</div></th>
									<td class="essential">※</td>
									<td>
@foreach ($reqEmpTypeList as $value)
										<label for="req_emp_type_{{$value->id}}">
											<input type="radio" name="req_emp_type[]" id="req_emp_type_{{$value->id}}" value="{{$value->id}}" @if (@in_array($value->id, $req_emp_type)) checked="checked" @endif>
											 {{$value->emp_type}}
										</label>
@endforeach
										<div class="required_error" id="req_emp_type_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="req_date_area" class="@if ($errors->has('req_date'))err @endif">
									<th class="tit"><div class="melon-btn-flat-king melon-btn-block">入職希望時期</div></th>
									<td class="essential">※</td>
									<td>
										<select name="req_date" id="req_date" class="selectReqate">
											<option value="">選択してください</option>
@foreach ($req_dateList as $value)
											<option label="{{$value->req_date}}" value="{{$value->id}}" @if($value->id == $req_date) selected @endif>{{$value->req_date}}</option>
@endforeach
										</select>
										<div class="required_error" id="req_date_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="retirement_intention_area" class="@if ($errors->has('retirement_intention'))err @endif">
									<th class="tit"><div class="melon-btn-flat-king melon-btn-block">退職意向</div></th>
									<td class="essential">※</td>
									<td>
										<select name="retirement_intention" id="retirement_intention" class="select02">
											<option value="">選択してください</option>
@foreach($retirement_intentionList as $key => $value)
											<option label="{{ $value }}" value="{{ $key }}" @if(old('retirement_intention') == $key) selected @endif>{{ $value }}</option>
@endforeach
										</select>
										<div class="required_error" id="retirement_intention_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="entry_category_manual_area" class="@if ($errors->has('entry_category_manual'))err @endif">
									<th class="tit"><div class="melon-btn-flat-king melon-btn-block">登録カテゴリ</div></th>
									<td class="essential">※</td>
									<td>
										<select name="entry_category_manual" id="entry_category_manual" class="select02">
											<option value="">選択してください</option>
@foreach($entry_category_manual as $key => $value)
											<option label="{{ $value }}" value="{{ $value }}" @if(old('entry_category_manual') == $value || request()->input('category') === (string)$key ) selected @endif>{{ $value }}</option>
@endforeach
										</select>
										<div class="required_error" id="entry_category_manual_errmsg" style="display: none;"></div>
									</td>
								</tr>
								<tr id="agreement_flag_area" class="@if ($errors->has('agreement_flag'))err @endif">
									<th class="tit"></th>
									<td class="essential"></td>
									<td>
										<label for="agreement_flag">
											<input type="checkbox" name="agreement_flag" id="agreement_flag" value="1" @if(old('agreement_flag') == "1") checked="checked" @endif>
											 利用規約に同意する
										</label>
										<div class="agreement_flag_error" id="agreement_flag_errmsg" style="display: none;"></div>
									</td>
								</tr>
							</table>
							<div class="SubmitbtnArea">
								<div class="mb10"><input id="btnSubmit" type="image" src="/woa/entry/img/pc3/btn_commonform.gif" class="proceed_button" value="「個人情報の取扱」を確認の上、次ページに進む" alt="「利用規約」に同意して次へ進む" /></div>
								<div>
									<span><a href="{{ route('Rule') }}" rel="dialog nofollow" id="kiyaku" data-modal="rule" data-transition="pop" data-modal="rule">利用規約</a></span>
									<span class="last"><a href="{{ route('Privacy') }}" rel="nofollow" id="kojin_joho" data-modal="privacy">個人情報の取り扱いについて</a></span>
								</div>
								<div class="modal rule">
									<div class="modalBody">
										<div class="scroll_box" id="rule"></div>
										<p class="close">×close</p>
									</div>
									<div class="modalBK"></div>
								</div>
								<div class="modal privacy">
									<div class="modalBody">
										<div class="scroll_box" id="privacy"></div>
										<p class="close">×close</p>
									</div>
									<div class="modalBK"></div>
								</div>
							</div>
						</div><!-- /content -->
					</div><!-- /form -->
				</div><!-- /commonForm -->
				<!-- 登録フォーム入力欄ここまで　-->
			</div><!-- /formContents -->
		</form>
	</div><!-- /contentsArea -->

	<div class="mb50"></div>

	<div id="footer">
		<div class="innerfooter">
			<small>(C) SMS CO., LTD. All Rights Reserved.</small>
		</div>
	</div>

	<script src="/woa/js/entry/pc/form3/modal_footer.js"></script>

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
	@include('common._common_tag')
</body>
</html>
