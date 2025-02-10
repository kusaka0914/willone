<h2 class="c-title-l">人気検索条件</h2>
<ul class="c-fav u-mt10">
    <li class="c-fav-item">
        <a class="c-fav-link" href="{{ route('JobSelect', ['id' => 'judoseifukushi']) }}">柔道整復師</a>
    </li>
    <li class="c-fav-item">
        <a class="c-fav-link" href="{{ route('JobSelect', ['id' => 'ammamassajishiatsushi']) }}">あん摩マッサージ指圧師</a>
    </li>
    <li class="c-fav-item">
        <a class="c-fav-link" href="{{ route('JobSelect', ['id' => 'harikyushi']) }}">鍼灸師</a>
    </li>
    @if(!empty($sidebar_popular_search_ekichika5_url))
        <li class="c-fav-item">
            <a class="c-fav-link" href="{{ $sidebar_popular_search_ekichika5_url }}">駅から徒歩5分以内</a>
        </li>
    @endif
</ul>
