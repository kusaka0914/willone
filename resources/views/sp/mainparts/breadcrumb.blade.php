<div class="l-breadcrumb-wrapper">
    <ol class="l-breadcrumb">
    @foreach ($bread_crumb as $item)
    @if (!$loop->last)
        @if (!empty($item['url']) && !empty($item['label']))
        <li class="l-breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
        @elseif (empty($item['url']) && !empty($item['label']))
        <li class="l-breadcrumb-item"><span>{{ $item['label'] }}</span></li>
        @endif
    @elseif (!empty($item['label']))
        <li class="l-breadcrumb-item"><span>{{ $item['label'] }}</span></li>
    @endif
    @endforeach
    </ol>
</div>
