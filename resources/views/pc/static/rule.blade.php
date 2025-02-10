@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')

    @include('pc.mainparts.breadcrump')

    <h1 class="p-rule-head">利用規約</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main p-rule-container">

@include('pc.contents.include.ct._rule'){{--利用規約中身--}}

            </div>
            @include('pc.mainparts.normalsidebar')
        </div>
    </main>

    @include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
</body>
@endsection