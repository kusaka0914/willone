@if (!empty($area_data) && !$area_data->isEmpty())
    <h2 class="c-title-l">
        <i class="fa fa-search"></i>
        @if (!empty($focus_area_flag))
        {{$type_name}}の{{$area_data_title_key}}の求人を{{$pref_name}}の近隣都道府県から探す
        @else
        {{$type_name}}の求人を{{$pref_name}}の近隣都道府県から探す
        @endif
    </h2>
    <ul class="c-area">
    @foreach ($area_data as $pref)
        @if (!empty($type_roma))
        <li class="c-area-item">
        @if (!empty($focus_area_flag))
            @if (!empty($ekichika))
            <a class="c-area-link-active" href="{{ route('JobAreaSelectEkichika5' , ['id' => $type_roma, 'pref' => $pref->addr1_roma]) }}">
            @else
            <a class="c-area-link-active" href="{{ route('JobAreaSelectSearch' , ['id' => $type_roma, 'pref' => $pref->addr1_roma, 'searchKey' => $area_data_href['key'], 'searchValue' => $area_data_href['type']]) }}">
            @endif
        @else
        <a class="c-area-link-active" href="{{ route('JobAreaSelect' , ['id' => $type_roma, 'pref' => $pref->addr1_roma]) }}">
        @endif
                {{$pref->addr1_name}}（{{$pref->sum}}）
            </a>
        </li>
        @else
        <li class="c-area-item">
            <a class="c-area-link-active" href="{{ route('AreaSelect' , ['id' => $type_roma, 'pref' => $pref->addr1_roma]) }}">
                {{$pref->addr1_name}}（{{$pref->sum}}）
            </a>
        </li>
        @endif
    @endforeach
    </ul>
@endif

@if (!empty($aggregate_data) && $aggregate_data['stateDisplay'] === true)
    <h2 class="c-title-l"><i class="fa fa-search"></i>{{$type_name}}の求人を{{$pref_name}}の市区町村から探す</h2>
    <ul class="c-area">
    @foreach ($aggregate_data['state'] as $state)
        @if (!empty($type_roma))
        <li class="c-area-item">
            <a class="c-area-link-active" href="{{ route('JobAreaStateSelect' , ['id' => $type_roma, 'pref' => $pref_roma, 'state' => $state->addr2_roma]) }}">
                {{$state->addr2}}（{{$state->sum}}）
            </a>
        </li>
        @else
        <li class="c-area-item">
            <a class="c-area-link-active" href="{{ route('AreaStateSelect' , ['pref' => $pref_roma, 'state' => $state->addr2_roma]) }}">
            {{$state->addr2}}（{{$state->sum}}）
            </a>
        </li>
        @endif
    @endforeach
    </ul>
@endif

@if (!empty($aggregate_data) && $aggregate_data['employDisplay'] === true)
<h2 class="c-title-l"><i class="fa fa-search"></i>{{$pref_name}}の{{$type_name}}の求人を雇用形態から探す</h2>
<ul class="c-area">
@foreach(config('ini.EMPLOY_TYPE') as $one)
@if(!empty($aggregate_data[$one['search_key']]))
    <li class="c-area-item">
        <a class="c-area-link-active" href="{{ route('JobAreaSelectSearch' , ['id' => $type_roma, 'pref' => $pref_roma, 'searchKey' => 'employ', 'searchValue' => $one['search_key']]) }}">
            {{$one['value']}}（{{$aggregate_data[$one['search_key']]}}）
        </a>
    </li>
@endif
@endforeach
</ul>
@endif

@if (!empty($aggregate_data) && $aggregate_data['bussinessDisplay'] === true)
<h2 class="c-title-l"><i class="fa fa-search"></i>{{$pref_name}}の{{$type_name}}の求人を施設形態から探す</h2>
<ul class="c-area">
@foreach(config('ini.BUSINESS_TYPE') as $one)
@if(!empty($aggregate_data[$one['search_key']]))
    <li class="c-area-item">
        <a class="c-area-link-active" href="{{ route('JobAreaSelectSearch' , ['id' => $type_roma, 'pref' => $pref_roma, 'searchKey' => 'business', 'searchValue' => $one['search_key']]) }}">
            {{$one['value']}}（{{$aggregate_data[$one['search_key']]}}）
        </a>
    </li>
@endif
@endforeach
</ul>
@endif

@if (!empty($aggregate_data) && $aggregate_data['ekichika5Display'] === true)
<h2 class="c-title-l"><i class="fa fa-search"></i>{{$pref_name}}の{{$type_name}}の駅ちか5分求人を探す</h2>
<ul class="c-area">
    <li class="c-area-item">
        <a class="c-area-link-active" href="{{ route('JobAreaSelectEkichika5' , ['id' => $type_roma, 'pref' => $pref_roma]) }}">
            駅から徒歩5分以内（{{$aggregate_data['ekichika5']}}）
        </a>
    </li>
</ul>
@endif
