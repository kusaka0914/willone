<!DOCTYPE html>
<html lang="ja">
<head>
<meta name="robots" content="noindex,nofollow">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
<meta name="format-detection" content="telephone=no">
@include('common._ogp')
<title>>柔道整復師、鍼灸師、マッサージ師の求人・就職支援ならウィルワン</title>
<meta name="robots" content="noindex,nofollow">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
<link rel="stylesheet" href="/woa/css/company/pc/base.css">
<link rel="stylesheet" href="/woa/css/company/pc/layout.css">
<link rel="stylesheet" href="/woa/css/company/pc/theme.css">
<link rel="stylesheet" href="/woa/css/company/pc/module.css">
<link rel="stylesheet" href="/woa/css/company/pc/sp_module.css">
<link rel="stylesheet" href="/woa/css/company/pc/kjb_module.css">
<link rel="stylesheet" href="/woa/css/company/pc/kjb_theme.css">
<link rel="stylesheet" href="/woa/css/company/pc/kjb_sp_module.css">

{{-- ヘッダーフッター共通化によって崩れたデザイン修正 --}}
<style type="text/css">
select {
    -webkit-appearance: menulist;
    width: auto;
    height: auto;
}
table tbody, table tfoot, table thead{
    border: 0px solid #f1f1f1;
}
table tbody tr:nth-child(even) {
    background-color: #fefefe;
}
label{
    color: #7c7a6a;
    font-size:inherit;
    display:inherit;
}
[type=color], [type=date], [type=datetime-local], [type=datetime], [type=email], [type=month], [type=number], [type=password], [type=search], [type=tel], [type=text], [type=time], [type=url], [type=week], textarea
{
    height: inherit;
    box-shadow:none;
}
[type='text']:focus, textarea:focus {
    outline: none;
    border: 1px solid #ff961b;
    background-color: #fefefe;
    box-shadow: 0 0 5px #cacaca;
    transition: box-shadow 0.5s, border-color 0.25s ease-in-out;
}
.ejb-l_footer1{
    font-size: 0.95rem;
}
.ejb-l_footer2 {
    font-size: 1.1rem;
}
.error_message {
    background: url(/woa/img/company/sp/ico_error.png) top left no-repeat;
    height: 15px;
    padding-left: 20px;
    color: #fe5252;
    font-weight: bold;
    font-size: 12px;
    padding-top: 2px;
}
.l-header {
    height: 60px;
    border-top: none;
}

</style>
    <link rel="icon" href="/woa/favicon.ico">
</head>

