<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta name="robots" content="noindex,nofollow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    @include ('common._ogp')
	<title>{{ $headtitle }}</title>
	<link rel="stylesheet" type="text/css" href="{{ addQuery('/woa/entry/sp/form3002/css/style.css') }}">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
	@include ('common._gtag')
	<link rel="icon" href="/woa/favicon.ico">
</head>
<body class="step1">
	<div class="contents">

		<div class="hands" style="right: 2%; top: 35%;z-index:1000000;"></div>
		<div class="arrow_step5"></div>

		<form id="form" name="form1" action="@if (preg_match('/jinzaibank.com/', $_SERVER['HTTP_HOST'])){{ route('EntryFin')}}@else{{ route('EntryFinFromOld')}}@endif" method="post">
			@include ('common._hidden_entry')

			<div id="dialog_form" data-initialstate="false">
				<div id="dialog_header">
					<h1>
                        <img src="/woa/entry/sp/form3002/img/head.png" alt="ウィルワン ハローワーク非掲載も多数！新着・会員限定のレア求人をご紹介！">
                        <p>就職やキャリアに関するお悩みなどなんでもご相談ください！</p>
                    </h1>
                    <div class="header-img-wrap">
                        <img src="/woa/entry/sp/form3002/img/header-img.png" alt="ウィルワンが治療家学生の就職を完全無料でサポート!">
                        <div class="header-img-text">
                            <p>ウィルワンが</p>
                            <p>治療家学生の<span>就職</span>を</p>
                            <p>完全無料でサポート！</p>
                        </div>
                    </div>

                    <div class="bar">
                        <div class="bar-item"></div>
                        <div class="bar-item"></div>
                        <div class="bar-item"></div>
                        <div class="bar-item"></div>
                        <div class="bar-item"></div>
                    </div>

			@include('common._error_message')
					<ul class="status" data-page-num="1">
						<li><strong>1<span>.資格選択</span></strong></li>
						<li><strong>2<span>.時期と働き方</span></strong></li>
						<li><strong>3<span>.通勤エリア</span></strong></li>
						<li><strong>4<span>.名前と生年</span></strong></li>
						<li><strong>5<span>.お仕事状況</span></strong></li>
					</ul>
				</div>
                @if (!empty($office_name) || (!empty($addr2_name) && !empty($business)))
                <p id="job_text" style="margin-top: 5px;">{{$office_name ?? (($addr2_name ?? '') . ($business ?? ''))}}<br>の詳しい情報をお届けします。</p>
                @endif
				<div id="dialog_content">
					<ul class="bxslider">
						<li>
							<div id="Step1" class="step1-2" style="visibility: visible;">
								<div id="license_area" class="formItem">
									<div class="labelHeading">
										<h2 class="itemTitle">どんな資格をお持ちですか？<small>（複数選択可）</small></h2>
									</div>
									<ul id="license_container" class="colWrap">
                                        <div class="formItemStudent">
											@foreach ($licenseList as $value)
											@if (in_array($value->id, $licenseStudent))
											<li class="col @if (@in_array($value->id, $license) || in_array($value->license, $shikaku)) checked @endif">
												<input type="checkbox" name="license[]" id="license_{{ $value->id }}" value="{{ $value->id }}" class="checkboxCol" @if (@in_array($value->id, $license) || in_array($value->license, $shikaku)) checked="checked" @endif >
												<label for="license_{{ $value->id }}" class="checkbox">
													<img src="/woa/entry/sp/form3002/img/license_{{ $value->id }}.png" width="45px">{{ $value->license }}
												</label>
											</li>
											@endif
											@endforeach
										</div>
                                        <div id="graduation_year_area">
                                            <h3>卒業年：</h3>
                                            <select name="graduation_year" id="graduation_year">
                                                <option value="">選択してください</option>
                                                @foreach ($graduationYearList as $key => $value)
                                                <option label="{{ $value }}" value="{{ $key }}" @if (old('graduation_year') == $key || $graduation_year == $value) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            <div class="error_message" id="graduation_year_errmsg" style="display: none;">@if ($errors->has('graduation_year')){{ $errors->first('graduation_year') }} @endif </div>
                                        </div>
                                        <li class="col slide-student-btn" id="slide_student_btn">
											就業中の方はこちら
											<img src="/woa/entry/sp/form3002/img/352466_arrow_down_icon.svg" width="45px">
										</li>
                                        <div class="formItemWorker" style="display: none;">
                                            @foreach ($licenseList as $value)
                                            @if (!in_array($value->id, $licenseStudent))
                                            <li class="col @if (in_array($value->license, $shikaku)) checked @endif ">
                                                <input type="checkbox" name="license[]" id="license_{{ $value->id }}" value="{{ $value->id }}" class="checkboxCol" @if (in_array($value->license, $shikaku)) checked="checked" @endif>
                                                <label for="license_{{ $value->id }}" class="checkbox">
                                                    <img src="/woa/entry/sp/form3002/img/license_{{ $value->id }}.png" width="45px">{{ $value->license }}
                                                </label>
                                            </li>
                                            @endif
                                            @endforeach
                                        </div>
									</ul>
									@if ($errors->has('license'))
									<div class="error_messageF" id="license_errmsg" style="display: block;">{{ $errors->first('license') }}</div>
									@else
									<div class="error_message" id="license_errmsg"></div>
									@endif
								</div>
							</div>
						</li>{{-- /STEP1 --}}
						<li>
							<div id="Step2" style="visibility: visible;">
                                <input name="req_date" value='3' type="hidden">
								<div id="req_emp_types_area" class="formItem">
									<div class="labelHeading">
										<h2 class="itemTitle">ご希望の働き方</h2>
									</div>
									<ul class="colWrap">
									@foreach ($reqEmpTypeList as $value)
										<li class="col @if (@in_array($value->id, $req_emp_type)) checked @endif">
										<input type="radio" value="{{ $value->id }}" id="req_emp_type_{{ $loop->iteration }}" name="req_emp_type[]" @if (@in_array($value->id, $req_emp_type)) checked="checked" @endif disabled="disabled">
										<label for="req_emp_type_{{ $loop->iteration }}" class="radio"><img src="/woa/entry/sp/form3002/img/req_emp_type_{{ $value->id }}.png" width="45px">{!! nl2br($value->emp_type_br) !!}</label>
										</li>
									@endforeach
									</ul>
									@if ($errors->has('req_emp_type'))
									<div id="req_emp_type_errmsg" class="error_message" style="display: block;">{{ $errors->first('req_emp_type') }}</div>
									@else
									<div id="req_emp_type_errmsg" class="error_message" style="display: none;"></div>
									@endif
									<div id="req_emp_type_errmsg" class="error_message"></div>
								</div>
							</div>
						</li>{{-- /STEP2 --}}
						<li>
							<div id="Step3" style="visibility: visible;">
								<p class="smallTxt key"><small>公開されません</small></p>
								<div id="zip_area">
									<h2 class="itemTitle">お住まいの郵便番号</h2>
									<div class="formItem">
										<span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
										<input type="number" value="{{$zip ?? old('zip') }}" name="zip" id="zip" style="ime-mode: disabled;" class="width150zip @if (!empty($zip)) placeOn on @endif" disabled="disabled" maxlength="7" inputmode="numeric" placeholder="例：1234567"/>
									</div>
									<div class="error_message" id="zip_errmsg" @if ($errors->has('zip')) style="display: block;" @endif>{{ $errors->first('zip') }}</div>
									<div id="zip2">
										<div id="zip3"><small>郵便番号がわからない場合はコチラ</small></div>
										<div id="addr_area" class="acoArea">
											<div class="formItem formItem_step3">
												<select name="addr1" id="addr1" style="ime-mode:active;" disabled="disabled">
													<option value="">選択してください</option>
													@foreach ($prefectureList as $value)
													<option label="{{ $value->addr1 }}" value="{{ $value->id }}" @if ($value->id == $addr1 || $value->addr1 == $user_addr1) selected @endif >{{ $value->addr1 }}</option>
													@endforeach
												</select>
											</div><!-- /formItem -->
											<div class="formItem formItem_step3">
												<select name="addr2" id="addr2" disabled="disabled">
													<option value="">選択して下さい</option>
													@foreach ($cityList as $value)
													<option label="{{ $value->addr2 }}" value="{{ $value->id }}" @if ($value->id == $addr2 || $value->addr2 == $user_addr2) selected @endif>{{ $value->addr2 }}</option>
													@endforeach
												</select>
											</div>
											<input type="hidden" id="form_addr2" value="{{ old('addr2') }}">
											<div id="addr1or2_errmsg0" class="errorBox error_message err_pos01" @if ($errors->has('addr1')) style="display: block;" @endif >{{ $errors->first('addr1') }}</div>
											<div id="addr1or2_errmsg1" class="errorBox error_message err_pos02" @if ($errors->has('addr2')) style="display: block;" @endif >{{ $errors->first('addr2') }}</div>
											<div id="" class="formItem formItem_step3">
												<input type="text" name="addr3" value="{{ $addr3 ?? old('addr3')}}" id="addr3" class="width175_err width_87" style="ime-mode: active;" placeholder="例：1-2-3　AAマンション101" disabled="disabled" maxlength="255"/>
											</div>
											<div id="addr3_errmsg" class="error_message" @if ($errors->has('addr3')) style="display: block;" @endif >{{ $errors->first('addr3') }}</div>
										</div><!-- /acoArea -->
									</div>
                                    <div id="moving_flg_area" class="formItem">
                                        <h2 class="itemTitle">県外への転居はご検討されてますか？</h2>
                                        <ul class="colWrap">
                                            <li class="col @if (old('moving_flg') == '可') checked @endif">
                                                <input type="radio" value="可" id="moving_flg_1" name="moving_flg" @if (old('moving_flg') == '可') checked="checked" @endif disabled="disabled">
                                                <label for="moving_flg_1" class="radio2">はい / 求人次第</label>
                                            </li>
                                            <li class="col @if (old('moving_flg') == '否') checked @endif">
                                                <input type="radio" value="否" id="moving_flg_2" name="moving_flg" @if (old('moving_flg') == '否') checked="checked" @endif disabled="disabled">
                                                <label for="moving_flg_2" class="radio2">いいえ</label>
                                            </li>
                                        </ul>
                                        @if ($errors->has('moving_flg'))
                                        <div id="moving_flg_errmsg" class="error_message" style="display: block;">{{ $errors->first('moving_flg') }}</div>
                                        @else
                                        <div id="moving_flg_errmsg" class="error_message" style="display: none;"></div>
                                        @endif
                                    </div>
                                    <div class="addTxt message">
                                        <img src="/woa/entry/sp/form3002/img/ico_01-2.png" alt="ポイント">
                                        <p><strong>お近くの求人情報</strong>をお届けいたします。<br>希望勤務エリアがある方は、ご登録後に設定可能です。</p>
                                    </div>
								</div>
							</div>
						</li>{{-- /STEP3 --}}
						<li>
							<div id="Step4" style="visibility: visible;">
								<p class="smallTxt key"><small>公開されません</small></p>
								<h2 class="itemTitle icon name">お名前</h2>
								<div class="formItem formItem_step4">
									<input type="text" size="20" name="name_kan" id="name_kan" value="{{$name_kan ?? old('name_kan')}}" disabled="disabled" maxlength="64" @if (!empty($name_kan)) class="placeOn on" @endif>
									<label for="name_kan" class="formLabel">例：整体花子</label>
									<div class="error_message" id="name_kan_errmsg" @if ($errors->has('name_kan')) style="display: block;" @endif>{{ $errors->first('name_kan') }}</div>
								</div>
								<div class="formItem formItem_step4 name_cana-frame" @if (!empty($name_kan)) style="display: block;" @endif>
								<input type="text" size="20" name="name_cana" id="name_cana" value="{{ $name_cana ?? old('name_cana')}}" placeholder="例：せいたいはなこ" @empty($name_kan) disabled="disabled" @endempty maxlength="64">
								</div>
								<div class="error_message" id="name_cana_errmsg" @if ($errors->has('name_cana')) style="display: block;" @endif>{{ $errors->first('name_cana') }}</div>
								<h2 class="itemTitle icon birth">生まれ年</h2>
								<div class="formItem formItem_step4">
									<div class="selectWrap birth">
										西暦<input type="number" size="20" maxlength="4" name="input_birth_year" id="input_birth_year" value="{{ $input_birth_year ?? old('input_birth_year')}}" style="" inputmode="numeric"><label for="input_birth_year" class="formLabel3">@if (empty($input_birth_year)) 例：1999 @endif</label>年

									</div>
									<div class="error_message" id="input_birth_year_errmsg" @if ($errors->has('input_birth_year')) style="display: block;" @endif>{{ $errors->first('input_birth_year') }}</div>
								</div>
								<div class="addTxt message" style="padding-top:0">
									<img src="/woa/entry/sp/form3002/img/ico_01-2.png" alt="ポイント">
									<p> 給与情報などがより正確にわかります。</p>
								</div>
							</div>
						</li>{{-- /STEP4 --}}
						<li>
							<div id="Step5" style="visibility: visible;">
								<p class="smallTxt key"><small>公開されません</small></p>
								<h2 class="itemTitle icon sp">携帯番号</h2>
								<div class="formItem">
									<input id="mob_phone" name="mob_phone" type="tel" value="{{$mob_phone ?? old('mob_phone')}}" style="ime-mode: disabled;" size="14" disabled="disabled" maxlength="11" placeholder="例：09012345678" @if (!empty($mob_phone)) class="placeOn on" @endif>
									<div class="error_message" id="mob_phone_errmsg" @if ($errors->has('mob_phone')) style="display: block;" @endif>{{ $errors->first('mob_phone') }}</div>
								</div>
                                <h2 class="itemTitle icon mail">メールアドレス</h2>
                                <div class="formItem">
									<input id="mob_mail" name="mail" type="email" size="22" value="{{$mob_mail ?? old('mob_mail')}}" maxlength="80" style="ime-mode: disabled;" placeholder="メールアドレス" disabled="disabled" @if (!empty($mob_mail)) class="on" @endif>
									<div id="suggest" style="display:none;" tabindex="-1"></div>
									<div class="error_message" id="mob_mail_errmsg" @if ($errors->has('mob_mail')) style="display: block;" @endif>{{ $errors->first('mob_mail') }}</div>
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
							</div>
						</li>{{-- /STEP5 --}}
						<li>
							<div id="dialog_page6" style="visibility: visible;"><br>
								<div align="center"><br><br><br><span class="message"><!--  登録処理中です・・・ --></span></div>
							</div>
						</li>
					</ul>
				</div>
			</div><!-- /dialog_form -->
            <input name='retirement_intention' value='良い転職先なら辞めたい' type='hidden'>
		</form>

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

		<footer>
			<div class="pMark"><p><a href="https://privacymark.jp/" target="_blank" onclick="$.sendGA(1, 'privacymark');" id="privacymark"><img src="/woa/img/pmark.gif" alt="プライバシーマーク" class="mar_rm"></a><b>安心してご登録いただくために</b><br>ウィルワンエージェントを運営する(株)エス・エム・エスはプライバシーマークを取得しております。</p></div>
			@include ('sp.contents.include._modal_footer')
		</footer>

	</div><!-- end contents -->

	<script src="/woa/js/jquery-1.11.1.min.js"></script>
	<script src="/woa/js/jquery.easing.1.3.js"></script>
	<script src="/woa/js/jquery.autoKana.js"></script>
	<script src="/woa/js/common/ga_common.js?20190725"></script>
	<script src="{{addQuery('/woa/js/common/ga4_common.js')}}"></script>
	<script src="/woa/js/common/SearchCity/jquery.SearchCity.js?20180913"></script>
	<script src="/woa/entry/sp/form3002/js/jq.bxslider_for_horizscrl_form.js?20221121"></script>
	<script src="{{addQuery('/woa/entry/sp/form3002/js/validation_form.js')}}"></script>
	<script src="{{addQuery('/woa/entry/sp/form3002/js/horizontal_scroll_form.js')}}"></script>
	<script src="/woa/entry/sp/form3002/js/sp_modal_browserback.js?20221114"></script>

	<!-- adwordsリマケタグ -->
	<script>
		/* <![CDATA[ */
		var google_conversion_id = 822859503;
		var google_custom_params = window.google_tag_params;
		var google_remarketing_only = true;
		/* ]]> */
	</script>

	<script src="//www.googleadservices.com/pagead/conversion.js"></script>

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
            // hubspotから取得できた情報に応じて表示を切り替える
            @if ($student == false && !empty($shikaku))
            $('#slide_student_btn').trigger('click');
            jQuery('.bx-next').removeClass('off');
            @endif
            @if (!empty($zip))
            $('#zip3').trigger('click');
            @endif
            if(jQuery('[name=license\\[\\]]:checked').length > 0 && jQuery('[name=graduation_year]').val() != ''){
                jQuery('.bx-next').removeClass('off');
            }
		});
	</script>
	<script>
		$(function() {
			$.fn.autoKana('#name_kan', '#name_cana', {
				katakana : false  //true：カタカナ、false：ひらがな（デフォルト）
			});
		});

		jQuery(function($){
			// デフォルトはAタイプ表示
			// 「お気持ちはどちらに近いですか？」の処理
			$('[data-modal="branch"]').trigger('click');
            // 「近いうちに転職したい」「今は情報収集したい」クリック時のイベント登録
            $('.branch_btn-A,.branch_btn-B,.modalBK').on('click', function(){
                branchOperation();
            })
            // パラメータにbranchがある場合は「お気持ち」を飛ばす
            const params = (new URL(document.location)).searchParams;
            const branchType = params.get('branch');
            if (['A', 'B'].includes(branchType)) {
                $("body").removeClass("modal-on");
                const btnName = '.branch_btn-'+branchType;
                $(btnName).trigger("click");
            }
			// JINZAISYSTEM-8476
			// メールアドレス入力欄表示パターン切り替え
			$('input[name="retirement_intention"]').on('change', function(){
				// 退職意向
				retirement = this.value;
			//display_pattern = document.getElementById('display_pattern').value;
				display_pattern = $('input[name="pattern"]').val();

				// 「今は情報収集したい」選択時
				if(display_pattern == 'B') {
					// 転職希望時期
					req_date = $('input[name="req_date"]:checked').val();
					pattern = 1;

					switch(req_date) {
						case "1":
							if(retirement == '良い転職先なら検討する' || retirement == '半年以上は辞められない'
								|| retirement == 'あまり辞める気は無い' || retirement == 'その他')
							{
								pattern = 2;
							}
							break;
						case "2":
						case "3":
							if(retirement == '半年以上は辞められない' || retirement == 'あまり辞める気は無い')
							{
								pattern = 2;
							}
							break;
						case "4":
							if(retirement != '半年以上は辞められない')
							{
								pattern = 2;
							}
							break;
						case "5":
							pattern = 2;
							break;
						default:
							break;
					}

				}
			});
		});
        function branchOperation(){
            $('html,body').scrollTop(0);
            $('#branch,.bx-clone .step1-2').hide();
            $('.step1-2,.hands').fadeIn();
            $('.bx-controls').css('opacity','1');
            if(jQuery('[name=license\\[\\]]:checked').length > 0){
                jQuery('.bx-next').removeClass('off');
            }
            const startPosition = $("#license_area").offset();
            $(".hands").animate({ top: startPosition.top + 100 }, 0);
            $('body').css('overflow', 'visible');
        }
	</script>
	@include('common._common_tag')
</body>
</html>
