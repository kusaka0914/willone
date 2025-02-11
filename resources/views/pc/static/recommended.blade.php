@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')

    @include('pc.mainparts.breadcrump')

    <h1 class="p-recommended-head">新卒の方にもオススメです</h1>

    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

				<p class="u-text-c"><img src="/woa/images/recommended1.jpg"></p>
				<p class="u-text-c"><img src="/woa/images/recommended2.jpg"></p>
				<div class="c-button-wrap-color"><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.other')])}}?action=pcor-recommended-btn" class="c-button-big">
                    <span>キャリアカウンセリング・応募書類の添削・面接対応まで</span><br>
                    エージェントに相談する
                </a></div>

            </div>

    @include('pc.mainparts.normalsidebar')
        </div>
    </main>
    @include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
</body>
@endsection
