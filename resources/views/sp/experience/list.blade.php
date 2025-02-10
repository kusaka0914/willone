@extends('sp.mainparts.head')

@section('content')
<body>
    @include('sp.mainparts.bodyheader')

    <h1 class="p-experience-head">就職・転職体験談一覧</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

                <div class="c-pagenation-wrap c-search-count-wrap2">
                    @include('common.experience_common_pagenation')
                    <div class="c-search-count">
                        <span class="c-search-count-num">{{ $blogdatacnt }}</span>
                        <span class="c-search-count-text">
                            {{ getPaginationFromToNumText($page, $blogdata->count(), $blogdatacnt, 30) }}
                        </span>
                    </div>
                </div>

                <ul class="c-experience-big">
                    @foreach( $blogdata as $value)
                    <li class="c-experience-big-item"><a href="{{ route('TensyokuDetail',['id' => $value->id])}}" class="c-experiense-big-link">
                        <figure class="c-experience-big-img">
                            <img src="{{getS3ImageUrl(config('const.blog_image_path') . $value->list_image)}}" alt="">
                        </figure>
                        <div class="c-experience-big-detail">
                            <h2 class="c-experience-big-title">
                                {{ $value->title }}
                            </h2>
                            <p class="c-experience-big-text">
                                @foreach($category as $cvalue)
                                    @if( $value->category_id == $cvalue->key_value)
                                        ●{{ $cvalue->value1}}
                                    @endif
                                    @if( $value->category_2 == $cvalue->key_value)
                                        ●{{ $cvalue->value1}}
                                    @endif
                                    @if( $value->category_3 == $cvalue->key_value)
                                        ●{{ $cvalue->value1}}
                                    @endif
                                @endforeach
                            </p>
                        </div>
                    </a></li>
                    @endforeach

                </ul>

                <div class="c-pagenation-wrap c-search-count-wrap2">
                    @include('common.experience_common_pagenation')
                    <div class="c-search-count">
                        <span class="c-search-count-num">{{ $blogdatacnt }}</span>
                        <span class="c-search-count-text">
                            {{ getPaginationFromToNumText($page, $blogdata->count(), $blogdatacnt, 30) }}
                        </span>
                    </div>
                </div>

                <div class="c-button-wrap-color"><a href="{{ route('TensyokuCpList') }}" class="c-button">就職・転職体験談へ戻る</a></div>
            </div>

            @include('sp.mainparts.experiencelistsidebar')

        </div>
    </main>

    @include('sp.mainparts.bodyfooter')

@include('sp.mainparts.topscript')
</body>
@endsection
