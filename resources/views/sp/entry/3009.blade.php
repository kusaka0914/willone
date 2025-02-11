<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta name="robots" content="noindex,nofollow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    @include ('common._ogp')
	<title>{{ $headtitle }}</title>
	<link rel="stylesheet" type="text/css" href="{{ addQuery('/woa/entry/sp/form3009/css/style.css') }}">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
	@include ('common._gtag')
    <link rel="icon" href="/image_file/kurohon.ico">
</head>
<body class="step1">
	<div class="contents">

		<div class="hands" style="right: 2%; top: 20%;"></div>
		<div class="arrow_step5"></div>

		<form id="form" name="form1" action="@if (preg_match('/jinzaibank.com/', $_SERVER['HTTP_HOST'])){{ route('EntryFin')}}@else{{ route('EntryFinFromOld')}}@endif" method="post">

			@include ('common._hidden_entry')
            <input type="hidden" name="thanks_lp" value="1">
			<div id="dialog_form" data-initialstate="false">
				<div id="dialog_header">
                <h1><img src="/woa/entry/sp/form3009/img/logo.svg" alt="【国試黒本】柔道整復師・鍼灸師・マッサージ師試験の参考書/国家試験対策" height="12" width="65"></h1>
                <div class="header-description">就職支援だけでなく、<span class="font-red">国家試験</span>、<span class="font-red">就職フェア</span>まで幅広く<span class="font-blue">治療家学生</span>を支援！</div>

			@include('common._error_message')
					<ul class="status" data-page-num="1">
						<li><strong>1<span>.資格選択</span></strong></li>
						<li><strong>2<span>.時期と働き方</span></strong></li>
						<li><strong>3<span>.通勤エリア</span></strong></li>
						<li><strong>4<span>.名前と生年</span></strong></li>
						<li><strong>5<span>.お仕事状況</span></strong></li>
					</ul>
				</div>
                @if(!empty($office_name) || (!empty($addr2_name) && !empty($business)))
                <p id="job_text" class="job-text" style="margin-top: 5px;">{{$office_name ?? (($addr2_name ?? '') . ($business ?? ''))}}<br>の詳しい情報をお届けします。</p>
                @endif
                <div id="dialog_content">
					<ul class="bxslider">
						<li>
							<div id="branch">
								<div class="labelHeading">
									<h2>お気持ちはどちらに近いですか？</h2>
								</div>
								<div class="modalInner">
									<a class="branch_btn-A" onclick="$('#branch_data').val('A');$.sendGA(1, 'STEP1_branchA');"><span>近いうちに就職したい</span></a>
									<a class="branch_btn-B" onclick="$('#branch_data').val('B');$.sendGA(1, 'STEP1_branchB');"><span>今は情報収集したい</span></a>
								</div>
								<div class="hands_modal"></div>
							</div>
							<div id="Step1" class="step1-2" style="visibility: visible;">
								<div class="stepBar --90">
									<div class="stepBar__pieWrap">
                  	<div class="stepBar__pie"></div>
									</div>
                  <div class="stepbar__text">
                      <p>残り<span class="color">90</span>%！&nbsp;カンタン<span>60</span>秒で求人検索！</p>
                  </div>
                </div>
								<div id="license_area" class="formItem">
									<div class="labelHeading">
										<h2 class="itemTitle">どんな資格をお持ちですか？<small>（複数選択可）</small></h2>
									</div>
									<ul id="license_container" class="colWrap">
										@foreach ($licenseList as $value)
										@if (!in_array($value->id, $licenseStudent))
										<li class="col">
											<input type="checkbox" name="license[]" id="license_{{ $value->id }}" value="{{ $value->id }}" class="checkboxCol">
                                            <label for="license_{{ $value->id }}" class="checkbox checkbox_{{ $value->id }}">
                                                <div class="col-img-grid">
                                                    <div class="col-img-box col-img-{{ $value->id }}"></div>
                                                    <span>{{ preg_replace("/（学生）/", '', $value->license) }}</span>

                                                </div>
											</label>
										</li>
										@endif
										@endforeach
										<div class="formItemStudent" style="display: none;">
											@foreach ($licenseList as $value)
											@if (in_array($value->id, $licenseStudent))
											<li class="col @if (@in_array($value->id, $license)) checked @endif">
												<input type="checkbox" name="license[]" id="license_{{ $value->id }}" value="{{ $value->id }}" class="checkboxCol" @if (@in_array($value->id, $license)) checked="checked" @endif >
												<label for="license_{{ $value->id }}" class="checkbox">
                                                    <div class="col-img-box col-img-student"></div>
                                                    <div>{{ $value->license }}</div>
												</label>
											</li>
											@endif
											@endforeach
										</div>
										<li class="col slide_student_btn-col" id="slide_student_btn">
                                            <div class="slide-student-btn">
                                                <img src="/woa/entry/sp/form3009/img/ico-student.png" width="45px">
                                                <span>学生</span>
                                            </div>
										</li>
									</ul>
									@if ($errors->has('license'))
									<div class="error_messageF" id="license_errmsg" style="display: block;">{{ $errors->first('license') }}</div>
									@else
									<div class="error_message" id="license_errmsg"></div>
									@endif
								</div>
								<div id="graduation_year_area" style="display:@if ($student) block; @else none; @endif">
                                    <div class="labelHeading">
										<h2 class="itemTitle">卒業年</h2>
									</div>
                                    <div class="grauduation">
                                        <select name="graduation_year" id="graduation_year">
                                            <option value="">選択してください</option>
                                            @foreach ($graduationYearList as $key => $value)
                                            <option label="{{ $value }}" value="{{ $key }}" @if (old('graduation_year') == $key) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
									@if ($errors->has('graduation_year'))
									<div class="error_message" id="graduation_year_errmsg" style="display: block;">{{ $errors->first('graduation_year') }}</div>
									@else
									<div class="error_message" id="graduation_year_errmsg" style="display: none;"></div>
									@endif
								</div>
							</div>
						</li>{{-- /STEP1 --}}
						<li>
							<div id="Step2" style="visibility: visible;">
								<div class="stepBar --70">
									<div class="stepBar__pieWrap">
  	                <div class="stepBar__pie"></div>
									</div>
                  <div class="stepbar__text">
                  	<p>残り&nbsp;<span>70%</span>&nbsp;！</p>&nbsp;カンタン60秒で求人検索！
                  </div>
                </div>
								<div id="req_date_area" class="formItem">
									<div class="labelHeading">
										<h2 class="itemTitle">いつ頃の求人をお探しですか？</h2>
									</div>
									<ul class="colWrap">
									@foreach ($req_dateList as $value)
											<li class="col @if ($value->id == old('req_date')) checked @endif">
											<input type="radio" value="{{ $value->id }}" id="req_date_{{ $loop->iteration }}" name="req_date" @if ($value->id == old('req_date')) checked="checked" @endif disabled="disabled">
                                                <label for="req_date_{{ $loop->iteration }}" class="radio req_date_{{ $loop->iteration }}">
                                                    <div class="col-img-box col-img-req_date_{{ $value->id }}"></div>
                                                    <div>{{ $value->req_date }}</div>
                                                </label>
											</li>
									@endforeach
									</ul>
									@if ($errors->has('req_date'))
									<div id="req_date_errmsg" class="error_message" style="display: block;">{{ $errors->first('req_date') }}</div>
									@else
									<div id="req_date_errmsg" class="error_message" style="display: none;"></div>
									@endif
								</div>
								<div id="req_emp_types_area" class="formItem">
									<div class="labelHeading">
										<h2 class="itemTitle">ご希望の働き方</h2>
									</div>
									<ul class="colWrap">
									@foreach ($reqEmpTypeList as $value)
										<li class="col">
										<input type="radio" value="{{ $value->id }}" id="req_emp_type_{{ $loop->iteration }}" name="req_emp_type[]" disabled="disabled">
										<!-- <label for="req_emp_type_{{ $loop->iteration }}" class="radio"><img src="/woa/entry/sp/form3009/img/req_emp_type_{{ $value->id }}.png" width="45px">{!! nl2br($value->emp_type_br) !!}</label> -->
                                        <label for="req_emp_type_{{ $loop->iteration }}" class="radio">
                                                <div class="col-img-grid">
                                                    <div class="col-img-box col-img-{{ $value->id }}"></div>
                                                    <span>{!! nl2br($value->emp_type_br) !!}</span>
                                                </div>
                                            </label>

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
								<div class="stepBar --50">
									<div class="stepBar__pieWrap">
  	                <div class="stepBar__pie"></div>
									</div>
                  <div class="stepbar__text">
                  	<p>残り&nbsp;<span>50%</span>&nbsp;！</p>&nbsp;カンタン60秒で求人検索！
                  </div>
                </div>
								<p class="smallTxt key"><small>公開されません</small></p>
								<div id="zip_area">
									<h2 class="itemTitle">お住まいの郵便番号</h2>
									<div class="formItem formBgWhite">
										<span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
										<input type="number" value="{{ old('zip') }}" name="zip" id="zip" style="ime-mode: disabled;" class="width150zip" disabled="disabled" maxlength="7" inputmode="numeric" placeholder="例：1234567"/>
									</div>
									<div class="error_message" id="zip_errmsg" @if ($errors->has('zip')) style="display: block;" @endif>{{ $errors->first('zip') }}</div>
									<div id="zip2">
										<div id="zip3"><small>郵便番号がわからない場合はコチラ</small></div>
										<div id="addr_area" class="acoArea">
											<div class="formItem formItem_step3">
												<select name="addr1" id="addr1" style="ime-mode:active;" disabled="disabled">
													<option value="">選択してください</option>
													@foreach ($prefectureList as $value)
													<option label="{{ $value->addr1 }}" value="{{ $value->id }}" @if ($value->id == $addr1) selected @endif >{{ $value->addr1 }}</option>
													@endforeach
												</select>
											</div><!-- /formItem -->
											<div class="formItem formItem_step3">
												<select name="addr2" id="addr2" disabled="disabled">
													<option value="">選択して下さい</option>
													@foreach ($cityList as $value)
													<option label="{{ $value->addr2 }}" value="{{ $value->id }}" @if ($value->id == $addr2) selected @endif>{{ $value->addr2 }}</option>
													@endforeach
												</select>
											</div>
											<input type="hidden" id="form_addr2" value="{{ old('addr2') }}">
											<div id="addr1or2_errmsg0" class="errorBox error_message err_pos01" @if ($errors->has('addr1')) style="display: block;" @endif >{{ $errors->first('addr1') }}</div>
											<div id="addr1or2_errmsg1" class="errorBox error_message err_pos02" @if ($errors->has('addr2')) style="display: block;" @endif >{{ $errors->first('addr2') }}</div>
											<div id="" class="formItem formItem_step3">
												<input type="text" name="addr3" value="{{ old('addr3')}}" id="addr3" class="width175_err width_87" style="ime-mode: active;" placeholder="例：1-2-3　AAマンション101" disabled="disabled" maxlength="255"/>
											</div>
											<div id="addr3_errmsg" class="error_message" @if ($errors->has('addr3')) style="display: block;" @endif >{{ $errors->first('addr3') }}</div>
										</div><!-- /acoArea -->
									</div>
                                    <div id="moving_flg_area" class="formItem">
                                        <h2 class="itemTitle">県外への転居はご検討されてますか？</h2>
                                        <ul class="colWrap">
                                            <li class="col @if(old('moving_flg') == '可') checked @endif">
                                                <input type="radio" value="可" id="moving_flg_1" name="moving_flg" @if(old('moving_flg') == '可') checked="checked" @endif disabled="disabled">
                                                <label for="moving_flg_1" class="radio2">はい / 求人次第</label>
                                            </li>
                                            <li class="col @if(old('moving_flg') == '否') checked @endif">
                                                <input type="radio" value="否" id="moving_flg_2" name="moving_flg" @if(old('moving_flg') == '否') checked="checked" @endif disabled="disabled">
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
                                        <img src="/woa/entry/sp/form3009/img/human.png" alt="ポイント">
                                        <p><strong>お近くの求人情報</strong>をお届けいたします。<br>希望勤務エリアがある方は、ご登録後に設定可能です。</p>
                                    </div>
								</div>
							</div>
						</li>{{-- /STEP3 --}}
						<li>
							<div id="Step4" style="visibility: visible;">
								<div class="stepBar --30">
									<div class="stepBar__pieWrap">
  	                <div class="stepBar__pie"></div>
									</div>
                  <div class="stepbar__text">
                  	<p>残り&nbsp;<span>30%</span>&nbsp;！</p>&nbsp;カンタン60秒で求人検索！
                  </div>
                </div>
								<p class="smallTxt key"><small>公開されません</small></p>
                                <div class="labelHeading">
                                    <h2 class="itemTitle">お名前</h2>
                                </div>
								<div class="formItem formItem_step4 formBgWhite">
									<input type="text" size="20" name="name_kan" id="name_kan" value="{{ old('name_kan')}}" disabled="disabled" maxlength="64">
									<label for="name_kan" class="formLabel">例：整体花子</label>
									<div class="error_message" id="name_kan_errmsg" @if ($errors->has('name_kan')) style="display: block;" @endif>{{ $errors->first('name_kan') }}</div>
								</div>
								<div class="formItem formItem_step4 name_cana-frame formBgWhite">
								<input type="text" size="20" name="name_cana" id="name_cana" value="{{ old('name_cana')}}" placeholder="例：せいたいはなこ" disabled="disabled" maxlength="64">
								</div>
								<div class="error_message" id="name_cana_errmsg" @if ($errors->has('name_cana')) style="display: block;" @endif>{{ $errors->first('name_cana') }}</div>
                                <div class="labelHeading">
                                    <h2 class="itemTitle">生まれ年</h2>
                                </div>
								<div class="formItem formItem_step4 formBgWhite">
									<div class="selectWrap birth">
										西暦<input type="number" size="20" maxlength="4" name="input_birth_year" id="input_birth_year" value="{{ old('input_birth_year')}}" style="" inputmode="numeric"><label for="input_birth_year" class="formLabel3">例：1999</label>年

									</div>
									<div class="error_message" id="input_birth_year_errmsg" @if ($errors->has('input_birth_year')) style="display: block;" @endif>{{ $errors->first('input_birth_year') }}</div>
								</div>
								<div class="addTxt message" style="padding-top:0">
									<img src="/woa/entry/sp/form3009/img/human.png" alt="ポイント">
									<p> 給与情報などがより正確にわかります。</p>
								</div>
							</div>
						</li>{{-- /STEP4 --}}
						<li>
							<div id="Step5" style="visibility: visible;">
								<div class="stepBar --last">
									<div class="stepBar__pieWrap">
  	                <div class="stepBar__pie"></div>
									</div>
                  <div class="stepbar__text">
                  	<p>これでラストです！</p>
                  </div>
                </div>
								<p class="smallTxt key"><small>公開されません</small></p>
                                <div class="labelHeading">
                                    <h2 class="itemTitle">携帯番号</h2>
                                </div>
								<div class="formItem formBgWhite">
									<input id="mob_phone" name="mob_phone" type="tel" value="{{ old('mob_phone')}}" style="ime-mode: disabled;" size="14" disabled="disabled" maxlength="11" placeholder="例：09012345678">
									<div class="error_message" id="mob_phone_errmsg" @if ($errors->has('mob_phone')) style="display: block;" @endif>{{ $errors->first('mob_phone') }}</div>
								</div>
								<div id="retirement_intention_area">
									<h2 class="itemTitle icon retirement">お仕事のご状況</h2>
									<div class="formItem bottomItem">
										<ul id="retirementIntention" class="stackedList">
										@foreach ($retirement_intentionList as $key => $value)
											<li class="@if (old('retirement_intention')==$key)) checked @endif">
												<input type="radio" value="{{ $key }}" id="retirement_intention_{{ $loop->iteration }}" name="retirement_intention" @if (old('retirement_intention')==$key)) checked="checked" @endif disabled="disabled">
												<label for="retirement_intention_{{ $loop->iteration }}" class="radio2">{{ $value }}</label>
											</li>
										@endforeach
										</ul>
									</div>
								</div>
								<div class="error_message" id="retirement_intention_errmsg" @if ($errors->has('retirement_intention')) style="display: block;" @endif>{{ $errors->first('retirement_intention') }}</div>

								<div class="formItem">
									<input id="mob_mail" name="mail" type="email" size="22" value="{{ old('mob_mail')}}" maxlength="80" style="ime-mode: disabled;" placeholder="メールアドレス（任意）" disabled="disabled">
									<div id="suggest" style="display:none;" tabindex="-1"></div>
									<div class="error_message" id="mob_mail_errmsg" @if ($errors->has('mob_mail')) style="display: block;" @endif>{{ $errors->first('mob_mail') }}</div>
								</div>

                <div class="modal willone-toha" style="display: none;">
                  <div class="modalBody">
                    <div class="scroll_box" id="tos">
                      <div>
                        <h2>「ウィルワンエージェント」とは・・・</h2><br>
                        国試黒本と同じ会社が運営する治療家専門の人材紹介サービスです。<br>国試黒本には掲載のない非公開求人があるため、求職者様の選択肢を広げることができます（ご利用は完全無料です）
                      </div>
                    </div>
                    <p class="close">×close</p>
                  </div>
                  <div class="modalBK"></div>
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
			<!-- div class="lastupdate"></div -->
			<div class="pMark"><p><a href="https://privacymark.jp/" target="_blank" onclick="$.sendGA(1, 'privacymark');" id="privacymark"><img src="/woa/img/pmark.gif" alt="プライバシーマーク" class="mar_rm"></a><b>安心してご登録いただくために</b><br>国試黒本を運営する(株)エス・エム・エスはプライバシーマークを取得しております。</p></div>
			@include ('sp.contents.include._modal_footer')
		</footer>

	</div><!-- end contents -->

	<script src="/woa/js/jquery-1.11.1.min.js"></script>
	<script src="/woa/js/jquery.easing.1.3.js"></script>
	<script src="/woa/js/jquery.autoKana.js"></script>
    <script src="{{addQuery('/woa/js/common/ga_common.js')}}"></script>
	<script src="{{addQuery('/woa/js/common/ga4_common.js')}}"></script>
    <script src="{{addQuery('/woa/js/common/SearchCity/jquery.SearchCity.js')}}"></script>
    <script src="{{addQuery('/woa/entry/sp/form3009/js/jq.bxslider_for_horizscrl_form.js')}}"></script>
    <script src="{{addQuery('/woa/entry/sp/form3009/js/validation_form.js')}}"></script>
	<script src="{{addQuery('/woa/entry/sp/form3009/js/horizontal_scroll_form.js')}}"></script>
    <script src="{{addQuery('/woa/entry/sp/form3009/js/modal_footer.js')}}"></script>
    <script src="{{addQuery('/woa/entry/sp/form3009/js/sp_modal_browserback.js')}}"></script>
    <script src="{{addQuery('/woa/js/common/enterBlock.js')}}"></script>

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
			$('.bx-controls').css('opacity','0');
			// 「お気持ちはどちらに近いですか？」の処理
			$('[data-modal="branch"]').trigger('click');
            // 「近いうちに転職したい」「今は情報収集したい」クリック時のイベント登録
            $('.branch_btn-A,.branch_btn-B').on('click', function(){
                branchOperation();
            })
            // パラメータにbranchがある場合は「お気持ち」を飛ばす
            const params = (new URL(document.location)).searchParams;
            const branchType = params.get('branch');
            if (['A', 'B'].includes(branchType)) {
                branchOperation();
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
        }
	</script>
	@include('common._common_tag')
</body>
</html>
