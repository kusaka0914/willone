@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')

    @include('sp.mainparts.breadcrumb')

    <div class="p-list-head">
        <div class="l-contents-container">
            <h1 class="p-list-head-title">{{ $title }}求人一覧</h1>
            <p class="p-list-head-text">
                『ウィルワン』では、寮完備の求人も多数掲載しています。暮らしに不安がある方も、安心して就職後の生活がスタートできるようしっかりサポートしますよ。
            </p>
            <div class="p-list-head-btn">
                <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?action=spor-sresult-header&job_type={{getJobIdFromTypeRoma($type_roma ?? null)}}" class="c-button">この地域の希望通りの求人を紹介してもらう <span>無料 <i class="fa fa-angle-double-right"></i></span></a>
            </div>
        </div>
    </div>

    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

                @if( !empty($pref) )
                <div class="u-none">
                    <form method="post" action="{{ route('Redirect')}}" id="searchForm">
                        {{ csrf_field()}}
                        @if(isset($type_roma))
                            <input type="hidden" name="link_type" value="2">
                            <input type="hidden" name="type" value="{{ $type_roma }}">
                        @else
                            <input type="hidden" name="link_type" value="1">
                            <input type="hidden" name="type" value="">
                        @endif
                        <input type="hidden" name="pref" value="{{ $pref_roma }}">
                        <input type="hidden" name="pref_id" value="{{ $pref }}">

                        <div class="c-explore">
                            <div class="c-explore-line">
                                <div class="c-explore-title u-w20">市区町村</div>
                                <div class="c-explore-item">
                                    <label class="c-form-select-ic">
                                        <select class="c-form-select" name="state" id="municipalitiesSelect" onchange="municipalitiesChange();">
                                            @foreach($state_data as $value)
                                                <option value="{{ $value->addr2_roma }}" data-id="{{ $value->id }}"{{ (!empty($state) && $state === $value['id']) ? ' selected' : '' }}>
                                                    {{ $value->addr2 }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                            </div>
                            @if(isset($search_near_cities))
                                @include('sp.job.parts.searchConditions')
                            @endif
                        </div>

                        <div class="c-button-wrap c-search-count-wrap">
                            <div class="c-search-count">
                                <span class="c-search-count-text">該当件数</span><br>
                                <span class="c-search-count-num">{{ $job_count }}</span>
                                <span class="c-search-count-text">件</span>
                            </div>
                        </div>
                    </form>
                </div>
                <hr>
                @endif

                <div class="bluebox u-m15">
                    <h2>キャリアパートナーに相談する</h2>
                    <h3 class="u-mt20">お気持ちはどちらに近いですか？</h3>
                    <ul class="bluebox-flex u-m10 u-mb20">
                        <li><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?job_type={{getJobIdFromTypeRoma($type_roma ?? null)}}&action=spor-seach-motiv-1&branch=A" class="bluebox-btn"><img src="/woa/img/branch_a.png" alt="近いうちに転職したい" class="u-mb10">近いうちに転職したい</a></li>
                        <li><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?job_type={{getJobIdFromTypeRoma($type_roma ?? null)}}&action=spor-seach-motiv-0&branch=B" class="bluebox-btn"><img src="/woa/img/branch_b.png" alt="今は情報収集したい" class="u-mb10">今は情報収集したい</a></li>
                    </ul>
                </div>

                <ul class="c-job-big">
                    {{-- PR求人 対象が1件のみで1番目指定 or 対象が複数件の場合は2件まで上部表示 --}}
                    @php $pr_i = 0; @endphp
                    @if ($pr_job_data->isNotEmpty() && ($pr_job_data[0]->pr_display_position === config('ini.PR_DISPLAY_POSITION.first') || $pr_job_data->count() != 1))
                    @foreach($pr_job_data as $pr_job)

                    @php if($loop->iteration > 2) break; @endphp
                    <li class="c-job-big-item c-job-big-item-pr">
                        <div class="c-job-pr-label">
                            <i class="fa fa-check-circle"></i>注目求人
                        </div>
                        <div class="c-job-big-name-wrap">
                            <h2 class="c-job-big-name"><a href="{{ route('OpportunityDetail' , ['id' => $pr_job['job_id']]) }}">{{ $pr_job['office_name'] }}</a></h2>
                            <div class="c-job-big-genre-wrap"><span class="c-job-big-genre"></span></div>
                        </div>
                        <div class="c-job-big-detail">
                            <table class="c-job-big-tbl">
                                <tr class="c-job-big-tbl-pickup">
                                    <th>募集職種</th>
                                    <td>{{ $pr_job['job_type_name'] }}</td>
                                </tr>
                                <tr>
                                    <th>勤務地</th>
                                    <td>{{ $pr_job['addr'] }}</td>
                                </tr>
                                <tr>
                                    <th>最寄駅</th>
                                    <td>{{ getStationText($pr_job['station1'], $pr_job['minutes_walk1']) }}</td>
                                </tr>
                                <tr>
                                    <th>給与</th>
                                    <td>{!! nl2br($pr_job['salary']) !!}</td>
                                </tr>
                                <tr>
                                    <th>休日・休暇</th>
                                    <td>{!! nl2br($pr_job['dayoff']) !!}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="c-button-wrap">
                            <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?job_id={{ $pr_job['job_id'] }}&action=spor-sresult-btn&job_type={{getJobIdFromTypeRoma($type_roma ?? null)}}" class="c-button">現在の募集要項を確認する <span>無料 <i class="fa fa-angle-double-right"></i></span></a>
                        </div>
                    </li>
                    @php $pr_i++; @endphp
                    @endforeach
                    @endif

                    @foreach($job_data as $job)
                    {{-- PR求人 対象が1件のみで2番目指定 or 3件目がある場合求人リストの2番目に表示 --}}
                    @if ($loop->iteration === 2 && ($pr_job_data->isNotEmpty() && $pr_job_data[0]->pr_display_position === config('ini.PR_DISPLAY_POSITION.second') || !empty($pr_job_data[2])))
                        <li class="c-job-big-item c-job-big-item-pr">
                            <div class="c-job-pr-label">
                                <i class="fa fa-check-circle"></i>注目求人
                            </div>
                            <div class="c-job-big-name-wrap">
                                <h2 class="c-job-big-name"><a href="{{ route('OpportunityDetail' , ['id' => $pr_job_data[$pr_i]['job_id']]) }}">{{ $pr_job_data[$pr_i]['office_name'] }}</a></h2>
                                <div class="c-job-big-genre-wrap"><span class="c-job-big-genre"></span></div>
                            </div>
                            <div class="c-job-big-detail">
                                <table class="c-job-big-tbl">
                                    <tr class="c-job-big-tbl-pickup">
                                        <th>募集職種</th>
                                        <td>{{ $pr_job_data[$pr_i]['job_type_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>勤務地</th>
                                        <td>{{ $pr_job_data[$pr_i]['addr'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>最寄駅</th>
                                        <td>{{ getStationText($pr_job_data[$pr_i]['station1'], $pr_job_data[$pr_i]['minutes_walk1']) }}</td>
                                    </tr>
                                    <tr>
                                        <th>給与</th>
                                        <td>{!! nl2br($pr_job_data[$pr_i]['salary']) !!}</td>
                                    </tr>
                                    <tr>
                                        <th>休日・休暇</th>
                                        <td>{!! nl2br($pr_job_data[$pr_i]['dayoff']) !!}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="c-button-wrap">
                                <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?job_id={{ $pr_job_data[$pr_i]['job_id'] }}&action=spor-sresult-btn&job_type={{getJobIdFromTypeRoma($type_roma ?? null)}}" class="c-button">現在の募集要項を確認する <span>無料 <i class="fa fa-angle-double-right"></i></span></a>
                            </div>
                        </li>
                        @endif
                    <li class="c-job-big-item">
                        <div class="c-job-big-name-wrap">
                            <h2 class="c-job-big-name"><a href="{{ route('OpportunityDetail' , ['id' => $job['job_id']]) }}">{{ $job['office_name'] }}</a></h2>
                            <div class="c-job-big-genre-wrap"><span class="c-job-big-genre"></span></div>
                        </div>
                        <div class="c-job-big-detail">
                            <table class="c-job-big-tbl">
                                <tr class="c-job-big-tbl-pickup">
                                    <th>募集職種</th>
                                    <td>{{ $job['job_type_name'] }}</td>
                                </tr>
                                <tr>
                                    <th>勤務地</th>
                                    <td>{{ $job['addr'] }}</td>
                                </tr>
                                <tr>
                                    <th>最寄駅</th>
                                    <td>{{ getStationText($job['station1'], $job['minutes_walk1']) }}</td>
                                </tr>
                                <tr>
                                    <th>給与</th>
                                    <td>{!! nl2br($job['salary']) !!}</td>
                                </tr>
                                <tr>
                                    <th>休日・休暇</th>
                                    <td>{!! nl2br($job['dayoff']) !!}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="c-button-wrap">
                            <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?job_id={{ $job['job_id'] }}&action=spor-sresult-btn&job_type={{getJobIdFromTypeRoma($type_roma ?? null)}}" class="c-button">現在の募集要項を確認する <span>無料 <i class="fa fa-angle-double-right"></i></span></a>
                        </div>
                    </li>
                    @if( ($loop->iteration % 5) === 0 )
                    {{-- 求人が0件の場合はバナーを表示しない仕様あり --}}
                    <li class="c-job-secret-item">
                        <div class="c-job-secret-title"><i class="fa fa-lock"></i> {{$privateJobsPrefLabel ?? '東京都'}}のおすすめ<span>非公開求人</span></div>
                        <div class="c-job-secret-detail">
                            <div class="c-job-secret-detail-head"><span>▼</span> 地域相場より給与高めの非公開求人 <span>▼</span></div>
                            <table class="c-job-big-tbl">
                                <tr>
                                    <th>特徴</th>
                                    <td>
                                        <ul class="c-job-highlight">
                                            <li class="c-job-highlight-item"><i class="fa fa-music"></i>年間休日110日以上</li>
                                            <li class="c-job-highlight-item"><i class="fa fa-music"></i>土日祝休み</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>勤務時間</th>
                                    <td>8:30-17:30（休憩60分）</td>
                                </tr>
                                <tr>
                                    <th>給与</th>
                                    <td>常勤 月給32万円</td>
                                </tr>
                            </table>
                            <a class="c-job-secret-btn" href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?action=spor-jobsecret-btn&job_type={{getJobIdFromTypeRoma($type_roma ?? null)}}">
                                <span>地域相場</span><span>より</span><span>給与高め</span><span>の</span><span>非公開求人</span><span>を</span><span>紹介</span><span>してもらう</span>
                            </a>
                        </div>
                    </li>
                    @endif
                    @endforeach
                </ul>

                @include('sp.mainparts.pagenation')

                @if( !empty($type_name) )

                <div class="bluebox u-m10">
                    <h2>会員登録で@if( !empty($pref) ){{$pref_name}}エリアの@endif気になる情報をゲット</h2>
                    @if( $type_name == "柔道整復師")
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?action=spor-search-lst-judo-1-bottom&job_type=40" class="c-type-text bluebox-txtlink"><strong>外傷が診られる</strong>求人</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?action=spor-search-lst-judo-2-bottom&job_type=40" class="c-type-text bluebox-txtlink"><strong>トレーナー活動</strong>あり</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?action=spor-search-lst-judo-3-bottom&job_type=40" class="c-type-text bluebox-txtlink"><strong>手技自由</strong>/<strong>経験を活かせる</strong>職場</a>

                    @elseif ( $type_name == "鍼灸師")
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?action=spor-search-lst-hari-1-bottom&job_type=42" class="c-type-text bluebox-txtlink"><strong>鍼灸割合が高い</strong>求人</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?action=spor-search-lst-hari-2-bottom&job_type=42" class="c-type-text bluebox-txtlink"><strong>月給25万円以上</strong>/時給1,400円以上</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?action=spor-search-lst-hari-3-bottom&job_type=42" class="c-type-text bluebox-txtlink"><strong>手技自由</strong>/<strong>経験を活かせる</strong>職場</a>

                    @elseif ( $type_name == "整体師・セラピスト")
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?action=spor-search-lst-anma-1-bottom&job_type=43" class="c-type-text bluebox-txtlink"><strong>月給23万円以上</strong>/<strong>時給1,200円以上</strong></a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?action=spor-search-lst-anma-2-bottom&job_type=43" class="c-type-text bluebox-txtlink"><strong>副業WワークOK</strong>の求人</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?action=spor-search-lst-anma-3-bottom&job_type=43" class="c-type-text bluebox-txtlink"><strong>未経験者歓迎</strong>/<strong>研修制度あり</strong>の求人</a>
                    @endif

                </div>
                @endif

            </div>

            <div class="l-contents-sub">
                @include('sp.mainparts.searchjobstatelist')
                @include('sp.mainparts.searchjobtypelist')
            </div>

            @include('sp.mainparts.normalsidebar')
        </div>
    </main>
    @include('sp.mainparts.bodyfooter')

@include('sp.mainparts.topscript')
<script src="/woa/js/job/list.js?20221208"></script>
<div class="popup">
    <div class="popup-content">
        <div class="popup__close">
            <i class="fa fa-times" aria-hidden="true"></i>
        </div>
        <p class="popup__title">無料会員登録して<br/>詳しく求人を見てみませんか？</p>
        <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.list')]) }}?action={{config('app.device')}}or-scrl-pop&job_type={{getJobIdFromTypeRoma($type_roma ?? null)}}" class="popup__btn">登録する</a>
    </div>
</div>
</body>
@endsection
