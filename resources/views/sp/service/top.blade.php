@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')
    <h1 class="p-service-head">治療家向けのお仕事紹介して20年！ウィルワンエージェント</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

                <div class="c-img-box"><img src="/woa/images/service1.png"></div>
                <div class="p-service-button-wrap-color"><a href="{{ route('ServiceFree') }}" class="c-button">もっと詳しく見る</a></div>

                <div class="c-img-box"><img src="/woa/images/service2.png"></div>
                <div class="p-service-button-wrap-color"><a href="{{ route('ServiceFind') }}" class="c-button">もっと詳しく見る</a></div>

                <div class="c-img-box"><img src="/woa/images/service3.png"></div>
                <div class="p-service-button-wrap-color"><a href="{{ route('ServiceFeature') }}" class="c-button">もっと詳しく見る</a></div>

            </div>

            @include('sp.mainparts.normalsidebar')
        </div>
    </main>
@include('sp.mainparts.bodyfooter')

@include('sp.mainparts.topscript')
</body>
@endsection
