@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')

    <h1 class="p-recommended-head">新卒の方にもオススメです</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

                <div class="c-img-box"><img class="c-img-responsive" src="/woa/images/recommended1.jpg" alt=""></div>
				<div class="c-img-box"><img class="c-img-responsive" src="/woa/images/recommended2.jpg" alt=""></div>
				<div class="c-button-wrap-color"><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.other')])}}?action=spor-recommended-btn" class="c-button-big">
                    <span>キャリアカウンセリング・応募書類の添削・面接対応まで</span><br>
                    エージェントに相談する
                </a></div>

            </div>

            @include('sp.mainparts.normalsidebar')
        </div>
    </main>
    @include('sp.mainparts.bodyfooter')

    @include('sp.mainparts.topscript')

</body>
@endsection
