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

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

{{-- ヘッダーフッター共通化によって崩れたデザイン修正 --}}
<style type="text/css">
select {
    -webkit-appearance: menulist;
    width: auto;
    height: auto;
}
.member-detail-item {
    font-size: 15px;
    padding: .5em .9em;
}
.font-size26 {
    font-size: 26px;
}
.imgRight {
    float: right;
    margin: 2px 6px 0 0;
}
.pdg-tb20 {
    padding: 1.429em;
}
.member-detail input{
    font-size: 15px;
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
.mgn-b10 {
    margin-bottom: .714em;
}
.txt-center {
    text-align: center;
}
h4{
    font-weight: 700;
    font-size: 12px;
    color: #222;
}
[type=text], textarea{
   box-shadow:none;
}
.member-detail textarea{
    font-size: inherit;
    -webkit-appearance: textarea;
    -webkit-rtl-ordering: logical;
    flex-direction: column;
    line-height: inherit;
    cursor: text;
    white-space: pre-wrap;
    word-wrap: break-word;
}
.mainConts{
    padding-top: 30px;
}
.member-detail-comment .col {
        padding:    1px;
}
/*.topCont{
    margin-top: 55px;
}*/
.description{
    background-color: #f5f2ed;
    font-family: メイリオ;
    font-size: 26px;
    padding: 25px 9px 21px 26px;
    border-radius: 16px;
    margin-top: 30px;
}

.fa-check-square{
    color:  #e27676;
}

[type='text']:focus, textarea:focus {
    outline: none;
    border: 1.5px solid #14a5eb;qa
    background-color: #fefefe;
    box-shadow: 0 0 5px #cacaca;
    transition: box-shadow 0.5s, border-color 0.25s ease-in-out;
}
</style>
    <link rel="icon" href="/woa/favicon.ico">
</head>
<body>
    <main role="main">
    <section class="l-main">
        <div class="header" style="position: static; text-align: left; padding-left:33px">
            <img src="/woa/images/logo.png" class="header-logo">
                <div style="font-size:27px">採用ご担当者様へ</div>
        </div>

        <div class="mainConts">
                <div class="description"><i class="far fa-check-square "></i><span style="padding-left: 10px;"><b>ウィルワン</b>は事業所と治療家・セラピストのマッチングをサポートする<p style="padding:0 0 0 33px"><b style="color: #3184ce">就職支援サービス</b>です。</p></span>
                    <p style="font-size: 16px; padding: 0 0 0 14px;"><i class="far fa-edit"></i><span>下記フォームにてお問い合わせください。詳細をご案内します。</span></p>
                </div>

            <div class="contArea" style="padding:0px;margin:0px;margin-top:-30px;">
                <div class="inner-box" style="width:95%;margin:10px auto .714em;">
                    <form id="form" name="form1" action="/woa/empinquiry/comp" method="post" target="_self" enctype="application/x-www-url-encoded" onsubmit="disableButton();">
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
                            @if(mb_strpos($action,'employment_o_s') === FALSE)
                                <tr>
                                    <th class="font-size26 txt-normal txt-left pdg-b10" colspan="2" style="background: #fff;"><i class="fa fa-star theme-txt-softred"></i> 貴社情報&nbsp;</th>
                                </tr>
                                <tr>
                                    <th class="member-detail-item pdg-tb20 theme-bt-gr1">貴社名<img src="/woa/img/company/pc/icon_Required2.png" width="30" height="18" alt="必須" class="imgRight"></th>
                                    <td class="member-detail-comment pdg-tb20 theme-bt-gr1"><input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" class="member-form-input" placeholder="例：◯◯◯会社">
                                    </td>
                                </tr>
                            @else
                                <input type="hidden" name="company_name" value="雇用概要書由来">
                            @endif
                            @if(mb_strpos($action,'woa_jg_mailmg') === FALSE)
                                <tr>
                                    <th class="member-detail-item pdg-tb20 theme-bt-gr1">部署名<img src="/woa/img/company/pc/icon_Required2.png" width="30" height="18" alt="必須" class="imgRight"></th>
                                    <td class="member-detail-comment pdg-tb20 theme-bt-gr1"><input type="text" id="division_name" name="division_name" value="{{ old('division_name') }}" class="member-form-input" placeholder="例：△△△部"  >
                                    </td>
                                </tr>
                            @else
                                <input type="hidden" name="division_name" value="事業所メルマガ由来">
                            @endif
                            <tr>
                                <th class="member-detail-item pdg-tb20 theme-bt-gr1">担当者名<img src="/woa/img/company/pc/icon_Required2.png" width="30" height="18" alt="必須" class="imgRight"></th>
                                <td class="member-detail-comment pdg-tb20 theme-bt-gr1"><input type="text" id="name_kan" name="name_kan" value="{{ old('name_kan') }}" class="member-form-input" placeholder="例：〇〇花子">
                                </td>
                            </tr>
                            @if(mb_strpos($action,'woa_jg_mailmg') === FALSE)
                                <tr>
                                    <th class="member-detail-item pdg-tb20 theme-bt-gr1">フリガナ<img src="/woa/img/company/pc/icon_Required2.png" width="30" height="18" alt="必須" class="imgRight"></th>
                                    <td class="member-detail-comment pdg-tb20 theme-bt-gr1"><input type="text" id="name_cana" name="name_cana" value="{{ old('name_cana') }}" class="member-form-input" placeholder="例：マルマルタロウ">
                                    </td>
                                </tr>
                            @else
                                <input type="hidden" name="name_cana" value="事業所メルマガ由来">
                            @endif
                            @if(mb_strpos($action,'employment_o_s') === FALSE)
                                    @if(mb_strpos($action,'woa_jg_mailmg') === FALSE)
                                    <tr>
                                        <th class="member-detail-item pdg-tb20 theme-bt-gr1">郵便番号</th>
                                        <td class="member-detail-comment pdg-tb20 theme-bt-gr1">
                                            <input type="text" id="zip" name="zip" class="member-form-input2" placeholder="例：1234567">
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th class="member-detail-item pdg-tb20 theme-bt-gr1">都道府県<img src="/woa/img/company/pc/icon_Required2.png" width="30" height="18" alt="必須" class="imgRight"></th>
                                        <td class="member-detail-comment pdg-tb20 theme-bt-gr1"><select id="addr1" name="addr1" >
                                            <option value="">選択してください</option>
                                            @foreach($prefectureList as $value)
                                                <option label="{{ $value->addr1 }}" value="{{ $value->id }}" @if($addr1 == $value->id) selected @endif>{{ $value->addr1 }}</option>
                                            @endforeach
                                        </select></td>
                                    </tr>
                                    @if(mb_strpos($action,'woa_jg_mailmg') === FALSE)
                                        <tr>
                                            <th class="member-detail-item pdg-tb20 theme-bt-gr1">市区町村<img src="/woa/img/company/pc/icon_Required2.png" width="30" height="18" alt="必須" class="imgRight"></th>
                                            <td class="member-detail-comment pdg-tb20 theme-bt-gr1">
                                                <select class="selectBox" id="addr2" name="addr2">
                                                    <option value="">選択してください</option>
                                                    @foreach($cityList as $value)
                                                        <option label="{{ $value->addr2 }}" value="{{ $value->id }}" @if(old('addr2') == $value->id) selected @endif>{{ $value->addr2 }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" id="form_addr2" value="{{ $addr2 }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="member-detail-item pdg-tb20 theme-bt-gr1">番地・建物名等<img src="/woa/img/company/pc/icon_Required2.png" width="30" height="18" alt="必須" class="imgRight"></th>
                                            <td class="member-detail-comment pdg-tb20 theme-bt-gr1">
                                                <input type="text" id="addr3" name="addr3" value="{{ old('addr3') }}" class="member-form-input" placeholder="例：1-2-3　AAビル1F">
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
                                <th class="member-detail-item pdg-tb20 theme-bt-gr1">電話番号<img src="/woa/img/company/pc/icon_Required2.png" width="30" height="18" alt="必須" class="imgRight"></th>
                                <td class="member-detail-comment pdg-tb20 theme-bt-gr1">
                                    <input type="text" id="tel" name="tel" value="{{ old('tel') }}" class="member-form-input" placeholder="例：09012345678">
                                    <span class="notice">※半角数字ハイフンなし ※携帯電話番号を推奨しています。</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="member-detail-item pdg-tb20 theme-bt-gr1">メールアドレス<img src="/woa/img/company/pc/icon_Required2.png" width="30" height="18" alt="必須" class="imgRight"></th>
                                <td class="member-detail-comment pdg-tb20 theme-bt-gr1"><input type="text" id="mail" name="mail" value="{{ $mail }}" class="member-form-input" placeholder="例：aaa@aaa.ne.jp" >
                                </td>
                            </tr>
                            <tr>
                                <th class="member-detail-item pdg-tb20 theme-bt-gr1">お問合せ内容<img src="/woa/img/company/pc/icon_Required2.png" width="30" height="18" alt="必須" class="imgRight"></th>
                                <td class="member-detail-comment pdg-tb20 theme-bt-gr1">
                                    @foreach((array)$inquiryList as $key => $value)
                                    <div class="col">
                                        <input type="checkbox" name="inquiry[]" id="inquiry_{{ $key }}" value="{{ $key }}" @if(old('inquiry') != '' && in_array($key, old('inquiry'))) checked="checked"'@endif class="checkboxColxxx">
                                        <label for="inquiry_{{ $key }}" class="checkbox">{{ $value }}</label>
                                    </div>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th class="member-detail-item pdg-tb20 theme-bt-gr1 theme-bb-gr1">備考<br/>(フリーコメント記入欄)</th>
                                <td class="member-detail-comment pdg-tb20 theme-bt-gr1 theme-bb-gr1">
                                    <textarea rows=3 cols=40 name="inquiry_detail" id="inquiry_detail" class="member-form-input3" style="width:100%">{{ old('inquiry_detail') }}</textarea>
                                </td>
                            </tr>
                        </table>
                        <div class="member-detail center pdg-al15">
                            <p class="txt-center mgn-b10">「<a href="https://policy.bm-sms.co.jp/consumer/privacy/policy" target="_blank">個人情報保護方針</a>」に同意のうえ、下記ボタンを押してください。</p>
                            <input type="submit" id="submit_btn" name="submit_btn" value="問い合わせる" class="btn-submit" style="width:30%;text-align:center;display:block;">
                        </div>
                    </form>
                </div>

                <!-- サービスの流れ -->
                <div class="inner-box" style="width:95%;margin:10px auto .714em;">
                    <div class="serviceFlow">
                        <h3><img src="/woa/img/company/pc/img_serviceFlowTtl.gif" alt="サービスの流れ" width="876" height="36" /></h3>
                        <ul class="clear">
                            <li class="step01">
                                <dl>
                                    <dt><img src="/woa/img/company/pc/img_step01.gif" alt="step1" width="203" height="22" /></dt>
                                    <dd class="heightLine-stepTxt"><span>お問い合わせ</span>登録フォーム、またはお電話にてお問い合わせください。サービスの詳細をご説明いたします。<br />登録手数料や前金等は一切ありません。</dd>
                                </dl>
                            </li>
                            <li class="step02">
                                <dl>
                                    <dt><img src="/woa/img/company/pc/img_step02.gif" alt="step2" width="203" height="22" /></dt>
                                    <dd class="heightLine-stepTxt"><span>人材のご紹介</span>弊社担当者が仕事内容や雇用条件をもとに最適な人材をご紹介いたします。</dd>
                                </dl>
                            </li>
                            <li class="step03">
                                <dl>
                                    <dt><img src="/woa/img/company/pc/img_step03.gif" alt="step3" width="203" height="22" /></dt>
                                    <dd class="heightLine-stepTxt"><span>面接</span>面接の日時調整から双方の条件調整まで弊社担当者が責任をもって対応いたします。</dd>
                                </dl>
                            </li>
                            <li class="step04">
                                <dl>
                                    <dt><img src="/woa/img/company/pc/img_step04.gif" alt="step4" width="222" height="22" /></dt>
                                    <dd class="heightLine-stepTxt"><span>採用の成立</span>貴社と求職者の双方が内定・入社に同意された時点で採用成立となります。採用成立後、成功報酬をいただいています。決定後も入社日調整や初日の持参物確認など、随時サポートいたします。</dd>
                                </dl>
                            </li>
                        </ul>
                        <p class="flowIcon"><img src="/woa/img/company/pc/img_icon.gif" alt="採用成立まで無料" width="72" height="67" /></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </main>
    <div class="l-discription theme-ba-gr1 mgn-tb10" style="width:920px;">
        <h4>ウィルワンの運営をしております株式会社エス・エム・エスでは、治療家・セラピスト以外のご職業のマッチングもサポートしております。<br/>
                    下記のバナーよりお気軽にお問い合わせください。もしくは<a href="#top">上記フォーム</a>より、「お問合せ内容詳細」に他職種の採用についての問い合わせである旨をご記入いただければ、弊社担当者よりご連絡させていただきます。<br/><br/>

        </h4>

        <div style="padding-left: 110px">＜お問い合わせ先はこちら＞</div>
        <div style="text-align: center" >
            <a href="https://www.jinzaibank.com/kjb/for_customer/index.html?action=woa_group_link" target="_blank"><img src="/woa/img/company/pc/logo.jpg" style="margin-top: 5px; width: 100px;"/></a>&nbsp;&nbsp;
            <a href="https://www.jinzaibank.com/hjb/for_customer/index.html?action=woa_group_link" target="_blank"><img src="/woa/img/company/pc/logo_hjb.jpg" style="margin-top: 5px; width: 100px"/></a>&nbsp;&nbsp;
            <a href="https://www.jinzaibank.com/mejb/for_customer/index.html?action=woa_group_link"  target="_blank"><img src="/woa/img/company/pc/logo_mejb.png" style="width: 135px"/></a>&nbsp;&nbsp;
            <a href="https://eiyo.jinzaibank.com/empinquiry?action=woa_group_link" target="_blank"><img src="/woa/img/company/pc/logo_EJB.png" style="width: 100px"/></a>&nbsp;&nbsp;
            <a href="https://www.kaigoagent.com/empinquiry?action=woa_group_link" target="_blank"><img src="/woa/img/company/pc/logo_KJA.png" style="margin-top: -10px; width: 140px"/></a>&nbsp;&nbsp;
        </div>
        <div style="text-align: center; margin-top: 10px;">
            <a href="https://www.nursejinzaibank.com/customer?action=woa_group_link" target="_blank"><img src="/woa/img/company/pc/logo_NJB.jpg" style="margin-top: -5px; width: 190px"/></a>&nbsp;&nbsp;&nbsp;
            <a href="https://www.ptotjinzaibank.com/for_customer/service/index.html?action=woa_group_link" target="_blank"><img src="/woa/img/company/pc/ptot_logo.gif" style="margin-top: 10px; width: 150px"/></a>&nbsp;&nbsp;&nbsp;
            <a href="https://www.carejinzaibank.com/for_customer/service/index.html?action=woa_group_link" target="_blank"><img src="/woa/img/company/pc/cjblogo.gif" style="margin-top: 10px; width: 190px"/></a>&nbsp;&nbsp;&nbsp;
            <a href="https://hoiku.jinzaibank.com/empinquiry?action=woa_group_link" target="_blank"><img src="/woa/img/company/pc/logo-HOJB.png" style="margin-top: 10px; width: 230px"/></a>
        </div>
    </div>

    <div class="l-footer-logo" style="text-align: center"><img src="/woa/images/logo.png" alt=""></div>
    </div>
    <div class="l-footer-copy-wrap">
        <div class="l-footer-copy"><small>(C) SMS CO., LTD. All Rights Reserved.</small></div>
    </div>

    <script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js?20180307"></script>
    <script type="text/javascript" src="/woa/js/common/SearchCity/jquery.SearchCity.js?20180307"></script>
    <script type="text/javascript" src="/woa/js/company/pc/empinuiry_form.js?20180307"></script>
</body>
</html>
