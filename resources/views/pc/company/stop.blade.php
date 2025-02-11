@extends('pc.mainparts.head')
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="robots" content="noindex,nofollow">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>柔道整復師、鍼灸師、マッサージ師の求人・就職支援ならウィルワン</title>

    <link rel="stylesheet" href="/woa/css/company/pc/layout.css">
    <link rel="stylesheet" href="/woa/css/company/pc/theme.css">
    <link rel="stylesheet" href="/woa/css/company/pc/module.css">
    <link rel="stylesheet" href="/woa/css/company/pc/sp_module.css">
    <link rel="stylesheet" href="/woa/css/company/pc/kjb_module.css">
    <link rel="stylesheet" href="/woa/css/company/pc/for_customer.css">

    {{-- ヘッダーフッター共通化によって崩れたデザイン修正 --}}
    <style type="text/css">
        select {
            -webkit-appearance: menulist;
            width: auto;
            height: auto;
        }
        .member-detail-item {
            font-size: 18px;
            padding: .5em .9em;
        }
        .member-detail input[type="text"], select{
            font-size: 18px;
        }
        .member-detail input[type="submit"]{
            font-size: 22px;
            padding: .8em;
            font-family: "Lucida Grande", Meiryo, "メイリオ", "Hiragino Kaku Gothic ProN", "ヒラギノ角ゴ ProN W3", "ＭＳ Ｐゴシック", sans-serif
        }
        .font-size24 {
            font-size: 24px;
        }
        .imgRight {
            float: right;
            margin: 2px 6px 0 0;
        }
        .pdg-tb20 {
            padding: 1.429em;
        }
        .row{
            max-width: 100%;
        }
        table tbody, table tfoot, table thead{
            border: 0px solid #f1f1f1;
        }
        table tbody tr:nth-child(even) {
            background-color: #fefefe;
        }
        label{
            color: #7c7a6a;
        }
        .contArea {
            margin: 0;
            padding: 30px 0;
        }
        .contArea #inputForm {
           height: 240px;
           padding: 0;
	/* padding: 0 148px 0 108px;
	margin: 0 20px 30px; */
}
</style>
    <link rel="icon" href="/woa/favicon.ico">
</head>
<body>
    <header role="banner">
        <div class="row">
            <div class="large-24 columns">

                <div class="row align-middle">
                    <div class="large-8 columns">
                        <div class="row align-middle">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main role="main">
        <section class="l-main">

            <div id="mainContent_stop" style="float:none; padding-top:55px">

                <div>
                    <img src="/woa/images/logo.png" class="header-logo">
                </div>

                <div class="contArea" style="margin-top: 20px;">
                        <div class="inner-box" style="width:95%;margin:10px auto .714em;">
                            <form id="form" name="form1" action="/woa/empinquiry/optout" method="post" target="_self" enctype="application/x-www-url-encoded" onsubmit="disableButton();">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="action" value="{{ $action }}">
                                <input type="hidden" name="account" value="{{ $account }}">
                                <input type="hidden" name="order_tel_contact" value="{{ $order_tel_contact }}">
                                <input type="hidden" name="sent_address" value="{{ $sent_address }}">
                                <input type="hidden" name="shubetu" value="{{ $shubetu }}">
                                @if(count($errors))
                                <ul class="error-messageB theme-txt-red no-liststyle">
                                    @foreach ($errors->all() as $error)
                                    <li><span class="required_error">※{{ $error }}</span></li>
                                    @endforeach
                                </ul>
                                @endif
                                <table class="member-detail" id="inputForm">
                                    <tr>
                                        <td colspan="2" class="member-detail-common theme-bt-gr1 font-size24" style="border-top: none;">配信停止フォーム</td>
                                    </tr>
                                    <tr>
                                        <th class="member-detail-item pdg-tb20 theme-bt-gr1">メールアドレス<img src="/woa/img/company/pc/icon_Required2.png" width="30" height="18" alt="必須" class="imgRight"></th>
                                        <td class="member-detail-comment pdg-tb20 theme-bt-gr1">
                                            <input type="text" id="mail" name="mail" value="{{ $mail }}" class="member-form-input" placeholder="例：aaa@aaa.ne.jp" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="member-detail-item pdg-tb20 theme-bt-gr1 theme-bb-gr1">配信停止理由<img src="/woa/img/company/pc/icon_Required2.png" width="30" height="18" alt="必須" class="imgRight"></th>
                                        <td class="member-detail-comment pdg-tb20 theme-bt-gr1 theme-bb-gr1">
                                            <select name="stop_reason" class="selectBox" title="配信停止理由">
                                                <option value=""></option>
                                                @foreach($stop_reason_options as $key => $value)
                                                <option label="{{ $value }}" value="{{ $key }}" @if(old('stop_reason')==$key) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                                <div style="text-align:center; font-size:11px;">
                                    ウィルワン（運営会社：エス・エム・エス）では<br>
                                    いただいた個人情報を適切に管理し、目的以外での利用は行いません。
                                </div>
                                <div class="member-detail center pdg-al15">
                                    <input type="submit" id="submit_btn" name="submit_btn" value="配信停止" class="btn-submit" style="width:30%;text-align:center;display:block;">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @include('pc.contents.include._footer_copyright')
            </section>
        </main>
        <script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js?20180307"></script>
    </body>
    </html>
