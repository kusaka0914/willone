@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')

    <h1 class="p-about-head">ウィルワンに人材紹介を申し込む</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">
            	<h2 class="c-title-l-pink u-mb0">
	                    人材紹介申込完了
	                </h2>
            	<div class="c-form-base">
					<div class="c-form-base-wrap">


	                <p>人材紹介申込ありがとうございます。</br>
	                   確認後、ご連絡させていただきます。</p>
	                <a href="{{ route('Top') }}" class="thanksBtn">ウィルワントップへ</a>
				</div>
				</div>
			</div>

			@include('sp.mainparts.normalsidebar')
        </div>
    </main>
    @include('sp.mainparts.bodyfooter')

@include('sp.mainparts.topscript')
</body>
@endsection