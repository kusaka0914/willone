@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')
    @include('pc.mainparts.breadcrump')
    <h1 class="p-service-detail-head">完全無料で、しっかりサポート！</h1>

    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

				<div class="p-service-main">
					<div><img src="/woa/images/service_free_image.jpg"></div>
					<p class="u-p15 u-mt0">
						ウィルワンなら<span class="c-text-point">就職・転職支援のサービスを完全無料</span>でご利用いただけます。<br>
						「ホントに無料なの?」「急に費用を請求されたりしないですか？」という質問をよくいただきますが、ご安心ください。治療家・セラピストに特化した就職支援の専門スタッフが、あなたの理想の就職・転職を<span class="c-text-point">最後までしっかりサポート</span>いたします。
					</p>
				</div>

				<div class="p-service-detail-arrow">
					<i class="fa fa-caret-down" aria-hidden="true"></i>
					<i class="fa fa-caret-down" aria-hidden="true"></i>
					<i class="fa fa-caret-down" aria-hidden="true"></i>
				</div>

				<h2 class="c-title-l u-mt0" id="contents01"><span class="point">～治療家に特化して20年～</span><br>ウィルワンの就職・転職サポート</h2>

				<div class="c-textbox">
                    <div class="c-textbox-detail">
                        <p class="c-textbox-text">
                            <img class="c-textbox-img" src="/woa/images/service_free_photo1.jpg" alt="">
                            ウィルワンには日々、多くの治療院・サロンから人材紹介のご依頼をいただいています。<br>その理由は業界に特化した人材を紹介してきた長年の信頼と実績があるからです。<span class="u-bold">人気の求人情報、高収入・好待遇の求人情報など一般には募集されないような求人情報を含む求人数</span>の中から選ぶ事ができます。
                        </p>
                    </div>
                </div>

				<div class="c-textbox">
                    <div class="c-textbox-title"><i class="fa fa-check-square" aria-hidden="true"></i> 就職・転職支援のプロがあなた専任でサポート</div>
                    <div class="c-textbox-detail">
                        <p class="c-textbox-text">
                            <img class="c-textbox-img" src="/woa/images/service_free_photo2.jpg" alt="">
							ウィルワンにはエージェントという、あなた専任で就職・転職をサポートするスタッフがいます。<br>「将来に悩んでいる」「就職・転職を成功させるには、今何をしたらいいかわからない」「今後の業界はどうなっていくのか?」など、<span class="u-bold">あなたの不安や悩みをしっかり受け止め寄り添い、就職・転職が成功するまでお手伝い</span>します。
                        </p>
                    </div>
                </div>

				<div class="c-textbox">
                    <div class="c-textbox-detail">
                        <p class="c-textbox-text">
                            <img class="c-textbox-img" src="/woa/images/service_free_photo3.jpg" alt="">
							ウィルワンでは、一般的な面接対策ではなく求人先に合わせたピンポイントな面接対策を行なっています。<br><span class="u-bold">求人先の院長・経営者さまと話をして、求める人材像を細かく聞き出して、把握しているので、希望就職・転職先に合わせたきめ細やかなアドバイス</span>ができます。
                        </p>
                    </div>
                </div>

				<hr>

				@include('pc.service.link')

            </div>

            @include('pc.mainparts.normalsidebar')
        </div>
    </main>
@include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
</body>
@endsection