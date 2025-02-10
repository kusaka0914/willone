@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')
    @include('pc.mainparts.breadcrump')

    <h1 class="p-service-head">治療家向けのお仕事紹介して20年！ウィルワンエージェント</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

				<p><img src="/woa/images/service1.png"></p>
				<div class="p-service-button-wrap-color"><a href="{{ route('ServiceFree') }}" class="c-button">もっと詳しく見る</a></div>

				<p><img src="/woa/images/service2.png"></p>
				<div class="p-service-button-wrap-color"><a href="{{ route('ServiceFind')}}" class="c-button">もっと詳しく見る</a></div>

				<p><img src="/woa/images/service3.png"></p>
				<div class="p-service-button-wrap-color"><a href="{{ route('ServiceFeature') }}" class="c-button">もっと詳しく見る</a></div>

            </div>

            @include('pc.mainparts.servicesidebar')
        </div>
    </main>
@include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
</body>
@endsection
