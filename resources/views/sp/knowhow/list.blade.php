@extends('sp.mainparts.head')

@section('content')
<body>
    @include('sp.mainparts.bodyheader')

    <h1 class="p-seminar-head">{{ $knowhow_value}}</h1>

    <main class="l-contents">
        <div class="l-contents-container">
            <div class="bluebox u-m15">
                <h2>キャリアパートナーに相談する</h2>
                <h3 class="u-mt20">お気持ちはどちらに近いですか？</h3>
                <ul class="bluebox-flex u-m10 u-mb20">
                    <li><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.other')]) }}?action=spor-knowhow-motiv-1&branch=A" class="bluebox-btn"><img src="/woa/img/branch_a.png" alt="近いうちに転職したい" class="u-mb10">近いうちに転職したい</a></li>
                    <li><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.other')]) }}?action=spor-knowhow-motiv-0&branch=B" class="bluebox-btn"><img src="/woa/img/branch_b.png" alt="今は情報収集したい" class="u-mb10">今は情報収集したい</a></li>
                </ul>
            </div>
            <div class="l-contents-main">

                @include('sp.mainparts.blogmainlist')
            </div>

            @include('sp.mainparts.bloglistsidebar')

        </div>
    </main>

    @include('sp.mainparts.bodyfooter')

@include('sp.mainparts.topscript')
</body>
@endsection
