@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')
    @include('sp.mainparts.breadcrumb')

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
                @if ($type_roma === 'judoseifukushi' || $type_roma === 'ammamassajishiatsushi' || $type_roma === 'harikyushi' || $type_roma === 'seitaishi_therapist')
                <div class="bluebox u-m15">
                    <h2>キャリアパートナーに相談する</h2>
                    <h3 class="u-mt20">お気持ちはどちらに近いですか？</h3>
                    <ul class="bluebox-flex u-m10 u-mb20">
                        <li><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.top')]) }}?action=spor-{{$type_roma ?? null}}-motiv-1&branch=A&job_type={{getJobIdFromTypeRoma($type_roma ?? null)}}" class="bluebox-btn"><img src="/woa/img/branch_a.png" alt="近いうちに転職したい" class="u-mb10">近いうちに転職したい</a></li>
                        <li><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.top')]) }}?action=spor-{{$type_roma ?? null}}-motiv-0&branch=B&job_type={{getJobIdFromTypeRoma($type_roma ?? null)}}" class="bluebox-btn"><img src="/woa/img/branch_b.png" alt="今は情報収集したい" class="u-mb10">今は情報収集したい</a></li>
                    </ul>
                </div>
                @endif

                <h2 class="c-title-l"><i class="fa fa-search"></i>都道府県から探す</h2>
                <div class="u-clearfix">
                    <table class="c-table" style="border-top: 0;">
                        <tr>
                            <th class="u-w20">都道府県</th>
                            <td colspan="2">
                                <label class="c-form-select-ic">
                                    <select class="c-form-select" name="pref" id="sp-prefecture-select">
                                        <option value="">都道府県を選択</option>
                                        @foreach($spPrefecturesList as $spPrefectures)
                                            <option value="{{ $spPrefectures->addr1_roma }}">{{ $spPrefectures->addr1 }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </td>
                        </tr>
                    </table>
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
                @include('sp.mainparts.newjob')
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
                @include('sp.mainparts.blankjob')
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

                @include('sp.mainparts.entrybutton', ['_action' => 'spor-jobtypeselect-btn_bottom', 'lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.top')])

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

            @include('sp.mainparts.jobsidebar')
        </div>
        @include('sp.mainparts.syokusyutext')
    </main>
    @include('sp.mainparts.bodyfooter')
    @include('sp.mainparts.topscript')
    <script type="text/javascript">
        $(function () {
            const typeRoma = @json($type_roma);
            $('#sp-prefecture-select').on('change', function () {
                if ($(this).val() && typeRoma) {
                    // route: JobAreaSelect
                    location.href = '/woa/job/' + typeRoma + '/' + $(this).val();
                }
            });
        });
    </script>
</body>
@endsection
