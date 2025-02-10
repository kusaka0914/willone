@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')
    <h1 class="p-service-detail-head">希望にあわせた<br>ピンポイント！面接対策</h1>

    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

				<div class="p-service-detail-main">
					<div><img src="/woa/images/service_feature_image.png"></div>
					<p class="u-p15 u-mt0">
						求人サイトなどではわからない<span class="c-text-point">希望就職先の雰囲気や採用担当者の人柄などの情報</span>をしっかり面接対策でお伝えします。
					</p>
				</div>

				<div class="p-service-detail-arrow">
					<i class="fa fa-caret-down" aria-hidden="true"></i>
					<i class="fa fa-caret-down" aria-hidden="true"></i>
					<i class="fa fa-caret-down" aria-hidden="true"></i>
				</div>

				<h2 class="c-title-l u-mt0" id="contents01"><span class="point">〜自信を持って面接にのぞめる〜</span><br>ウィルワンだからできる面接対策</h2>

				<div class="c-textbox">
                    <div class="c-textbox-title"><i class="fa fa-check-square" aria-hidden="true"></i> 求人先が求める人物像を細かくアドバイス</div>
                    <div class="c-textbox-detail">
                        <p class="c-textbox-text">
                            <img class="c-textbox-img" src="/woa/images/service_feature_photo1.jpg" alt="">
							ウィルワンでは、面接マニュアルにあるような表面的な面接対策は行いません。<br>
							<br>
							事前に求人先から要望を聞いているので、求人先の方がどんな人に来て欲しいのか、どんなスキルを必要とされているのか把握しています。なので、<span class="u-bold">希望求人先に合わせたピンポイントな面接対策が可能</span>です。
                        </p>
                    </div>
                </div>

				<p><img src="/woa/images/service_feature1.jpg"></p>

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
