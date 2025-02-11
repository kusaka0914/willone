<?php $noindex = 1;?>
@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')
    <main class="l-contents">
		<div class="l-contents-container u-text-c">
            <div class="p-404-title u-mt40">500</div>
			<div class="p-404-subtitle">Internal Server Error</div>
			<div class="u-mt40">アクセスしようとしたページは表示できませんでした。</div>
			<div class="c-button-wrap u-mb40 u-mt40"><a href="{{ route('Top')}}" class="c-button">
				<i class="fa fa-caret-right"></i> トップページへ戻る
            </a></div>
		</div>
    </main>
    @include('sp.mainparts.bodyfooter')

@include('sp.mainparts.topscript')
</body>
@endsection