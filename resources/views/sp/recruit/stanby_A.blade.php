@include('sp.recruit._header_A', ['job' => $job, 'isPubliclyFlag' => $isPubliclyFlag])

<main class="site_main">
    <article class="target-office">
        <br>
        <a href="{{$entry_path}}">
            <div class="form-box theme-bg-white">
                <div class="form-box-inner">
                    @if (!$isPubliclyFlag || $job->exist_order_flag != 1)
                    <h1 class="feed-detail-name_A">{{$job->addr2_name}}の{{$job->business}} <span style="color: #f9c608;">非公開求人</span></h1>
                    @else
                    <h1 class="feed-detail-name_A">{{$job->office_name}}</h1>
                    @endif
                </div>
            </div>
            <p class="c-text-link-wrap"><span>{{ date('Y年m月d日',strtotime($job->last_confirmed_datetime)) }} 更新</span></p>
            @if ($isPubliclyFlag && $job->order_pr_title != '' && $job->exist_order_flag == 1)
            <div class="recommend-title">・この求人のおすすめポイント！</div>
            <div class="recommend-comment">{!! nl2br(e($job->order_pr_title)) !!}</div>
            @endif
            <br>
            <ul class="feed-job-detail-info_A">
                @if ($job->salary)
                <li>【給与】 {!! nl2br(e($job->salary)) !!}</li>
                @endif
                @if ($job->job_type_name)
                <li>【募集職種】 {{$job->job_type_name}}</li>
                @endif
                @if ($employ_view)
                <li>【雇用形態】 {{$employ_view}}</li>
                @elseif ($job->employment_type)
                <li>【雇用形態】 {{$job->employment_type}}</li>
                @endif
                @if ($job->business)
                <li>【施設】 {{$job->business}}</li>
                @endif
                @if (!empty($job->station))
                <li>【最寄駅】 {{$job->station}}</li>
                @endif
                @if (!empty($job->detail))
                <li>【仕事内容】 {{$job->detail}}</li>
                @endif
                @if (!empty($job->dayoff))
                <li>【休日休暇】 {{$job->dayoff}}</li>
                @endif
            </ul>
            <div class="fx-row fx-row-center-xs" style="text-align: center;">
                <div class="feed-btn1">
                    <span class="btnInner c-button-recommend">応募エントリーする</span>
                </div>
                @if ($job->exist_order_flag != 1)
                <br>
                <span>※こちらの求人は募集状況の確認が必要です</span>
                @endif
            </div>
        </a>
    </article>
</main>
