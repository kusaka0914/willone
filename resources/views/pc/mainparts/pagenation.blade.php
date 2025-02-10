<div class="c-pagenation-wrap c-search-count-wrap2">
    <ol class="c-pagenation">

        @if( $job_count < config('ini.DEFAULT_OFFSET'))
            <li><span>前へ</span></li>
            <li><span>1</span></li>
            <li><span>次へ</span></li>
        @else
            @if( $page > 1)

                @if( $url_name == 'AreaNewList')
                    <li><a href="{{ route($url_name , ['page' => ($page-1) , 'pref' => $pref_roma])}}">前へ</a></li>
                @elseif( $url_name == 'AreaBlankList')
                    <li><a href="{{ route($url_name , ['page' => ($page-1) , 'pref' => $pref_roma])}}">前へ</a></li>
                @elseif( $url_name == 'JobNewList')
                    <li><a href="{{ route($url_name , ['page' => ($page-1) , 'type' => $type_roma])}}">前へ</a></li>
                @elseif( $url_name == 'JobBlankList')
                    <li><a href="{{ route($url_name , ['page' => ($page-1) , 'type' => $type_roma])}}">前へ</a></li>
                @elseif(in_array($url_name, ['JobAreaSelect', 'JobAreaSelectEkichika5']))
                    <li>
                        <a href="{{ route($url_name , ['page' => ($page-1) , 'pref' => $pref_roma , 'id' => $type_roma ])}}@if(!empty($query_string)){{ $query_string }}@endif">前へ</a>
                    </li>
                @elseif(in_array($url_name, ['AreaStateSelect', 'AreaStateSelectEkichika5']))
                    <li>
                        <a href="{{ route($url_name , ['page' => ($page-1) , 'pref' => $pref_roma , 'state' => $state_roma ])}}@if(!empty($query_string)){{ $query_string }}@endif">前へ</a>
                    </li>
                @elseif(in_array($url_name, ['JobAreaStateSelect', 'JobAreaStateSelectEkichika5']))
                    <li>
                        <a href="{{ route($url_name , ['page' => ($page-1) , 'id' => $type_roma , 'pref' => $pref_roma , 'state' => $state_roma ])}}@if(!empty($query_string)){{ $query_string }}@endif">前へ</a>
                    </li>
                @else
                    <li><a href="{{ route($url_name , ['page' => ($page-1) ])}}@if(!empty($query_string)){{ $query_string }}@endif">前へ</a></li>
                @endif
            @else
                <li><span>前へ</span></li>
            @endif

            @if($page > 3)
                @if( $url_name == 'AreaNewList')
                    <li><a href="{{ route($url_name , ['page' => (1) ,'pref' => $pref_roma])}}">{{ 1 }}</a></li>
                    <li><span class="ellipsis">...</span></li>
                @elseif( $url_name == 'AreaBlankList')
                    <li><a href="{{ route($url_name , ['page' => (1) ,'pref' => $pref_roma])}}">{{ 1 }}</a></li>
                    <li><span class="ellipsis">...</span></li>
                @elseif( $url_name == 'JobNewList')
                    <li><a href="{{ route($url_name , ['page' => (1) ,'type' => $type_roma])}}">{{ 1 }}</a></li>
                    <li><span class="ellipsis">...</span></li>
                @elseif( $url_name == 'JobBlankList')
                    <li><a href="{{ route($url_name , ['page' => (1) ,'type' => $type_roma])}}">{{ 1 }}</a></li>
                    <li><span class="ellipsis">...</span></li>
                @elseif(in_array($url_name, ['JobAreaSelect', 'JobAreaSelectEkichika5']))
                    <li><a href="{{ route($url_name , ['page' => (1) ,'pref' => $pref_roma , 'id' => $type_roma])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ 1 }}</a></li>
                    <li><span class="ellipsis">...</span></li>
                @elseif(in_array($url_name, ['AreaStateSelect', 'AreaStateSelectEkichika5']))
                    <li><a href="{{ route($url_name , ['page' => (1) ,'pref' => $pref_roma , 'state' => $state_roma])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ 1 }}</a></li>
                    <li><span class="ellipsis">...</span></li>
                @elseif(in_array($url_name, ['JobAreaStateSelect', 'JobAreaStateSelectEkichika5']))
                    <li><a href="{{ route($url_name , ['page' => (1) , 'id' => $type_roma , 'pref' => $pref_roma , 'state' => $state_roma ])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ 1 }}</a></li>
                    <li><span class="ellipsis">...</span></li>
                @else
                    <li><a href="{{ route($url_name , ['page' => (1) ])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ 1 }}</a></li>
                    <li><span class="ellipsis">...</span></li>
                @endif
            @elseif($page == 3)
                @if( $url_name == 'AreaNewList')
                    <li><a href="{{ route($url_name , ['page' => (1) ,'pref' => $pref_roma])}}">{{ 1 }}</a></li>
                @elseif( $url_name == 'AreaBlankList')
                    <li><a href="{{ route($url_name , ['page' => (1) ,'pref' => $pref_roma])}}">{{ 1 }}</a></li>
                @elseif( $url_name == 'JobNewList')
                    <li><a href="{{ route($url_name , ['page' => (1) ,'type' => $type_roma])}}">{{ 1 }}</a></li>
                @elseif( $url_name == 'JobBlankList')
                    <li><a href="{{ route($url_name , ['page' => (1) ,'type' => $type_roma])}}">{{ 1 }}</a></li>
                @elseif(in_array($url_name, ['JobAreaSelect', 'JobAreaSelectEkichika5']))
                    <li><a href="{{ route($url_name , ['page' => (1) ,'pref' => $pref_roma , 'id' => $type_roma])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ 1 }}</a></li>
                @elseif(in_array($url_name, ['AreaStateSelect', 'AreaStateSelectEkichika5']))
                    <li><a href="{{ route($url_name , ['page' => (1) ,'pref' => $pref_roma , 'state' => $state_roma])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ 1 }}</a></li>
                @elseif(in_array($url_name, ['JobAreaStateSelect', 'JobAreaStateSelectEkichika5']))
                    <li><a href="{{ route($url_name , ['page' => (1) , 'id' => $type_roma , 'pref' => $pref_roma , 'state' => $state_roma ])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ 1 }}</a></li>
                @else
                    <li><a href="{{ route($url_name , ['page' => (1) ])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ 1 }}</a></li>
                @endif
            @endif

            <?php $page_max = ceil($job_count / config('ini.DEFAULT_OFFSET'));?>
            @for( $i=0;$i < $page + 1 && $i <= $page_max - 2; $i++)

                {{--最大表示ページリンク数制限--}}
                @if($i < $page - 2)
                    @continue
                @endif

                @if( $page == ($i+1))
                    <li><span>{{ $i+1 }}</span></li>
                @elseif( $url_name == 'AreaNewList')
                    <li><a href="{{ route($url_name , ['page' => ($i+1) ,'pref' => $pref_roma])}}">{{ $i+1 }}</a></li>
                @elseif( $url_name == 'AreaBlankList')
                    <li><a href="{{ route($url_name , ['page' => ($i+1) ,'pref' => $pref_roma])}}">{{ $i+1 }}</a></li>
                @elseif( $url_name == 'JobNewList')
                    <li><a href="{{ route($url_name , ['page' => ($i+1) ,'type' => $type_roma])}}">{{ $i+1 }}</a></li>
                @elseif( $url_name == 'JobBlankList')
                    <li><a href="{{ route($url_name , ['page' => ($i+1) ,'type' => $type_roma])}}">{{ $i+1 }}</a></li>
                @elseif(in_array($url_name, ['JobAreaSelect', 'JobAreaSelectEkichika5']))
                    <li>
                        <a href="{{ route($url_name , ['page' => ($i+1) ,'pref' => $pref_roma , 'id' => $type_roma])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ $i+1 }}</a>
                    </li>
                @elseif(in_array($url_name, ['AreaStateSelect', 'AreaStateSelectEkichika5']))
                    <li>
                        <a href="{{ route($url_name , ['page' => ($i+1) ,'pref' => $pref_roma , 'state' => $state_roma])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ $i+1 }}</a>
                    </li>
                @elseif(in_array($url_name, ['JobAreaStateSelect', 'JobAreaStateSelectEkichika5']))
                    <li>
                        <a href="{{ route($url_name , ['page' => ($i+1) , 'id' => $type_roma , 'pref' => $pref_roma , 'state' => $state_roma ])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ $i+1 }}</a>
                    </li>
                @else
                    <li><a href="{{ route($url_name , ['page' => ($i+1) ])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ $i+1 }}</a></li>
                @endif

            @endfor

            @if($page < $page_max - 2)
                <li><span class="ellipsis">...</span></li>
            @endif

            @if( $page == $page_max)
                <li><span>{{ $page_max }}</span></li>
            @elseif( $url_name == 'AreaNewList')
                <li><a href="{{ route($url_name , ['page' => ($page_max) ,'pref' => $pref_roma])}}">{{ $page_max }}</a>
                </li>
            @elseif( $url_name == 'AreaBlankList')
                <li><a href="{{ route($url_name , ['page' => ($page_max) ,'pref' => $pref_roma])}}">{{ $page_max }}</a>
                </li>
            @elseif( $url_name == 'JobNewList')
                <li><a href="{{ route($url_name , ['page' => ($page_max) ,'type' => $type_roma])}}">{{ $page_max }}</a>
                </li>
            @elseif( $url_name == 'JobBlankList')
                <li><a href="{{ route($url_name , ['page' => ($page_max) ,'type' => $type_roma])}}">{{ $page_max }}</a>
                </li>
            @elseif(in_array($url_name, ['JobAreaSelect', 'JobAreaSelectEkichika5']))
                <li>
                    <a href="{{ route($url_name , ['page' => ($page_max) ,'pref' => $pref_roma , 'id' => $type_roma])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ $page_max }}</a>
                </li>
            @elseif(in_array($url_name, ['AreaStateSelect', 'AreaStateSelectEkichika5']))
                <li>
                    <a href="{{ route($url_name , ['page' => ($page_max) ,'pref' => $pref_roma , 'state' => $state_roma])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ $page_max }}</a>
                </li>
            @elseif(in_array($url_name, ['JobAreaStateSelect', 'JobAreaStateSelectEkichika5']))
                <li>
                    <a href="{{ route($url_name , ['page' => ($page_max) , 'id' => $type_roma , 'pref' => $pref_roma , 'state' => $state_roma ])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ $page_max }}</a>
                </li>
            @else
                <li><a href="{{ route($url_name , ['page' => ($page_max) ])}}@if(!empty($query_string)){{ $query_string }}@endif">{{ $page_max }}</a></li>
            @endif

            @if( $page == $page_max)
                <li><span>次へ</span></li>
            @else
                @if( $url_name == 'AreaNewList')
                    <li><a href="{{ route($url_name , ['page' => ($page+1) ,'pref' => $pref_roma])}}">次へ</a></li>
                @elseif( $url_name == 'AreaBlankList')
                    <li><a href="{{ route($url_name , ['page' => ($page+1) ,'pref' => $pref_roma])}}">次へ</a></li>
                @elseif( $url_name == 'JobNewList')
                    <li><a href="{{ route($url_name , ['page' => ($page+1) ,'type' => $type_roma])}}">次へ</a></li>
                @elseif( $url_name == 'JobBlankList')
                    <li><a href="{{ route($url_name , ['page' => ($page+1) ,'type' => $type_roma])}}">次へ</a></li>
                @elseif(in_array($url_name, ['JobAreaSelect', 'JobAreaSelectEkichika5']))
                    <li>
                        <a href="{{ route($url_name , ['page' => ($page+1) ,'pref' => $pref_roma , 'id' => $type_roma])}}@if(!empty($query_string)){{ $query_string }}@endif">次へ</a>
                    </li>
                @elseif(in_array($url_name, ['AreaStateSelect', 'AreaStateSelectEkichika5']))
                    <li>
                        <a href="{{ route($url_name , ['page' => ($page+1) , 'pref' => $pref_roma , 'state' => $state_roma ])}}@if(!empty($query_string)){{ $query_string }}@endif">次へ</a>
                    </li>
                @elseif(in_array($url_name, ['JobAreaStateSelect', 'JobAreaStateSelectEkichika5']))
                    <li>
                        <a href="{{ route($url_name , ['page' => ($page+1) , 'id' => $type_roma , 'pref' => $pref_roma , 'state' => $state_roma ])}}@if(!empty($query_string)){{ $query_string }}@endif">次へ</a>
                    </li>
                @else
                    <li><a href="{{ route($url_name , ['page' => ($page+1) ])}}@if(!empty($query_string)){{ $query_string }}@endif">次へ</a></li>
                @endif
            @endif
        @endif

    </ol>
    <div class="c-search-count">
        <span class="c-search-count-num">{{ $job_count }}</span>
        <span class="c-search-count-text">
            {{ getPaginationFromToNumText($page, $job_data->count(), $job_count, config('ini.DEFAULT_OFFSET')) }}
        </span>
    </div>
</div>
