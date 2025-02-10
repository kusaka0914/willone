@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.reentry.8')
    @include('pc.mainparts.bodyhead')

    @include('pc.mainparts.breadcrumb')
    <main class="l-contents">
        <div class="l-contents-container">
            <h1 class="p-job-detail-name"><span>{{ $job_data->office_name }}</span>の求人情報</h1>
            <div class="p-job-detail-nav-wrap fixheader">
                <ul id="detailheader"  class="p-job-detail-nav">
                    <li class="p-job-detail-nav-item"><a href="#jobinfo" class="p-job-detail-nav-link">求人情報</a></li>
                </ul>
                <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.detail')]) }}?job_id={{ $job_data->job_id }}&action=pcor-woa-entry" class="p-job-detail-conf-btn">現在の募集状況を確認する（無料）</a>
            </div>

            <p class="p-job-detail-catchcopy">{{ $job_data->order_pr_title }}</p>

            <p class="c-text-link-wrap"><span>{{ date('Y年m月d日',strtotime($job_data->last_confirmed_datetime)) }} 更新</span></p>
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
                 <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.detail')]) }}?job_id={{ $job_data->job_id }}&action=pcor-woa-entry" class="c-button">現在の募集状況を確認する（無料）</a>
            </div>

            <h2 id="jobinfo" class="c-title-l-bar">{{ $job_data->office_name }}の求人情報</h2>

            <table class="c-table">
                <tr>
                    <th class="u-w20">募集職種</th>
                    <td>
                    {{ $job_data->job_type_name }}<br>
                    </td>
                </tr>
                @if(!empty($job_data->detail))
                <tr>
                    <th>仕事内容</th>
                    <td>{!! nl2br($job_data->detail) !!}</td>
                </tr>
                @endif
                @if( $job_data->dayoff != "")
                <tr>
                    <th>休日・休暇</th>
                    <td>{!! nl2br($job_data->dayoff) !!}</td>
                </tr>
                @endif
            </table>

            <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.detail')]) }}?job_id={{ $job_data->job_id }}&action=pcor-woa-entry" style="display:block"><img src="/woa/images/recruit_info.png" alt="詳しい話を聞いてみたい"></a>

            <table class="c-table">
                <tr>
                    <th>店舗名</th>
                    <td>{{ $job_data->office_name }}</td>
                </tr>
                <tr>
                    <th>勤務地</th>
                    <td>{{ $job_data->addr }}</td>
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

            @if (!empty($company))
            <h2 class="c-title-l-bar">{{ $job_data->office_name }}の運営会社情報</h2>

            <table class="c-table">
                <tr>
                    <th class="u-w20">運営会社名</th>
                    <td>{{ $company->company_name}}</td>
                </tr>
                @if ($job_data->publicly_flag == 1)
                <tr>
                    <th>運営店舗</th>
                    <td>
                        <a href="{{ route('CompanyList' , ['id' => $job_data->company_id]) }}">{{ $company->company_name . "の求人掲載一覧" }}</a>
                    </td>
                </tr>
                @endif
            </table>
            @endif

            <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.detail')]) }}?job_id={{ $job_data->job_id }}&action=pcor-woa-entry" style="display:block"><img src="/woa/images/recruit_info2.png" alt="詳しい話を聞いてみたい"></a>

             @if (!empty($jobposting) && !empty($job_syokusyu_top))
                <div class="bluebox u-mt30 u-mb30">
                    <h2>会員登録で{{$jobposting->job_location_address_region}}エリアの気になる情報をゲット</h2>
                    @if( $job_syokusyu_top->syokusyu_name == "柔道整復師")
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.detail')]) }}?job_id={{ $job_data->job_id }}&action=pcor-detail-lst-judo-1" class="c-type-text bluebox-txtlink"><strong>外傷が診られる</strong>求人</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.detail')]) }}?job_id={{ $job_data->job_id }}&action=pcor-detail-lst-judo-2" class="c-type-text bluebox-txtlink"><strong>トレーナー活動</strong>あり</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.detail')]) }}?job_id={{ $job_data->job_id }}&action=pcor-detail-lst-judo-3" class="c-type-text bluebox-txtlink"><strong>手技自由</strong>/<strong>経験を活かせる</strong>職場</a>

                    @elseif ( $job_syokusyu_top->syokusyu_name == "鍼灸師")
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.detail')]) }}?job_id={{ $job_data->job_id }}&action=pcor-detail-lst-hari-1" class="c-type-text bluebox-txtlink"><strong>鍼灸割合が高い</strong>求人</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.detail')]) }}?job_id={{ $job_data->job_id }}&action=pcor-detail-lst-hari-2" class="c-type-text bluebox-txtlink"><strong>月給25万円以上</strong>/時給1,400円以上</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.detail')]) }}?job_id={{ $job_data->job_id }}&action=pcor-detail-lst-hari-3" class="c-type-text bluebox-txtlink"><strong>手技自由</strong>/<strong>経験を活かせる</strong>職場</a>

                    @elseif ( $job_syokusyu_top->syokusyu_name == "整体師・セラピスト")
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.detail')]) }}?job_id={{ $job_data->job_id }}&action=pcor-detail-lst-anma-1" class="c-type-text bluebox-txtlink"><strong>月給23万円以上</strong>/<strong>時給1,200円以上</strong></a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.detail')]) }}?job_id={{ $job_data->job_id }}&action=pcor-detail-lst-anma-2" class="c-type-text bluebox-txtlink"><strong>副業WワークOK</strong>の求人</a>
                    <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.detail')]) }}?job_id={{ $job_data->job_id }}&action=pcor-detail-lst-anma-3" class="c-type-text bluebox-txtlink"><strong>未経験者歓迎</strong>/<strong>研修制度あり</strong>の求人</a>

                    @endif
                </div>
            @endif

        </div>
        <div class="l-contents-under">
            @include('pc.mainparts.searchjobstatelist')
            @include('pc.mainparts.searchjobtypelist')
        </div>
    </main>
    @include('pc.mainparts.bodyfooter')
@include('pc.mainparts.topscript')
</body>
@endsection
