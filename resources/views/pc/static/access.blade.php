@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')

    @include('pc.mainparts.breadcrump')

    <h1 class="p-rule-head">アクセス</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">
                <div id="content" class="allpages">
                    <h2 class="c-title-l-bar" id="contents01">所在地</h2>
                    <h3 class="accessTitle02">株式会社エス・エム・エス</h3>
                    <p class="accessTxt01">東京都港区芝公園2-11-1住友不動産芝公園タワー <span class="accessImgMap"><a href="https://goo.gl/maps/UFHhdKWtQhPqnuNp8" target="_blank"><img src="/woa/img/access/img_map.png" width="95" height="19" alt="map" class="opa" /></a></span></p>
                    <h2 class="c-title-l-bar" id="contents01">アクセス</h2>
                    <h3 class="accessTitle02">公共交通機関を利用する場合</h3>
                    <ul class="ul_accessList01">
                        <li><span>都営三田線「芝公園駅」徒歩2分（出口A3から出てください）</span></li>
                        <li><span>都営大江戸線・都営浅草線「大門駅」徒歩6分（出口A3から出てください）</span></li>
                        <li><span>JR線・東京モノレール「浜松町駅」徒歩7分（金杉橋口から出てください）</span></li>
                        <li><span>都営大江戸線「赤羽橋駅」徒歩8分</span></li>
                    </ul>
                </div>
            </div>

            @include('pc.mainparts.normalsidebar')
        </div>
    </main>

    @include('pc.mainparts.bodyfooter')

    @include('pc.mainparts.topscript')
</body>
@endsection
