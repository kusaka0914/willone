@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')

    @include('pc.mainparts.breadcrumb')

    <div class="p-type-head">
        <div class="p-type-head-box">
            <h1>{{ $title }}の求人</h1>
            <div class="jobOffers">
                <p class="jobOffers-num">
                    <span>{{ $job_count }}</span>
                    件の求人があります
                </p>
            </div>
        </div>
    </div>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">
                <h2 class="c-title-l"><i class="fa fa-search"></i>都道府県から探す</h2>
                <div class="u-clearfix">
                    @foreach ($prefecturesList as $areaValue)
                    <div class="p-type-area">
                        <h3 class="c-title-m">{{ $areaValue->name }}</h3>
                        <ul class="c-area">
                            @foreach ($areaValue->prefectures as $prefecture)
                                <li class="c-area-item"><a class="c-area-link-active" href="{{ route('JobAreaSelect', ['pref' => $prefecture->roma, 'id' => $type_roma]) }}">{{ $prefecture->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>

                <h2 class="c-title-l"><i class="fa fa-search"></i>人気エリアから探す</h2>
                <div class="u-clearfix">
                    @foreach( $popCities as $popCityRomaName => $popCityData)
                    <div class="p-top-area">
                        <h3 class="c-title-m">{{ $popCityData['prefName'] }}</h3>
                        <ul class="c-area">
                            @foreach( $popCityData['cities'] as $popCityValue)
                                <li class="c-area-item"><a class="c-area-link-active" href="{{ route('JobAreaStateSelect', ['pref' => $popCityRomaName , 'state' => $popCityValue['roma'], 'id' => $type_roma ])}}">{{ $popCityValue['name'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>

                @if (count($new_job) > 0)
                @include('pc.mainparts.newjob')
                <div class="c-button-wrap">
                    <form method="post" action="{{ route('JobNewList' ,['type' => $type_roma])}}">
                    {{ csrf_field()}}
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="new" value="1">
                    <input class="c-button" type="submit" name="" value="新着求人をもっと見る">
                </form>
                </div>
                @endif

                @if (count($new_job) > 0)
                @include('pc.mainparts.blankjob')
                <div class="c-button-wrap">
                    <form method="post" action="{{ route('JobBlankList',['type' => $type_roma])}}">
                    {{ csrf_field()}}
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="new" value="1">
                    <input type="hidden" name="blank" value="1">
                    <input class="c-button" type="submit" name="" value="ブランクOKの求人をもっと見る">
                </form>
                </div>
                @endif

                @include('pc.mainparts.entrybutton', ['_action' => 'pcor-jobtypeselect-btn_bottom', 'lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.top')])


                <!-- <h2 class="c-title-l-bar">就職・転職体験談</h2>
                <ul class="c-experience">
                    <li class="c-experience-item"><a href="" class="c-experience-link">
                        <div class="c-experience-img" style="background-image:url(/woa/images/experience_img_sample.png)"></div>
                        <div class="c-experience-detail">
                            <h3 class="c-experience-title">タイトル全文表示 横並びのリストの高さを合わせて崩れないように</h3>
                            <div class="c-experience-name">
                                <span class="p-experience-category-change">転職</span>
                                Y.Kさん
                            </div>
                        </div>
                    </a></li>
                    <li class="c-experience-item"><a href="" class="c-experience-link">
                        <div class="c-experience-img" style="background-image:url(/woa/images/experience_img_sample.png)"></div>
                        <div class="c-experience-detail">
                            <h3 class="c-experience-title">タイトル全文表示 横並びのリストの高さを合わせて崩れないように</h3>
                            <div class="c-experience-name">
                                <span class="p-experience-category-new">新卒</span>
                                Y.Kさん
                            </div>
                        </div>
                    </a></li>
                    <li class="c-experience-item"><a href="" class="c-experience-link">
                        <div class="c-experience-img" style="background-image:url(/woa/images/experience_img_sample.png)"></div>
                        <div class="c-experience-detail">
                            <h3 class="c-experience-title">タイトル全文表示 横並びのリストの高さを合わせて崩れないように</h3>
                            <div class="c-experience-name">
                                <span class="p-experience-category-change">転職</span>
                                Y.Kさん
                            </div>
                        </div>
                    </a></li>
                    <li class="c-experience-item"><a href="" class="c-experience-link">
                        <div class="c-experience-img" style="background-image:url(/woa/images/experience_img_sample.png)"></div>
                        <div class="c-experience-detail">
                            <h3 class="c-experience-title">タイトル全文表示 横並びのリストの高さを合わせて崩れないように</h3>
                            <div class="c-experience-name">
                                <span class="p-experience-category-new">新卒</span>
                                Y.Kさん
                            </div>
                        </div>
                    </a></li>
                    <li class="c-experience-item"><a href="" class="c-experience-link">
                        <div class="c-experience-img" style="background-image:url(/woa/images/experience_img_sample.png)"></div>
                        <div class="c-experience-detail">
                            <h3 class="c-experience-title">タイトル全文表示 横並びのリストの高さを合わせて崩れないように</h3>
                            <div class="c-experience-name">
                                <span class="p-experience-category-change">転職</span>
                                Y.Kさん
                            </div>
                        </div>
                    </a></li>
                    <li class="c-experience-item"><a href="" class="c-experience-link">
                        <div class="c-experience-img" style="background-image:url(/woa/images/experience_img_sample.png)"></div>
                        <div class="c-experience-detail">
                            <h3 class="c-experience-title">タイトル全文表示 横並びのリストの高さを合わせて崩れないように</h3>
                            <div class="c-experience-name">
                                <span class="p-experience-category-new">新卒</span>
                                Y.Kさん
                            </div>
                        </div>
                    </a></li>
                </ul>

                <h2 class="c-title-l-bar">転職成功事例</h2>
                <ul class="c-column">
                    <li class="c-column-item">
                        <a class="c-column-link" href="">
                            <div class="c-column-img-wrap">
                                <div class="c-column-img-wrap">
                                    <div class="c-column-img" style="background-image:url(/woa/images/column_icon_sample.png)"></div>
                                </div>
                            </div>
                            <div class="c-column-detail matchHeight">
                                <div class="c-column-title">記事タイトルが入ります 2行超えたら...を表示</div>
                                <div class="c-column-text">情報量がまちまちになる可能性があるため、崩れないよう横並びのリストの高さを合わせる</div>
                            </div>
                        </a>
                    </li>
                    <li class="c-column-item">
                        <a class="c-column-link" href="">
                            <div class="c-column-img-wrap">
                                <div class="c-column-img-wrap">
                                    <div class="c-column-img" style="background-image:url(/woa/images/column_icon_sample.png)"></div>
                                </div>
                            </div>
                            <div class="c-column-detail matchHeight">
                                <div class="c-column-title">記事タイトルが入ります 2行超えたら...を表示</div>
                                <div class="c-column-text">情報量がまちまちになる可能性があるため、崩れないよう横並びのリストの高さを合わせる</div>
                            </div>
                        </a>
                    </li>
                    <li class="c-column-item">
                        <a class="c-column-link" href="">
                            <div class="c-column-img-wrap">
                                <div class="c-column-img-wrap">
                                    <div class="c-column-img" style="background-image:url(/woa/images/column_icon_sample.png)"></div>
                                </div>
                            </div>
                            <div class="c-column-detail matchHeight">
                                <div class="c-column-title">記事タイトルが入ります 2行超えたら...を表示</div>
                                <div class="c-column-text">情報量がまちまちになる可能性があるため、崩れないよう横並びのリストの高さを合わせる</div>
                            </div>
                        </a>
                    </li>
                    <li class="c-column-item">
                        <a class="c-column-link" href="">
                            <div class="c-column-img-wrap">
                                <div class="c-column-img-wrap">
                                    <div class="c-column-img" style="background-image:url(/woa/images/column_icon_sample.png)"></div>
                                </div>
                            </div>
                            <div class="c-column-detail matchHeight">
                                <div class="c-column-title">記事タイトルが入ります 2行超えたら...を表示</div>
                                <div class="c-column-text">情報量がまちまちになる可能性があるため、崩れないよう横並びのリストの高さを合わせる</div>
                            </div>
                        </a>
                    </li>
                </ul>

                -->
                <h2 class="c-title-l"><i class="fa fa-search"></i>その他職種から探す</h2>
                <ul class="c-type">
                    <li class="c-type-item">
                        <a href="{{ route('JobSelect' , ['id' => 'judoseifukushi'])}}" class="c-type-link">
                            <div class="c-type-img" style="background-image:url(/woa/images/search_type_icon1.png)"></div>
                            <span class="c-type-text">柔道整復師</span>
                        </a>
                    </li>
                    <li class="c-type-item">
                        <a href="{{ route('JobSelect' , ['id' => 'ammamassajishiatsushi'])}}" class="c-type-link">
                            <div class="c-type-img" style="background-image:url(/woa/images/search_type_icon2.png)"></div>
                            <span class="c-type-text">あん摩マッサージ<br>指圧師</span>
                        </a>
                    </li>
                    <li class="c-type-item">
                        <a href="{{ route('JobSelect' , ['id' => 'harikyushi'])}}" class="c-type-link">
                            <div class="c-type-img" style="background-image:url(/woa/images/search_type_icon3.png)"></div>
                            <span class="c-type-text">鍼灸師</span>
                        </a>
                    </li>
                    <li class="c-type-item">
                        <a href="{{ route('JobSelect' , ['id' => 'seitaishi_therapist'])}}" class="c-type-link">
                            <div class="c-type-img" style="background-image:url(/woa/images/search_type_icon4.png)"></div>
                            <span class="c-type-text">整体師・セラピスト</span>
                        </a>
                    </li>
                </ul>
            </div>

            @include('pc.mainparts.typeselectsidebar')
        </div>
        @include('pc.mainparts.syokusyutext')
    </main>
    @include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
</body>
@endsection
