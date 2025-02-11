@include('pc.recruit._header', ['job' => $job, 'isPubliclyFlag' => $isPubliclyFlag])
<article class="target-office">
            <br>
            <div id="inner-box" style="box-shadow:0px 0px;margin:15px auto 0;width:800px;">
                <div class="form-box theme-bg-white">
                    <div class="form-box-inner">
                    @if(!$isPubliclyFlag)
                        <h1 class="feed-detail-name">{{$job->addr2_name}}の{{$job->business}}  <span style="color: #f9c608;">非公開求人</span></h5>
                    @else
                        <h1 class="feed-detail-name">{{$job->office_name}}</h5>
                    @endif
                    </div>
                </div>
                @if($isPubliclyFlag)
                    @if($job->order_pr_title != '')
                        <div class="recommend-title">・この求人のおすすめポイント！</div>
                    @endif
                    <div class="recommend-comment">{!! nl2br(e($job->order_pr_title)) !!}</div>
                @endif
                <div style="padding:1em 0;">
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
                    @if($isPubliclyFlag)
                        @if($job->company_name)
                    <li>【法人名】 {{$job->company_name}}</li>
                        @endif
                    @endif
                    @if($job->addr || $job->addr1_name)
                        @if($isPubliclyFlag)
                            <li>【勤務地】 {{$job->postal_code}} {{$job->addr}}</li>
                        @else
                            <li>【勤務地】 {{$job->addr1_name}}{{$job->addr2_name}}</li>
                        @endif
                    @endif
                    @if(!empty($job->station))
                    <li>【最寄駅】 {{$job->station}}</li>
                    @endif
                </ul>
                </div>
                <div class="fx-row fx-row-center-xs" style="text-align: center;">
                    <div class="feed-btn1">
                        <a href="{{$entry_path}}" class="c-button">詳細はコチラから</a>
                    </div>
                    <span>※応募ではありませんので、お気軽にお問合せください</span>
                </div>
            </div>
</article>

@include('pc.recruit.parts._indeedNearbyOffice')

<article class="more-office">
<div id="inner-box" style="box-shadow:0px 0px;margin:15px auto 0;width:800px;">
    <div class="fx-row fx-row-center-xs" style="text-align: center;">
        <div class="feed-btn2" style="position: relative;">
            <span class="feed-update-label">01月01日更新</span>
            <a href="{{$entry_path}}" rel="nofollow" class="c-button">もっと見る</a>
        </div>
    </div>
    <div><p style="text-align: center;">※閲覧時点で、既に応募を終了している場合があります。<br>お問合せ後、最新の求人情報を確認いたします。</p></div>
    <br>
</div>
</article>


@include('pc.mainparts.topscript')

<script>
$(function() {
    // 日付の自動更新
    var date = new Date();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var hour = date.getHours();

    var string = '';
    if (hour >= 8) {
        string = month + '月' + day + '日' + '更新';
    } else {
        day = date.getDate() - 1;
        if (day < 10) {
            day = '0' + day;
        }
        string = month + '月' + day + '日' + '更新';
    }

    $('.feed-update-label').text(string);
});
</script>