<body id="top" class="theme-txt-gray45">
    <div class="l-header-logo"><a href="https://www.jinzaibank.com/woa"><img src="/woa/images/logo.png" alt="ウィルワン"></a></div>

    <header class="l-header js-header">
        <div class="l-header-container">
            </ul> -->
        </div>
    </header>

    <main role="main">
    <section class="sp-main theme-bg-gray theme-sb-gray">
    <div class="sp-inner-box">

        <form id="form" name="form1" action="/woa/empinquiry/comp" method="post" target="_self" enctype="application/x-www-url-encoded" onsubmit="disableButton();">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="action" value="{{ $action }}">
            <input type="hidden" name="account" value="{{ $account }}">
            <input type="hidden" name="order_tel_contact" value="{{ $order_tel_contact }}">
            <input type="hidden" name="sent_address" value="{{ $sent_address }}">
            <input type="hidden" name="shubetu" value="{{ $shubetu }}">
            <p class="l-discription theme-ba-gr1 mgn-tb10">
                採用ご担当者様へ<br/>
                下記フォームにてお問合せください。詳細をご案内いたします。<br/>
            </p>
            <table class="member-detail theme-ba-gr1 center">
                <tr>
                    <th class="member-info-title">お問い合せフォーム</th>
                </tr>
                @if(mb_strpos($action,'employment_o_s') === FALSE)
                    <tr>
                        <th class="member-detail-item theme-bt-gr1">貴社名<img src="/woa/img/company/pc/icon_Required_form.png" width="30" height="18" alt="必須" class="imgRight"></th>
                        <td class="member-detail-comment theme-bt-gr1">
                            @if($errors->has('company_name'))
                                <div class="error_message">{{ $errors->first('company_name') }}</div>
                            @endif
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" class="member-form-input3">
                        </td>
                    </tr>
                @else
                    <input type="hidden" name="company_name" value="雇用概要書由来">
                @endif
                @if(mb_strpos($action,'woa_jg_mailmg') === FALSE)
                    <tr>
                        <th class="member-detail-item theme-bt-gr1">部署名<img src="/woa/img/company/pc/icon_Required_form.png" width="30" height="18" alt="必須" class="imgRight"></th>
                        <td class="member-detail-comment theme-bt-gr1">
                            @if($errors->has('division_name'))
                                <div class="error_message">{{ $errors->first('division_name') }}</div>
                            @endif
                            <input type="text" id="division_name" name="division_name" value="{{ old('division_name') }}" class="member-form-input3">
                        </td>
                    </tr>
                @else
                    <input type="hidden" name="division_name" value="事業所メルマガ由来">
                @endif
                <tr>
                    <th class="member-detail-item theme-bt-gr1">担当者名<img src="/woa/img/company/pc/icon_Required_form.png" width="30" height="18" alt="必須" class="imgRight"></th>
                    <td class="member-detail-comment theme-bt-gr1">
                        @if($errors->has('name_kan'))
                            <div class="error_message">{{ $errors->first('name_kan') }}</div>
                        @endif
                        <input type="text" id="name_kan" name="name_kan" value="{{ old('name_kan') }}" class="member-form-input3">
                    </td>
                </tr>
                @if(mb_strpos($action,'woa_jg_mailmg') === FALSE)
                    <tr>
                        <th class="member-detail-item theme-bt-gr1">フリガナ<img src="/woa/img/company/pc/icon_Required_form.png" width="30" height="18" alt="必須" class="imgRight"></th>
                        <td class="member-detail-comment theme-bt-gr1">
                            @if($errors->has('name_cana'))
                                <div class="error_message">{{ $errors->first('name_cana') }}</div>
                            @endif
                            <input type="text" id="name_cana" name="name_cana" value="{{ old('name_cana') }}" class="member-form-input3">
                        </td>
                    </tr>
                @else
                    <input type="hidden" name="name_cana" value="事業所メルマガ由来">
                @endif
                @if(mb_strpos($action,'employment_o_s') === FALSE)
                    @if(mb_strpos($action,'woa_jg_mailmg') === FALSE)
                    <tr>
                        <th class="member-detail-item theme-bt-gr1">郵便番号</th>
                        <td class="member-detail-comment theme-bt-gr1">
                            <input type="text" id="zip" name="zip" class="member-form-input2" placeholder="例：1234567">
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th class="member-detail-item">都道府県<img src="/woa/img/company/pc/icon_Required_form.png" width="30" height="18" alt="必須" class="imgRight"></th>
                        <td class="member-detail-comment">
                            @if($errors->has('addr1'))
                                <div class="error_message">{{ $errors->first('addr1') }}</div>
                            @endif
                            <select id="addr1" name="addr1">
                                <option value="">選択してください</option>
                                @foreach($prefectureList as $value)
                                    <option label="{{ $value->addr1 }}" value="{{ $value->id }}" @if($addr1 == $value->id) selected @endif>{{ $value->addr1 }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    @if(mb_strpos($action,'woa_jg_mailmg') === FALSE)


                        <tr>
                            <th class="member-detail-item theme-bt-gr1">市区町村<img src="/woa/img/company/pc/icon_Required_form.png" width="30" height="18" alt="必須" class="imgRight"></th>
                            <td class="member-detail-comment theme-bt-gr1">
                                @if($errors->has('addr2'))
                                    <div class="error_message">{{ $errors->first('addr2') }}</div>
                                @endif
                                <select class="selectBox" id="addr2" name="addr2">
                                <option value="">選択してください</option>
                                    @foreach($cityList as $value)
                                        <option label="{{ $value->addr2 }}" value="{{ $value->id }}" @if(old('addr2') == $value->id) selected @endif>{{ $value->addr2 }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="member-detail-item theme-bt-gr1">番地・建物名等<img src="/woa/img/company/pc/icon_Required_form.png" width="30" height="18" alt="必須" class="imgRight"></th>
                            <td class="member-detail-comment theme-bt-gr1">
                                @if($errors->has('addr3'))
                                    <div class="error_message">{{ $errors->first('addr3') }}</div>
                                @endif
                                <input type="text" id="addr3" name="addr3" value="{{ old('addr3') }}" class="member-form-input3">
                            </td>
                        </tr>
                    @else
                        <input type="hidden" name="addr2" value="12001">
                        <input type="hidden" name="addr3" value="事業所メルマガ由来">
                    @endif
                @else
                    <input type="hidden" name="addr1" value="11">
                    <input type="hidden" name="addr2" value="11001">
                    <input type="hidden" name="addr3" value="雇用概要書由来">
                @endif
                <tr>
                    <th class="member-detail-item theme-bt-gr1">電話番号<img src="/woa/img/company/pc/icon_Required_form.png" width="30" height="18" alt="必須" class="imgRight"></th>
                    <td class="member-detail-comment theme-bt-gr1">
                        @if($errors->has('tel'))
                            <div class="error_message">{{ $errors->first('tel') }}</div>
                        @endif
                        <input type="text" id="tel" name="tel" value="{{ old('tel') }}" class="member-form-input3">
                    </td>
                </tr>
                <tr>
                    <th class="member-detail-item theme-bt-gr1">メールアドレス<img src="/woa/img/company/pc/icon_Required_form.png" width="30" height="18" alt="必須" class="imgRight"></th>
                    <td class="member-detail-comment theme-bt-gr1">
                        @if($errors->has('mail'))
                            <div class="error_message">{{ $errors->first('mail') }}</div>
                        @endif
                        <input type="text" id="mail" name="mail" value="{{ $mail }}" class="member-form-input3">
                    </td>
                </tr>
                <tr>
                    <th class="member-detail-item theme-bt-gr1  theme-bg-gray" >お問合せ内容<img src="/woa/img/company/pc/icon_Required_form.png" width="30" height="18" alt="必須" class="imgRight"></th>
                    <td class="member-detail-comment theme-bt-gr1">
                        @if($errors->has('inquiry'))
                            <div class="error_message">{{ $errors->first('inquiry') }}</div>
                        @endif
                        @foreach($inquiryList as $key => $value)
                            <label for="inquiry[{{ $key }}]"><input class="circle" type="checkbox" name="inquiry[]" id="inquiry[{{ $key }}]" value="{{ $key }}" onclick="control_regist_btn_company();" @if(old('inquiry') != '' && in_array($key, old('inquiry'))) checked="checked" @endif>{{ $value }}</label>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th class="member-detail-item theme-bt-gr1">備考</th>
                    <td class="member-detail-comment theme-bt-gr1">
                        <textarea rows=3 cols=24 name="inquiry_detail" id="inquiry_detail" class="member-form-input3">{{ old('inquiry_detail') }}</textarea>
                    </td>
                </tr>
            </table>

            <div class="member-detail center pdg-al15">
                <p class="txt-center mgn-b10" style="font-size: 11px;">「<a href="https://policy.bm-sms.co.jp/consumer/privacy/policy" target="_blank">個人情報保護方針</a>」に同意のうえ、下記ボタンを押してください。</p>
                <input type="submit" id="submit" name="submit" value="問い合わせる" class="btn-submit">
            </div>
        </form>
    </div>
    </section>
    <br>
    @include('pc.contents.include._footer_copyright')
    </main>

    <script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js?20180307"></script>
    <script type="text/javascript" src="/woa/js/common/SearchCity/jquery.SearchCity.js?20180307"></script>
    <script type="text/javascript" src="/woa/js/company/pc/empinuiry_form.js?20180307"></script>
</body>
</html>
