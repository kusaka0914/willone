@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.reentry.8')
    @include('sp.mainparts.bodyheader')
    @include('sp.mainparts.breadcrumb')

    <main class="l-contents">
        <div class="l-contents-container">
            <p class="c-text-link-wrap"><span>{{ date('Y年m月d日',strtotime($job_data->last_confirmed_datetime)) }} 更新</span></p>
            <h1 class="p-job-detail-name"><span>{{ $job_data->office_name }}</span>の求人情報</h1>

            <div class="p-job-detail-nav-wrap fixheader">
                <ul id="detailheader"  class="p-job-detail-nav">
                    <li class="p-job-detail-nav-item"><a href="#jobinfo" class="p-job-detail-nav-link"><i class="fa fa-caret-down" aria-hidden="true"></i>&nbsp;求人情報</a></li>
                </ul>
                <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.detail')]) }}?job_id={{ $job_data->job_id }}&action=spor-woa-entry" class="p-job-detail-conf-btn">現在の募集状況を確認する（無料）</a>
            </div>

            <p class="p-job-detail-catchcopy">{{ $job_data->order_pr_title }}</p>

            <div class="u-clearfix">
                <div class="p-job-detail-photo-wrap">
                    <ul class="p-job-detail-photo">
                        <li><img src="{{ addQuery($job_image) }}" alt=""></li>
                    </ul>
                </div>
                <table class="p-job-detail-tbl c-table">
                    <tr>
                        <th class="u-w20">募集職種</th>
                        <td>{{ $job_data->job_type_name }}</td>
                    </tr>
                    <tr>
                        <th>給与</th>
                        <td>{!! nl2br($job_data->salary) !!}</td>
                    </tr>
                    <tr>
                        <th>最寄駅</th>
                        <td>
                            @for($i = 1; $i <= 3; $i++)
                                @if(!empty($job_data->{'station' . $i}))
                                    @if($i !== 1)
                                        <br>
                                    @endif
                                    {{ getStationText($job_data->{'station' . $i}, $job_data->{'minutes_walk' . $i}) }}
                                @endif
                            @endfor
                        </td>
                    </tr>
                </table>
            </div>

            <div class="c-button-wrap-color">
                <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.detail')]) }}?job_id={{ $job_data->job_id }}&action=spor-woa-entry" class="c-button">現在の募集状況を確認する（無料）</a>
            </div>

            <h2 id="jobinfo" class="c-title-l-bar">{{ $job_data->office_name }}の求人情報</h2>

            <dl class="p-job-detail-dl">
                <dt class="iconPeople">募集職種</dt>
                <dd>
                {{ $job_data->job_type_name }}
                </dd>
            @if( $job_data->detail != "")
                <dt class="iconWork">仕事内容</dt>
                <dd>{!! nl2br($job_data->detail) !!}</dd>
            @endif
            @if( $job_data->dayoff != "")
                <dt class="iconCalender">休日・休暇</dt>
                <dd>{!! nl2br($job_data->dayoff) !!}</dd>
            @endif
            </dl>

            <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.detail')]) }}?job_id={{ $job_data->job_id }}&action=spor-woa-entry"><img src="/woa/images/recruit_info_sp.png" alt="詳しい話を聞いてみたい" class="u-mt20 u-mb20"></a>

            <dl class="p-job-detail-dl">
                <dt>店舗名</dt>
                <dd>{{$job_data->office_name}}</dd>
            @if(!empty($job_data->addr))
                <dt class="iconAdd">勤務地</dt>
                <dd>{{$job_data->addr}}</dd>
            @endif
                <dt class="iconTrain">最寄駅</dt>
                <dd>
                    @for($i = 1; $i <= 3; $i++)
                        @if(!empty($job_data->{'station' . $i}))
                            @if($i !== 1)
                                <br>
                            @endif
                            {{ getStationText($job_data->{'station' . $i}, $job_data->{'minutes_walk' . $i}) }}
                        @endif
                    @endfor
                </dd>
            </dl>

            @if (!empty($company))
            <h2 id="jobdetail" class="c-title-l-bar">{{ $job_data->office_name }}の運営会社情報</h2>

            <table class="c-table">
                <tr>
                    <th class="u-w20">運営会社名</th>
                    <td>{{ $company->company_name}}</td>
                </tr>
                @if ($job_data->publicly_flag == 1)
                <tr>
                    <th>運営店舗</th>
                    <td>
                        <a href="{{ route('CompanyList' , ['id' => $company->id]) }}">{{ $company->company_name . "の求人掲載一覧" }}</a>
                    </td>
                </tr>
                @endif
            </table>
            @endif

            <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.detail')]) }}?job_id={{ $job_data->job_id }}&action=spor-woa-entry"><img src="/woa/images/recruit_info2_sp.png" alt="詳しい話を聞いてみたい" class="u-mt20 u-mb20"></a>

            <div class="c-button-wrap-color">
                <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.detail')]) }}?job_id={{ $job_data->job_id }}&action=spor-woa-entry" class="c-button">現在の募集状況を確認する（無料）</a>
            </div>

            @if (!empty($jobposting) && !empty($job_syokusyu_top))
                <div class="bluebox u-m10">
                    <h2>会員登録で{{$jobposting->job_location_address_region}}エリアの気になる情報をゲット</h2>
                    @if( $job_syokusyu_top->syokusyu_name == "柔道整復師")
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.detail')]) }}?job_id={{ $job_data->job_id }}&action=spor-detail-lst-judo-1" class="c-type-text bluebox-txtlink"><strong>外傷が診られる</strong>求人</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.detail')]) }}?job_id={{ $job_data->job_id }}&action=spor-detail-lst-judo-2" class="c-type-text bluebox-txtlink"><strong>トレーナー活動</strong>あり</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.detail')]) }}?job_id={{ $job_data->job_id }}&action=spor-detail-lst-judo-3" class="c-type-text bluebox-txtlink"><strong>手技自由</strong>/<strong>経験を活かせる</strong>職場</a>

                    @elseif ( $job_syokusyu_top->syokusyu_name == "鍼灸師")
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.detail')]) }}?job_id={{ $job_data->job_id }}&action=spor-detail-lst-hari-1" class="c-type-text bluebox-txtlink"><strong>鍼灸割合が高い</strong>求人</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.detail')]) }}?job_id={{ $job_data->job_id }}&action=spor-detail-lst-hari-2" class="c-type-text bluebox-txtlink"><strong>月給25万円以上</strong>/時給1,400円以上</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.detail')]) }}?job_id={{ $job_data->job_id }}&action=spor-detail-lst-hari-3" class="c-type-text bluebox-txtlink"><strong>手技自由</strong>/<strong>経験を活かせる</strong>職場</a>

                    @elseif ( $job_syokusyu_top->syokusyu_name == "整体師・セラピスト")
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.detail')]) }}?job_id={{ $job_data->job_id }}&action=spor-detail-lst-anma-1" class="c-type-text bluebox-txtlink"><strong>月給23万円以上</strong>/<strong>時給1,200円以上</strong></a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.detail')]) }}?job_id={{ $job_data->job_id }}&action=spor-detail-lst-anma-2" class="c-type-text bluebox-txtlink"><strong>副業WワークOK</strong>の求人</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.detail')]) }}?job_id={{ $job_data->job_id }}&action=spor-detail-lst-anma-3" class="c-type-text bluebox-txtlink"><strong>未経験者歓迎</strong>/<strong>研修制度あり</strong>の求人</a>

                    @endif
                </div>
            @endif
        <div class="l-contents-sub">
            @include('sp.mainparts.searchjobstatelist')
            @include('sp.mainparts.searchjobtypelist')
        </div>
        </div>
    </main>
    @include('sp.mainparts.bodyfooter')
    @include('sp.mainparts.topscript')
    <script src="/woa/js/detailHeader.js"></script>
</body>
@endsection
