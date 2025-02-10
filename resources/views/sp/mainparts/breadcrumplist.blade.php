<div class="l-breadcrumb-wrapper">
	@if( $breadcrump_num == 1)
        <ol class="l-breadcrumb">
            <li class="l-breadcrumb-item"><a href="/woa">{{ $breadcrump[0] }}</a></li>
            <li class="l-breadcrumb-item"><span>{{ $breadcrump[1] }}求人一覧</span></li>
        </ol>
    @elseif( $breadcrump_num == 2)
    	<ol class="l-breadcrumb">
            <li class="l-breadcrumb-item"><a href="/woa">{{ $breadcrump[0] }}</a></li>
            <li class="l-breadcrumb-item"><a href="/woa/area/{{ $pref_roma }}">{{ $breadcrump[1] }}</a></li>
            <li class="l-breadcrumb-item"><span>{{ $breadcrump[2] }}</span></li>
        </ol>
    @elseif( $breadcrump_num == 3)
    	<ol class="l-breadcrumb">
            <li class="l-breadcrumb-item"><a href="/woa">{{ $breadcrump[0] }}</a></li>
            <li class="l-breadcrumb-item"><a href="/woa/job/{{ $type_roma }}">{{ $breadcrump[1] }}</a></li>
            <li class="l-breadcrumb-item"><a href="/woa/job/{{ $type_roma }}/{{ $pref_roma }}">{{ $breadcrump[2] }}</a></li>
            <li class="l-breadcrumb-item"><span>{{ $breadcrump[3] }}</span></li>
        </ol>
    @elseif( $breadcrump_num == 4)
        <ol class="l-breadcrumb">
        @foreach ($breadcrump['url'] as $key => $url)
        @if (!$loop->last)
            @if (!empty($url) && !empty($breadcrump['name'][$key]))
            <li class="l-breadcrumb-item"><a href="{{ $url }}">{{ $breadcrump['name'][$key] }}</a></li>
            @endif
        @elseif (!empty($breadcrump['name'][$key]))
            <li class="l-breadcrumb-item"><span>{{ $breadcrump['name'][4] }}</span></li>
        @endif
        @endforeach
        </ol>
    @elseif( $breadcrump_num == 5)
        <ol class="l-breadcrumb">
            <li class="l-breadcrumb-item"><a href="/woa">{{ $breadcrump[0] }}</a></li>
            <li class="l-breadcrumb-item"><a href="/woa/job/{{ $type_roma }}">{{ $breadcrump[1] }}</a></li>
            <li class="l-breadcrumb-item"><span>{{ $breadcrump[2] }}</span></li>
        </ol>
    @endif

    </div>
