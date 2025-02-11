<?php $noindex = 1;?>
@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')

    <main role="main" class="l-contents">

    <div class="l-contents-container u-text-c">
        <div class="large-24 columns" style=" padding-top: 2rem; padding-bottom: 1rem;">
            <article>
                <p class="text-center">しばらく会員情報の更新がなかったため、<br />
                再登録が必要となっています。<br />
                大変お手数ではございますが、<br />
                以下のURLより再登録をお願い致します。</p>
                <div class="c-button-wrap u-mb40 u-mt40"><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.other')]) }}" rel="nofollow" class="c-button">
                <i class="fa fa-caret-right"></i> 再登録
                </a></div>

            </article>
        </div>
        <!-- / .columns -->
    </div>
    <!-- / .row -->

    </main>
    @include('sp.mainparts.bodyfooter')

@include('sp.mainparts.topscript')
</body>
@endsection
