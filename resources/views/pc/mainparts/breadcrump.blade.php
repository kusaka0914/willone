<div class="l-breadcrumb-wrapper">
        @if(!empty($breadcrump_num))
		@if( $breadcrump_num == 1)
        <ol class="l-breadcrumb">
            <li class="l-breadcrumb-item"><a href="/woa">{{ $breadcrump[0] }}</a></li>
            <li class="l-breadcrumb-item"><span>{{ $breadcrump[1] }}</span></li>
        </ol>
        @elseif( $breadcrump_num == 2)
        <ol class="l-breadcrumb">
            <li class="l-breadcrumb-item"><a href="/woa">{{ $breadcrump[0] }}</a></li>
            <li class="l-breadcrumb-item"><a href="{{ $breadcrumpurl[1] }}">{{ $breadcrump[1] }}</a></li>
            <li class="l-breadcrumb-item"><span>{{ $breadcrump[2] }}</span></li>
        </ol>
        @endif
        @else
        <ol class="l-breadcrumb">
            <li class="l-breadcrumb-item"><a href="{{ route('Top') }}">{{ config('ini.BASE_BREAD_CRUMB')[0]['label'] }}</a></li>
            @foreach($breadcrump as $breadcrumpData)
            @if(!empty($breadcrumpData['url']))
            <li class="l-breadcrumb-item"><a href="{{ $breadcrumpData['url'] }}">{{ $breadcrumpData['label'] }}</a></li>
            @else
            <li class="l-breadcrumb-item"><span>{{ $breadcrumpData['label'] }}</span></li>
            @endif
            @endforeach
        </ol>
        @endif
    </div>
