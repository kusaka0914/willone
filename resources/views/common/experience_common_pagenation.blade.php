<ol class="c-pagenation">
    @if($page == 1)
        <li><span>前へ</span></li>
    @else
        <li>
            @if(!empty($route_category))
                <a href="{{ route('TensyokuCategory', ['category' => $route_category, 'page' => $page - 1]) }}">前へ</a>
            @else
                <a href="{{ route('TensyokuList', ['page' => $page - 1]) }}">前へ</a>
            @endif
        </li>
    @endif
    @for($i = 1; $i <= $maxpage; $i++)
        @if($page == $i)
            <li><span>{{$i}}</span></li>
        @else
            <li>
                @if(!empty($route_category))
                    <a href="{{ route('TensyokuCategory', ['category' => $route_category, 'page' => $i]) }}">{{$i}}</a>
                @else
                    <a href="{{ route('TensyokuList', ['page' => $i]) }}">{{$i}}</a>
                @endif
            </li>
        @endif
    @endfor
    @if($page == $maxpage)
        <li><span>次へ</span></li>
    @else
        <li>
            @if(!empty($route_category))
                <a href="{{ route('TensyokuCategory', ['category' => $route_category, 'page' => $page + 1]) }}">次へ</a>
            @else
                <a href="{{ route('TensyokuList', ['page' => $page + 1]) }}">次へ</a>
            @endif
        </li>
    @endif
</ol>
