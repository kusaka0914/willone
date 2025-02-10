@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')

    @include('pc.mainparts.breadcrumb')
    <h1 class="p-type-head">職種から探す</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

                <h2 class="c-title-l"><i class="fa fa-search"></i>職種から探す</h2>
                <div class="u-clearfix">
                    <div class="p-type-area">
                        <ul class="c-area">
                            @foreach($syokugyou as $key => $item)
                            <li class="c-area-item"><a class="c-area-link" href="{{ route('JobSelect' ,[ 'id' => $key])}}">{{ $item['name'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                </div>



                @if (count($new_job) > 0)
                @include('pc.mainparts.newjob')
                <div class="c-button-wrap">
                    <form method="post" action="{{ route('NewList')}}">
                    {{ csrf_field()}}
                    <input type="hidden" name="new" value="1">
                    <input class="c-button" type="submit" name="" value="新着求人をもっと見る">
                </form>
                </div>
                @endif

                @if (count($new_job) > 0)
                @include('pc.mainparts.blankjob')
                <div class="c-button-wrap">
                    <form method="post" action="{{ route('BlankList')}}">
                    {{ csrf_field()}}
                    <input type="hidden" name="new" value="1">
                    <input type="hidden" name="blank" value="1">
                    <input class="c-button" type="submit" name="" value="ブランクOKの求人をもっと見る">
                </form>
                </div>
                @endif

                @include('pc.mainparts.entrybutton', ['_action' => 'pcor-jobtypetop-btn_bottom', 'lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.top')])

            </div>

            @include('pc.mainparts.jobsidebar')
        </div>
        @include('pc.mainparts.syokusyutext')
    </main>
    @include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
</body>
@endsection
