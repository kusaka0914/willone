@if (!empty($jobtypelist))
    <h2 class="c-title-l"><i class="fa fa-search"></i>{{$pref_name}}の求人を職種から探す</h2>
    <ul class="c-type">
    @foreach ($jobtypelist as $type_roma => $type_value)
    @if ($type_value->order_cnt > 0)
        @if (!empty($type) && $type_value->type == $type)
            @continue
        @endif
        <li class="c-type-item">
            <a href="{{ route('JobAreaSelect' , ['id' => $type_roma, 'pref' => $pref_roma])}}" class="c-type-link">
                <div class="c-type-img" @if(!empty($type_value->image))style="background-image:url({{$type_value->image}})@endif"></div>
                <span class="c-type-text">{{$type_value->type_name}}（{{$type_value->order_cnt}}）</span>
            </a>
        </li>
    @endif
    @endforeach
    </ul>
@endif
