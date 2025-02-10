<!DOCTYPE html>
<html lang="ja">
<head>
<meta name="robots" content="noindex,nofollow">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
<meta name="format-detection" content="telephone=no">
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
.error_message {
    background: url(/woa/img/company/sp/ico_error.png) top left no-repeat;
    height: 15px;
    padding-left: 20px;
    color: #fe5252;
    font-weight: bold;
    font-size: 12px;
    padding-top: 2px;
}
.member-info-title {
    width: 312px;
}
</style>
    <link rel="icon" href="/woa/favicon.ico">
</head>

<body id="top" class="theme-txt-gray45">
    <main role="main">
    <div class="l-header-logo"><a href="https://www.jinzaibank.com/woa"><img src="/woa/images/logo.png" alt="ウィルワン"></a></div>

    <section class="sp-main theme-bg-gray theme-sb-gray" style="margin-top: 15px;">
    <div class="sp-inner-box">

        <form id="form" name="form1" action="/woa/empinquiry/optout" method="post" target="_self" enctype="application/x-www-url-encoded" onsubmit="disableButton();">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="action" value="{{ $action }}">
            <input type="hidden" name="account" value="{{ $account }}">
            <input type="hidden" name="order_tel_contact" value="{{ $order_tel_contact }}">
            <input type="hidden" name="sent_address" value="{{ $sent_address }}">
            <input type="hidden" name="shubetu" value="{{ $shubetu }}">
            <table class="member-detail theme-ba-gr1 center">
                <tr>
                    <th class="member-info-title">配信停止フォーム</th>
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
                    <th class="member-detail-item theme-bt-gr1">配信停止理由<img src="/woa/img/company/pc/icon_Required_form.png" width="30" height="18" alt="必須" class="imgRight"></th>
                    <td class="member-detail-comment theme-bt-gr1">
                            @if($errors->has('stop_reason'))
                            <div class="error_message">{{ $errors->first('stop_reason') }}</div>
                        @endif
                        <select name="stop_reason" class="selectBox" title="配信停止理由">
                                <option value=""></option>
                                    @foreach($stop_reason_options as $key => $value)
                                        <option label="{{ $value }}" value="{{ $key }}" @if(old('stop_reason')==$key) selected @endif>{{ $value }}</option>
                                    @endforeach
                        </select>
                    </td>
                </tr>
            </table>
            <div style="font-size:6px;text-align:center;">
                <br>ウィルワン（運営会社：エス・エム・エス）では<br>
                いただいた個人情報を適切に管理し、目的以外での利用は行いません。<br>
            </div>
            <div class="member-detail center pdg-al15">
                <input type="submit" id="submit" name="submit" value="配信停止" class="btn-submit">
            </div>
        </form>
    </div>
    </section>
    <br>
    @include('pc.contents.include._footer_copyright')
    </main>
    <script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js?20180307"></script>
</body>
</html>
