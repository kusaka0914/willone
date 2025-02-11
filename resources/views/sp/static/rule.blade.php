@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')
    <h1 class="p-rule-head">利用規約</h1>

    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main p-rule-container">

@include('sp.contents.include.ct._rule'){{--利用規約中身--}}

            </div>

            @include('sp.mainparts.normalsidebar')

        </div>
    </main>

    @include('sp.mainparts.bodyfooter')

    @include('sp.mainparts.topscript')

</body>
@endsection