@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')

    @include('pc.mainparts.breadcrump')

    <h1 class="p-seminar-head">{{ $knowhow_value}}</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

                @include('pc.mainparts.blogmainlist')
            </div>

            @include('pc.mainparts.bloglistsidebar')

        </div>
    </main>

    @include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
</body>
@endsection
