@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')

    @include('pc.mainparts.breadcrumb')
    <h1 class="p-type-head">エリアから探す</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

                <h2 class="c-title-l"><i class="fa fa-search"></i>都道府県から探す</h2>
                <div class="u-clearfix">
                    <div class="p-type-area">
                        <h3 class="c-title-m">関東</h3>
                        <ul class="c-area">
                            @foreach($kantou as $pref)
                            <li class="c-area-item"><a class="c-area-link" href="{{ route('AreaSelect' , ['id' => $pref->addr1_roma]) }}" >{{ $pref->addr1 }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="p-top-area">
                        <h3 class="c-title-m">その他の地域</h3>
                        <ul class="c-area">
                            @foreach($other_chiiki as $pref)
                            <li class="c-area-item"><a class="c-area-link" href="{{ route('AreaSelect' , ['id' => $pref->addr1_roma]) }}" >{{ $pref->addr1 }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <h2 class="c-title-l"><i class="fa fa-search"></i>人気エリアから探す</h2>
                 <div class="u-clearfix">
                    @foreach( $popCities as $popCityRomaName => $popCityData)
                    <div class="p-top-area">
                        <h3 class="c-title-m">{{ $popCityData['prefName'] }}</h3>
                        <ul class="c-area">
                            @foreach( $popCityData['cities'] as $popCityValue)
                                <li class="c-area-item"><a class="c-area-link" href="{{ route('AreaStateSelect' , ['pref' => $popCityRomaName , 'state' => $popCityValue['roma'] ])}}">{{ $popCityValue['name'] }}</a></li>
                            @endforeach

                        </ul>
                    </div>
                    @endforeach
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

                @include('pc.mainparts.entrybutton', ['_action' => 'pcor-areatop-btn_bottom', 'lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.top')])

            </div>

            @include('pc.mainparts.jobsidebar')
        </div>
        @include('pc.mainparts.syokusyutext')
    </main>
    @include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
</body>
@endsection
