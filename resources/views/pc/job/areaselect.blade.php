@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')

    @include('pc.mainparts.breadcrumb')

    <h1 class="p-type-head">{{ $title }}の求人</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">
                <h2 class="c-title-l"><i class="fa fa-search"></i>条件から探す</h2>
                <form method="post" action="{{ route('Redirect')}}" id="searchForm">
                    {{ csrf_field()}}
                    <input type="hidden" name="pref" value="{{ $pref_roma }}">
                    <input type="hidden" name="pref_id" value="{{ $pref_id }}">
                    <input type="hidden" name="link_type" value="1">

                    <table class="c-table">
                        <tr>
                            <th class="u-w20">市区町村</th>
                            <td colspan="2">
                                <label class="c-form-select-ic">
                                    <select class="c-form-select" name="state" id="municipalitiesSelect" onchange="municipalitiesChange();">
                                        <option value="" data-id="">市区町村を選択</option>
                                        @foreach($state_data as $value)
                                            <option value="{{ $value->addr2_roma }}" data-id="{{ $value->id }}">
                                                {{ $value->addr2 }}
                                            </option>
                                        @endforeach
                                    </select>
                                </label>
                            </td>
                        </tr>
                        @if(isset($search_near_cities))
                            @include('pc.job.parts.searchConditions')
                        @endif
                    </table>

                </form>

                @include('pc.mainparts.jobarea')

                @if (count($new_job) > 0)
                @include('pc.mainparts.newjob')
                <div class="c-button-wrap">
                    <form method="post" action="{{ route('AreaNewList' ,['pref' => $pref_roma])}}">
                    {{ csrf_field()}}
                    <input type="hidden" name="pref" value="{{ $pref_id }}">
                    <input type="hidden" name="new" value="1">
                    <input class="c-button" type="submit" name="" value="新着求人をもっと見る">
                </form>
                </div>
                @endif

                @if (count($new_job) > 0)
                @include('pc.mainparts.blankjob')
                <div class="c-button-wrap">
                    <form method="post" action="{{ route('AreaBlankList' ,['pref' => $pref_roma])}}">
                    {{ csrf_field()}}
                    <input type="hidden" name="pref" value="{{ $pref_id }}">
                    <input type="hidden" name="new" value="1">
                    <input type="hidden" name="blank" value="1">
                    <input class="c-button" type="submit" name="" value="ブランクOKの求人をもっと見る">
                </form>
                </div>
                @endif

                @include('pc.mainparts.entrybutton', ['_action' => 'pcor-areaselect-btn_bottom', 'lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.list')])

            </div>

            @include('pc.mainparts.jobsidebar')

        </div>
        @include('pc.mainparts.syokusyutext')
    </main>
    @include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
</body>
@endsection
