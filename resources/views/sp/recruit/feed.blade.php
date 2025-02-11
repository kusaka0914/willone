@include('sp.recruit._header', ['job' => $job, 'isPubliclyFlag' => $isPubliclyFlag])

<main class="site_main">
    <article class="target-office">
        <br>
        <div class="form-box theme-bg-white">
            <div class="form-box-inner">
            @if(!$isPubliclyFlag)
                <h1 class="feed-detail-name">{{$job->addr2_name}}の{{$job->business}}  <span style="color: #f9c608;">非公開求人</span></h1>
            @else
                <h1 class="feed-detail-name">{{$job->office_name}}</h1>
            @endif
            </div>
        </div>
        @if($isPubliclyFlag)
            @if($job->order_pr_title != '')
                <div class="recommend-title">・この求人のおすすめポイント！</div>
            @endif
            <div class="recommend-comment">{!! nl2br(e($job->order_pr_title)) !!}</div>
        @endif
        <br>
        <ul class="feed-job-detail-info">
            @if($job->salary)
            <li>【給与】 {!! nl2br(e($job->salary)) !!}</li>
            @endif
            @if($job->job_type_name)
            <li>【募集職種】 {{$job->job_type_name}}</li>
            @endif
            @if($job->employment_type)
            <li>【雇用形態】 {{$job->employment_type}}</li>
            @endif
            @if($job->business)
            <li>【施設】 {{$job->business}}</li>
            @endif
            @if(!empty($job->station))
            <li>【最寄駅】 {{$job->station}}</li>
            @endif
        </ul>
        <div class="fx-row fx-row-center-xs" style="text-align: center;">
            <div class="feed-btn1">
                <a href="{{$entry_path}}" class="c-button">応募エントリーする</a>
            </div>
        </div>
    </article>
</main>
