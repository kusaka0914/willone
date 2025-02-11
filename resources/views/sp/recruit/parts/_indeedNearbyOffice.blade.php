@if (!empty($near_job))
<!--近隣求人情報-->
<aside class="site_aside">
    <div class="nearOfficeWrap">
    <h2 class="nearHeading">あなたにおすすめの求人</h2>
    @foreach ($near_job as $nearOffice)
        <article class="nearOffice">
            <a href="/glp/{{$feed_id}}_01?job_id={{$nearOffice->job_id}}&amp;action={{$action}}&amp;utm_source=feed&amp;utm_medium=cpc&amp;utm_campaign={{$feed_id}}" class="nearOfficeLink">
                <div class="indeed-LP_copy1">
            @if($nearOffice->publicly_flag == '0')
                <h3 class="feed-detail-name">{{$nearOffice->addr2_name}}の{{$nearOffice->business}}  <span style="color: #f9c608;">非公開求人</span></h3>
            @else
                <h3 class="feed-detail-name">{{$nearOffice->office_name}}</h3>
            @endif
                </div>
                <p class="feed-job-detail-info c-text-link-wrap"><span>{{ date('Y年m月d日',strtotime($nearOffice->last_confirmed_datetime)) }} 更新</span></p>
                <div class="indeed_btnArea">
                    <ul>
                    <div class="indeed-LP_copy2 feed-job-detail-info">
                        {{-- 求人情報 --}}
                        @if($nearOffice->salary)
                        <li>【給与】 {!! nl2br(e($nearOffice->salary)) !!}</li>
                        @endif
                        @if($nearOffice->job_type_name)
                        <li>【募集職種】 {{$nearOffice->job_type_name}}</li>
                        @endif
                        @if($nearOffice->employment_type)
                        <li>【雇用形態】 {{$nearOffice->employment_type}}</li>
                        @endif
                        @if($nearOffice->business)
                        <li>【施設】 {{$nearOffice->business}}</li>
                        @endif
                        @if(!empty($nearOffice->station))
                        <li>【最寄駅】 {{$nearOffice->station}}</li>
                        @endif
                        @if (!empty($nearOffice->detail))
                        <li>【仕事内容】 {{$nearOffice->detail}}</li>
                        @endif
                        @if (!empty($nearOffice->dayoff))
                        <li>【休日休暇】 {{$nearOffice->dayoff}}</li>
                        @endif
                    </div>
                    </ul>
                </div>
                <div class="indeed-LP_btn2 feed-btn1">
                    <span class="btnInner c-button-recommend">詳細はコチラから</span>
                </div>
            </a>
        </article><!-- /otherOffice -->
    @endforeach
    </div><!-- /.otherOfficeWrap -->
</aside><!-- /targetOffice -->
@endif