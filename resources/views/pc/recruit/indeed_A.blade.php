@include('pc.recruit._header', ['job' => $job, 'isPubliclyFlag' => $isPubliclyFlag])
<article class="target-office">
    <br>
    <div id="inner-box" style="box-shadow:0px 0px;margin:15px auto 0;width:800px;">
        <a href="{{$entry_path}}">
            <div class="form-box theme-bg-white">
                <div class="form-box-inner">
                    @if (!$isPubliclyFlag || $job->exist_order_flag != 1)
                    <h1 class="feed-detail-name">{{$job->addr2_name}}の{{$job->business}} <span style="color: #f9c608;">非公開求人</span></h1>
                    @else
                    <h1 class="feed-detail-name">{{$job->office_name}}</h1>
                    @endif
                </div>
            </div>
            <p class="c-text-link-wrap"><span>{{ date('Y年m月d日',strtotime($job->last_confirmed_datetime)) }} 更新</span></p>
            @if ($isPubliclyFlag && $job->order_pr_title != '' && $job->exist_order_flag == 1)
            <div class="recommend-title">・この求人のおすすめポイント！</div>
            <div class="recommend-comment">{!! nl2br(e($job->order_pr_title)) !!}</div>
            @endif
            <div style="padding:1em 0;">
                <ul class="feed-job-detail-info">
                    @if ($job->salary)
                    <li>【給与】 {!! nl2br(e($job->salary)) !!}</li>
                    @endif
                    @if ($job->job_type_name)
                    <li>【募集職種】 {{$job->job_type_name}}</li>
                    @endif
                    @if ($job->employment_type)
                    <li>【雇用形態】 {{$job->employment_type}}</li>
                    @endif
                    @if ($isPubliclyFlag && $job->exist_order_flag == 1 && $job->company_name)
                    <li>【法人名】 {{$job->company_name}}</li>
                    @endif
                    @if ($job->addr || $job->addr1_name)
                    @if ($isPubliclyFlag && $job->exist_order_flag == 1)
                    <li>【勤務地】 {{$job->postal_code}} {{$job->addr}}</li>
                    @else
                    <li>【勤務地】 {{$job->addr1_name}}{{$job->addr2_name}}</li>
                    @endif
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
            </div>
            <div class="fx-row fx-row-center-xs" style="text-align: center;">
                <div class="feed-btn1">
                    <span class="btnInner c-button-recommend">応募エントリーする</span>
                </div>
                @if ($job->exist_order_flag != 1)
                <span>※こちらの求人は募集状況の確認が必要です</span>
                @endif
            </div>
        </a>
    </div>
</article>
