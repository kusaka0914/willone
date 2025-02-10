@extends('pc.mainparts.head')

@section('content')
<link href="{{addQuery('/woa/css/static/jobchangeagent.css')}}" rel="stylesheet" type="text/css">

<body>
    @include('pc.mainparts.bodyhead')

    @include('pc.mainparts.breadcrump')

    <h1 class="p-guide-head">就業中(既卒)の方へ</h1>

    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">
                <main class="main guideIndex-main">
                    <img src="/woa/img/jobchangeagent/a1.png" alt="ウイルワンエージェントって、なに？" loading="lazy">
                    <img src="/woa/img/jobchangeagent/a2.png" alt="こんなお悩みありませんか？" loading="lazy">
                    <img src="/woa/img/jobchangeagent/a3.png" alt="何となーく求人が見たい" loading="lazy">
                    <div class="toggle-box">
                        <img src="/woa/img/jobchangeagent/arrow-down-tap.png" alt="矢印" loading="lazy" class="toggle-btn toggle-btn-top">
                    </div>
                    <img src="/woa/img/jobchangeagent/a5.png" alt="サンクスメッセージ" loading="lazy" class="toggle-target">
                    <img src="/woa/img/jobchangeagent/6.png" alt="空白" loading="lazy">
                    <img src="/woa/img/jobchangeagent/7.png" alt="今は見てるだけ" loading="lazy">
                    <div class="toggle-box">
                        <img src="/woa/img/jobchangeagent/arrow-down.png" alt="矢印" loading="lazy" class="toggle-btn">
                    </div>
                    <img src="/woa/img/jobchangeagent/9.png" alt="サンクスメッセージ" loading="lazy" class="toggle-target">
                    <img src="/woa/img/jobchangeagent/10.png" alt="空白" loading="lazy">
                    <img src="/woa/img/jobchangeagent/11.png" alt="時間をかけず良い求人だけ知りたい" loading="lazy">
                    <div class="toggle-box">
                        <img src="/woa/img/jobchangeagent/arrow-down.png" alt="矢印" loading="lazy" class="toggle-btn">
                    </div>
                    <img src="/woa/img/jobchangeagent/13.png" alt="サンクスメッセージ" loading="lazy" class="toggle-target">
                    <img src="/woa/img/jobchangeagent/14.png" alt="今ご転職の意思がなくてもOK" loading="lazy">
                    <a href="https://www.jinzaibank.com/woa/glp/PC_org_001?action=pcor-guide-btn" class="cv-btn">
                        <img src="/woa/img/jobchangeagent/15.png" alt="転職アドバイサーに無料で相談してみる" loading="lazy">
                    </a>
                    <img src="/woa/img/jobchangeagent/16.png" alt="帯" loading="lazy">
                    <img src="/woa/img/jobchangeagent/17.png" alt="転職成功の流れ" loading="lazy">
                    <img src="/woa/img/jobchangeagent/18.png" alt="よくある質問" loading="lazy">
                    <img src="/woa/img/jobchangeagent/19.png" alt="本当に転職するのかどうか決めていないのですが、こちらのサービスを使っても良いのでしょうか" loading="lazy">
                    <div class="toggle-box">
                        <img src="/woa/img/jobchangeagent/arrow-down.png" alt="矢印" loading="lazy" class="toggle-btn">
                    </div>
                    <img src="/woa/img/jobchangeagent/21.png" alt="全く問題ございません" loading="lazy" class="toggle-target">
                    <img src="/woa/img/jobchangeagent/22.png" alt="空白" loading="lazy">
                    <img src="/woa/img/jobchangeagent/23.png" alt="サービスを利用したいのですが仕事をしているのであまり時間を取れそうにありません…。" loading="lazy">
                    <div class="toggle-box">
                        <img src="/woa/img/jobchangeagent/arrow-down.png" alt="矢印" loading="lazy" class="toggle-btn">
                    </div>
                    <img src="/woa/img/jobchangeagent/25.png" alt="弊社は皆さまにご負担をかけないことを第一としておりますので、公式LINEでメッセージのやり取りが可能です！" loading="lazy" class="toggle-target insight-b">
                    <img src="/woa/img/jobchangeagent/26.png" alt="空白" loading="lazy">
                    <img src="/woa/img/jobchangeagent/27.png" alt="求人サイトでも検索したら求人がたくさん出てくると思いますが、ウイルワンのサービスは求人サイトとは何が違うのでしょうか？" loading="lazy">
                    <div class="toggle-box">
                        <img src="/woa/img/jobchangeagent/arrow-down.png" alt="矢印" loading="lazy" class="toggle-btn">
                    </div>
                    <img src="/woa/img/jobchangeagent/29.png" alt="私たちの強みは、皆さまの希望にあった求人を厳選してご紹介することです。" loading="lazy" class="toggle-target insight-b">
                    <img src="/woa/img/jobchangeagent/30.png" alt="空白" loading="lazy">
                    <img src="/woa/img/jobchangeagent/31.png" alt="一般企業を扱う他の転職エージェントも利用しています。" loading="lazy">
                    <div class="toggle-box">
                        <img src="/woa/img/jobchangeagent/arrow-down.png" alt="矢印" loading="lazy" class="toggle-btn">
                    </div>
                    <img src="/woa/img/jobchangeagent/33.png" alt="全く問題ございません" loading="lazy" class="toggle-target">
                    <img src="/woa/img/jobchangeagent/34.png" alt="今ご転職の意思がなくてもOK" loading="lazy">
                    <a href="https://www.jinzaibank.com/woa/glp/PC_org_001?action=pcor-guide-btn" class="cv-btn">
                        <img src="/woa/img/jobchangeagent/35.png" alt="転職アドバイサーに無料で相談してみる" loading="lazy">
                    </a>
                    <img src="/woa/img/jobchangeagent/36.png" alt="ウィルワンエージェント" loading="lazy">
                </main>
            </div>

            @include('pc.mainparts.normalsidebar')

        </div>
    </main>

    @include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
<script src="{{addQuery('/woa/js/jobchangeagent.js')}}"></script>

</body>
@endsection
