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

            </div>
        </section>
    </main>

    <div class="mgn-t20"></div>

    <section class="l-main theme-bg-diagonal">
        <h2 class="title-bar">配信停止登録完了</h2>
        <div class="inner-box">
            <div class="form-box-inner">
                <div class="form-box theme-bg-white" style="text-align:center;width:100%;padding:0;">
                    <div class="form-box-inner">
                        <h1 class="font-size26 txt-normal pdg-b10 txt-center" style="font-size: 27px"><i class="fa fa-check theme-txt-softred"></i>配信停止の登録が完了しました</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    @include('pc.contents.include._footer_copyright')
    @include('common._tag_willcloud')
</body>
</html>
