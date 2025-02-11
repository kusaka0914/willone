@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')
    <h1 class="p-service-detail-head">登録後は待っているだけでOK！<br>学業や仕事と就職・転職活動が両立できます！</h1>

    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

				<div class="p-service-detail-main">
					<div><img src="/woa/images/service_find_image.png"></div>
					<p class="u-p15 u-mt0">
						ウィルワンのエージェントがあなたのプロフィールを元に、<span class="c-text-point">持っているスキルを活かせる求人情報やご希望条件に合う求人情報だけを</span>あなたに最適な就職・転職先としてご提供しています。<br>在職中で転職をお考えの方や忙しくて思うように時間が取れない方でも、効率的な情報収集。就職、転職活動が行えます。
					</p>
				</div>

				<div class="p-service-detail-arrow">
					<i class="fa fa-caret-down" aria-hidden="true"></i>
					<i class="fa fa-caret-down" aria-hidden="true"></i>
					<i class="fa fa-caret-down" aria-hidden="true"></i>
				</div>

				<h2 class="c-title-l u-mt0" id="contents01"><span>登録するだけで</span><br>あなたに最適な就職先をご案内</h2>

				<p><img src="/woa/images/service_fine1_sp.jpg"></p>
				<div class="p-service-detail-arrow">
					<i class="fa fa-caret-down" aria-hidden="true"></i>
					<i class="fa fa-caret-down" aria-hidden="true"></i>
					<i class="fa fa-caret-down" aria-hidden="true"></i>
				</div>

				<div class="c-img-box u-text-c u-mt0"><img src="/woa/images/service_fine2.jpg" class="c-img-responsive"></div>

				<hr>

				@include('sp.service.link')

            </div>

            @include('sp.mainparts.normalsidebar')
        </div>
    </main>
    @include('sp.mainparts.bodyfooter')

@include('sp.mainparts.topscript')
</body>
@endsection
