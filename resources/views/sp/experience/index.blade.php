@extends('sp.mainparts.head')

@section('content')
<body>
    @include('sp.mainparts.bodyheader')

    <h1 class="p-data-head">ウィルワンを利用された就職・転職者の方々</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

				<div class="u-text-c">
					<h2><img src="/woa/images/data_title.png" alt="ウィルワンを利用して就職・転職活動に成功しているのは、こんな方々です！"></h2>
					<p><img src="/woa/images/data_image.png"></p>
				</div>

				<div class="u-text-c u-mt40">
					<h3><img src="/woa/images/data_title2.png" alt="ウィルワンに頼んで良かったと思いますか？"></h3>
					<p><img src="/woa/images/data_graph1.jpg" alt="就職・転職成功者の92.5%がウィルワンに満足したと答えています。"></p>
					<p class="u-text-l">ウィルワンは、求人情報・ピンポイントな面接対策などあらゆるサポートで、求職者のみなさまの成功をお手伝いして、就職・転職者の満足度92.5%をいただきました。<br><br>
					みなさまの声をしっかり聞きながら、常により質の高い求人情報を提供してるので、みなさまに満足していただいています！</p>
				</div>

				<ul class="c-data u-mt40">
					<li class="c-data-item">
						<h2 class="c-title-l-blue u-mb0">ウィルワンのご利用の男女比率</h2>
						<div class="c-data-detail">
							<p class="u-p15"><img src="/woa/images/data_graph2.png"></p>
							<div class="c-data-txt matchHeight">ご利用されている男女の差はほとんどありません！<br>若干数、男性のご利用者が多いです。</div>
						</div>
					</li>
					<li class="c-data-item">
						<h2 class="c-title-l-blue u-mb0">新卒者と転職者の割合</h2>
						<div class="c-data-detail">
							<p class="u-p15"><img src="/woa/images/data_graph3.png"></p>
							<div class="c-data-txt matchHeight">割合としては、転職者の方が多いですが、新卒者の方もウィルワンを利用して就職活動を有利にすすめています。</div>
						</div>
					</li>
					<li class="c-data-item">
						<h2 class="c-title-l-blue u-mb0">ご利用いただいている年代</h2>
						<div class="c-data-detail">
							<p class="u-p15"><img src="/woa/images/data_graph4.png"></p>
							<div class="c-data-txt matchHeight">20代〜60代まで、幅広い年齢の方々にウィルワンをご利用いただいています。</div>
						</div>
					</li>
					<li class="c-data-item">
						<h2 class="c-title-l-blue u-mb0">ウィルワンの良いところは？</h2>
						<div class="c-data-detail">
							<p class="u-p15"><img src="/woa/images/data_graph5.png"></p>
							<div class="c-data-txt matchHeight">あなた専任のキャリアコンサルタントに気軽に相談できるのがウィルワンの特徴なんです。</div>
						</div>
					</li>
					<li class="c-data-item">
						<h2 class="c-title-l-blue u-mb0">就職先を選ぶ基準は何ですか？</h2>
						<div class="c-data-detail">
							<p class="u-p15"><img src="/woa/images/data_graph6.png"></p>
							<div class="c-data-txt matchHeight">就職・転職先で、どんな仕事ができるかが選ぶ第一の基準。でも、給与や休暇などの待遇もやっぱり大切ですね。</div>
						</div>
					</li>
					<li class="c-data-item">
						<h2 class="c-title-l-blue u-mb0">面接対策で大事にしたこと</h2>
						<div class="c-data-detail">
							<p class="u-p15"><img src="/woa/images/data_graph7.png"></p>
							<div class="c-data-txt matchHeight">緊張しないよう人前で話して慣れておくことは面接対策で、とっても重要！ウィルワンは面接対策もバッチリです。</div>
						</div>
					</li>
				</ul>

				<div class="u-text-c u-mt40 u-pb20">
					<h2><img src="/woa/images/data_title3.png" alt="この制度を使わないのはソンです！"></h2>
					<ul class="c-data-service">
						<li class="c-data-service-item"><a href="{{ route('ServiceFree')}}"><img src="/woa/images/data_service1.png"></a></li>
						<li class="c-data-service-item"><a href="{{ route('ServiceFind')}}"><img src="/woa/images/data_service2.png"></a></li>
						<li class="c-data-service-item"><a href="{{route('ServiceFeature')}}"><img src="/woa/images/data_service4.png"></a></li>
					</ul>
				</div>

                <h2 class="c-title-l-bar">実際にウィルワンを利用された体験談はコチラ！</h2>
                <ul class="c-experience">
                    @foreach( $blogdata as $value)
                    <li class="c-experience-item"><a href="{{ route('TensyokuDetail', ['id' => $value->id])}}" class="c-experience-link">
                        <div class="c-experience-img" style="background-image:url({{ getS3ImageUrl(config('const.blog_image_path') . $value->list_image) }})"></div>
                        <div class="c-experience-detail">
                            <h3 class="c-experience-title">{{ $value->title}}</h3>

                        </div>
                    </a></li>
                    @endforeach

                </ul>

				<div class="c-button-wrap-color"><a href="{{ route('TensyokuList')}}" class="c-button">全ての体験談を見る</a></div>

            </div>



             @include('sp.mainparts.experiencetopsidebar')


        </div>

    </main>

    @include('sp.mainparts.bodyfooter')

@include('sp.mainparts.topscript')
</body>
@endsection
