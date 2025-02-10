@extends('pc.mainparts.head')

@section('content')

<body>
    @include('pc.mainparts.bodyhead')

    <div class="all-contentainer">
        <div class="p-top-fv">
            <div class="p-top-fv-contents">
                <img src="/woa/images/all-free.png" alt="完全無料" class="p-top-fv-contents-free">
                <h2 class="p-top-fv-contents-title">治療家の転職<br>
                学生の就活をサポート</h2>
                <img src="/woa/images/new_logo.png" alt="治療家の転職学生の就活をサポート" class="p-top-fv-contents-logo">
                <img src="/woa/images/new-main.png" alt="アイコン" class="p-top-fv-contents-main">
                <img src="/woa/images/fv-sub-1.png" alt="アイコン" class="p-top-fv-contents-sub-1 p-top-fv-contents-sub">
                <img src="/woa/images/fv-sub-2.png" alt="アイコン" class="p-top-fv-contents-sub-2 p-top-fv-contents-sub">
                <img src="/woa/images/fv-sub-3.png" alt="アイコン" class="p-top-fv-contents-sub-3 p-top-fv-contents-sub">
                <img src="/woa/images/fv-sub-4.png" alt="アイコン" class="p-top-fv-contents-sub-4 p-top-fv-contents-sub">
                <img src="/woa/images/fv-sub-5.png" alt="アイコン" class="p-top-fv-contents-sub-5 p-top-fv-contents-sub">
            </div>
        </div>
        <div class="p-top-head">
            <div class="p-top-head-contents">
                <!-- <img src="/woa/images/top_head_text.jpg" alt="治療家の就職・転職サポート"> -->
                 <p class="p-top-head-contents-text">あなた専任の担当者が、<br>
                 求人票には載らない、職場のリアルを把握した上でご提案</p>
                <ul class="p-top-head-job">
                        <li>
                            <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.top')]) }}?job_type=40&action=pcor-top-btn40" class="p-top-head-job-link">
                                <img class="" src="/woa/images/new-button-1.png" alt="柔道整復師の方はこちら" width="174">
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.top')]) }}?job_type=42&action=pcor-top-btn42" class="p-top-head-job-link">
                                <img class="" src="/woa/images/new-button-2.png" alt="鍼灸師の方はこちら"  width="174">
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.top')]) }}?job_type=41&action=pcor-top-btn41" class="p-top-head-job-link">
                                <img class="" src="/woa/images/new-button-3.png" alt="あん摩・マッサージ・指圧師の方はこちら" width="174">
                            </a>
                        </li>
                    </ul>
            </div>
        </div>
        <div class="p-top-search">
            <div class="l-contents-container l-contents-container-top">
                <form method="post" action="/woa/search">
                    {{ csrf_field() }}
                    <div class="p-top-search-box">
                        <div class="p-top-search-wrap">
                            <div class="p-top-search-title">気になるワードで検索</div>
                            <div class="p-top-search-word-wrap">
                                <div class="p-top-search-word-wrap-searchcontainer">
                                    <input type="text" name="freeword" class="p-top-search-word" placeholder="">
                                    <input class="p-top-search-submit" type="submit" id="search_freeword" value="">
                                </div>
                                <div class="p-top-search-toggle">
                                    <p>詳しい条件で探す</p>
                                    <div class="p-top-search-toggle-img">
                                        <img src="/woa/images/icon _chevron down_.svg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-top-search-hidden">
                            <div class="p-top-search-wrap">
                                <div class="p-top-search-input-wrap">
                                    <div class="p-top-search-title p-top-search-title-hidden">詳細条件を指定</div>
                                    <label class="p-top-search-input-ic">
                                        <select class="p-top-search-input" name="type">
                                            <option value="">職業を選択</option>
                                            @foreach( $syokugyou as $key => $item)
                                            <option value="{{ $key }}">{{ $item['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <!-- <img src="/woa/images/plus.png" alt=""> -->
                                    <label class="p-top-search-input-ic">
                                        <select id="pref" class="p-top-search-input" name="pref">
                                            <option value="">勤務地を選択</option>
                                            @foreach( $kinmuchi as $pref)
                                            <option value="{{ $pref->id }}">{{ $pref->addr1 }}</option>
                                            @endforeach

                                        </select>
                                    </label>
                                    <!-- <img src="/woa/images/plus.png" alt=""> -->
                                    <label class="p-top-search-input-ic">
                                        <select id="state" class="p-top-search-input" name="state">
                                            <option value="">市区町村を選択</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="p-top-search-submit-second-wrap">
                                <img src="/woa/images/search.png" alt="検索アイコン" class="p-top-search-submit-second-icon">
                                <input class="p-top-search-submit-second" type="submit" name="" value="検索する">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <main class="l-contents">
            <div class="l-contents-container">
                <div class="l-contents-main">
                    <div class="p-license-select">
                        <div class="p-license-select-title">求人を検索する</div>
                        <div class="p-license-select-nav">
                            <div class="p-license-select-item active" data-license="judoseifukushi">柔道整復師</div>
                            <div class="p-license-select-item" data-license="ammamassajishiatsushi">あん摩マッサージ指圧師</div>
                            <div class="p-license-select-item" data-license="harikyushi">鍼灸師</div>
                        </div>
                    </div>
                    <div class="p-top-area-container">
                    <div class="c-title-l-container-bg c-title-l-container-bg-1"></div>
                        <div class="c-title-l-container">
                            <h2 class="c-title-l">
                                <img src="/woa/images/search-icon.png" alt="検索アイコン">
                                <span class="c-variable-title">柔道整復師</span>の求人を<span class="c-variable-title">エリアから</span>検索する
                            </h2>
                            <div class="u-clearfix">
                                @foreach ($prefecturesList as $areaValue)
                                <div class="p-type-area">
                                    <h3 class="c-title-m">{{ $areaValue->name }}</h3>
                                    <ul class="c-area">
                                        @foreach ($areaValue->prefectures as $prefecture)
                                            <li class="c-area-item"><a class="c-area-link" href="{{ route('AreaSelect', ['id' => $prefecture->roma]) }}"><img src="/woa/images/area-vector.png" alt="エリア" class="c-area-link-img">{{ $prefecture->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="p-top-area-container">
                    <div class="c-title-l-container-bg c-title-l-container-bg-2"></div>
                        <div class="c-title-l-container">
                            <h2 class="c-title-l">
                                <img src="/woa/images/search-icon.png" alt="検索アイコン">
                                <span class="c-variable-title">柔道整復師</span>の求人を人気エリアから検索する
                            </h2>
                            <div class="u-clearfix">
                                @foreach( $popCities as $popCityRomaName => $popCityData)
                                <div class="p-top-area">
                                    <h3 class="c-title-m">{{ $popCityData['prefName'] }}</h3>
                                    <ul class="c-area">
                                        @foreach( $popCityData['cities'] as $popCityValue)
                                            <li class="c-area-item"><a class="c-area-link" href="{{ route('AreaStateSelect' , ['pref' => $popCityRomaName , 'state' => $popCityValue['roma'] ])}}"><img src="/woa/images/area-vector.png" alt="エリア" class="c-area-link-img">{{ $popCityValue['name'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @include('pc.mainparts.jobtype')

                    <div class="instructor-link">
                        <a href="/woa/interview-fti-1">
                            <div class="instructor-img-container">
                                <div class="instructor-img-container-left">
                                    <p class="instructor-img-container-left-tag">特集</p>
                                    <p class="instructor-img-container-left-title">機能訓練指導員</p>
                                    <p class="instructor-img-container-left-subtitle">として働く治療家インタビュー</p>
                                    <p class="instructor-img-container-left-name">Tさん（36歳）・柔道整復師</p>
                                </div>
                                <img src="/woa/images/new-instructor-banner1-pc.png" alt="機能訓練指導員として働く治療家インタビュー">
                            </div>
                        </a>
                        <a href="/woa/interview-fti-2">
                            <div class="instructor-img-container">
                                <div class="instructor-img-container-left">
                                    <p class="instructor-img-container-left-tag">特集</p>
                                    <p class="instructor-img-container-left-title">機能訓練指導員</p>
                                    <p class="instructor-img-container-left-subtitle">として働く治療家インタビュー</p>
                                    <p class="instructor-img-container-left-name">Mさん（47歳）・柔道整復師</p>
                                </div>
                                <img src="/woa/images/new-instructor-banner2-pc.png" alt="機能訓練指導員として働く治療家インタビュー">
                            </div>
                        </a>
                    </div>

                    @include('pc.mainparts.newjob')

                    @include('pc.mainparts.entrybutton', ['_action' => 'pcor-top-btn_bottom', 'lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.top')])
                </div>

                

            </div>
            <!-- @include('pc.mainparts.syokusyutext') -->
        </main>
    </div>

    @include('pc.mainparts.bodyfooter')

    @include('pc.mainparts.topscript')
    <script>
        $(document).ready(function(){
            // フリーワードの検索ボタン押下時は、検索対象はフリーワードのみのため、選択状態を解除する
            $("#search_freeword").on('click', function() {
                $('select[name="type"] option').attr("selected", false);
                $('select[name="pref"] option').attr("selected", false);
                $('select[name="state"] option').attr("selected", false);
            });

        });
    </script>
</body>
@endsection
