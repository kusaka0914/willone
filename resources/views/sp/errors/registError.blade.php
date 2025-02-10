<?php $noindex = 1;?>
@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')

    <main role="main" class="l-contents">

    <div class="l-contents-container u-text-c">
        <div class="large-24 columns" style=" padding-top: 2rem; padding-bottom: 1rem;">
            <article>
                <p class="text-center">誠に申し訳ございませんが、システムエラーまたはネットワークエラーが発生したため動作が中断されました。<br />
                    通信環境をご確認の上、お手数ですが最初から再度お試しください。</p>
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
