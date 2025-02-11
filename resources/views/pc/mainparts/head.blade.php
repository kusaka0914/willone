<!DOCTYPE html>
<html lang="ja" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="utf-8">
    @if(!empty($noindex))
            <meta name="robots" content="noindex,nofollow">
    @endif
    @if( isset($headtitle))
        @if( !empty($headtitle))
            <title>{{ $headtitle }}</title>
        @else
            <title>【ウィルワン】柔道整復師・鍼灸師・マッサージ師の求人・転職</title>
        @endif
    @else
        <title>【ウィルワン】柔道整復師・鍼灸師・マッサージ師の求人・転職</title>
    @endif

    @if( isset($headdescription))
    <meta name="description" content="{{ $headdescription }}">
    @endif
    @include('common._canonical')
    <!--og-->
    @include('common._ogp')
    <!--css-->
    <link href="{{ addQuery('/woa/css/style.css') }}" rel="stylesheet" media="all">
    <link href="/woa/css/styleapply.css?20180731" rel="stylesheet" media="all">
    <link href="/woa/css/systemstyle.css?20221205" rel="stylesheet" media="all">
    <link href="/woa/css/buttons.css?20181022" rel="stylesheet" media="all">
    @if( isset($pagenation_flg))
        @if ( $job_count > config('ini.DEFAULT_OFFSET'))
            @if( $page > 1)
                @if( $page == 2)
                    @if( $url_name == 'AreaNewList')
                        <link  rel="prev" href="{{ route($url_name , ['pref' => $pref_roma])}}">
                    @elseif( $url_name == 'AreaBlankList')
                        <link  rel="prev" href="{{ route($url_name , ['pref' => $pref_roma])}}">
                    @elseif( $url_name == 'JobNewList')
                        <link  rel="prev" href="{{ route($url_name , ['type' => $type_roma])}}">
                    @elseif( $url_name == 'JobBlankList')
                        <link  rel="prev" href="{{ route($url_name , ['type' => $type_roma])}}">
                    @elseif(in_array($url_name, ['JobAreaSelect', 'JobAreaSelectEkichika5']))
                        <link  rel="prev" href="{{ route($url_name , ['pref' => $pref_roma , 'id' => $type_roma])}}">
                    @elseif(in_array($url_name, ['AreaStateSelect', 'AreaStateSelectEkichika5']))
                        <link  rel="prev" href="{{ route($url_name , ['pref' => $pref_roma , 'state' => $state_roma])}}">
                    @elseif(in_array($url_name, ['JobAreaStateSelect', 'JobAreaStateSelectEkichika5']))
                        <link  rel="prev" href="{{ route($url_name , ['id' => $type_roma , 'pref' => $pref_roma , 'state' => $state_roma])}}">
                    @else
                        <link  rel="prev" href="{{ route($url_name )}}">
                    @endif
                @else
                    @if( $url_name == 'AreaNewList')
                        <link  rel="prev" href="{{ route($url_name , ['page' => ($page-1) ,'pref' => $pref_roma])}}">
                    @elseif( $url_name == 'AreaBlankList')
                        <link  rel="prev" href="{{ route($url_name , ['page' => ($page-1) ,'pref' => $pref_roma])}}">
                    @elseif( $url_name == 'JobNewList')
                        <link  rel="prev" href="{{ route($url_name , ['page' => ($page-1) ,'type' => $type_roma])}}">
                    @elseif( $url_name == 'JobBlankList')
                        <link  rel="prev" href="{{ route($url_name , ['page' => ($page-1) ,'type' => $type_roma])}}">
                    @elseif(in_array($url_name, ['JobAreaSelect', 'JobAreaSelectEkichika5']))
                        <link  rel="prev" href="{{ route($url_name , ['page' => ($page-1) ,'pref' => $pref_roma , 'id' => $type_roma])}}">
                    @elseif(in_array($url_name, ['AreaStateSelect', 'AreaStateSelectEkichika5']))
                        <link  rel="prev" href="{{ route($url_name , ['page' => ($page-1) ,'pref' => $pref_roma , 'state' => $state_roma])}}">
                    @elseif(in_array($url_name, ['JobAreaStateSelect', 'JobAreaStateSelectEkichika5']))
                        <link  rel="prev" href="{{ route($url_name , ['page' => ($page-1) ,'id' => $type_roma , 'pref' => $pref_roma , 'state' => $state_roma])}}">
                    @else
                        <link  rel="prev" href="{{ route($url_name , ['page' => ($page-1) ])}}">
                    @endif
                @endif
            @endif
            <?php $page_max = ceil($job_count / config('ini.DEFAULT_OFFSET'));?>
            @if( $page < $page_max)
                @if( $url_name == 'AreaNewList')
                    <link  rel="next" href="{{ route($url_name , ['page' => ($page+1) ,'pref' => $pref_roma])}}">
                @elseif( $url_name == 'AreaBlankList')
                    <link  rel="next" href="{{ route($url_name , ['page' => ($page+1) ,'pref' => $pref_roma])}}">
                @elseif( $url_name == 'JobNewList')
                    <link  rel="next" href="{{ route($url_name , ['page' => ($page+1) ,'type' => $type_roma])}}">
                @elseif( $url_name == 'JobBlankList')
                    <link  rel="next" href="{{ route($url_name , ['page' => ($page+1) ,'type' => $type_roma])}}">
                @elseif(in_array($url_name, ['JobAreaSelect', 'JobAreaSelectEkichika5']))
                    <link  rel="next" href="{{ route($url_name , ['page' => ($page+1) ,'pref' => $pref_roma , 'id' => $type_roma])}}">
                @elseif(in_array($url_name, ['AreaStateSelect', 'AreaStateSelectEkichika5']))
                    <link  rel="next" href="{{ route($url_name , ['page' => ($page+1) ,'pref' => $pref_roma , 'state' => $state_roma])}}">
                @elseif(in_array($url_name, ['JobAreaStateSelect', 'JobAreaStateSelectEkichika5']))
                    <link  rel="next" href="{{ route($url_name , ['page' => ($page+1) ,'id' => $type_roma , 'pref' => $pref_roma , 'state' => $state_roma])}}">
                @else
                    <link  rel="next" href="{{ route($url_name , ['page' => ($page+1) ])}}">
                @endif
            @endif
        @endif
    @endif
@include('common._gtag')
@if(isset($jobposting))
    @include('common._jobposting')
@endif
    <link rel="icon" href="/woa/favicon.ico">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "url": "https://www.jinzaibank.com/woa",
        "name": "ウィルワンエージェント"
    }
    </script>
</head>

@yield('content')

</html>
