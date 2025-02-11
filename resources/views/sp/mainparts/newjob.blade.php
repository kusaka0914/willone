@if (!empty($new_job))
<h2 class="c-title-l-bar">新着求人</h2>
                <ul class="c-job">
                    @foreach( $new_job as $new_data)
                    <li class="c-job-item">
                        <h3 class="c-job-title matchHeight"><a href="{{ route('OpportunityDetail' , ['id' => $new_data['job_id'] ]) }}" class="c-job-link">{{ $new_data['office_name'] }}の求人情報</a><span class="c-job-catch">{{ $new_data['order_pr_title'] }}</span></h3>
                        <dl class="c-job-detail">
                            <dt>勤務地</dt>
                            <dd>{{ $new_data['addr'] }}</dd>
                            <dt>募集職種</dt>
                            <dd>{{ $new_data['job_type_name'] }}</dd>
                            <dt>最寄駅</dt>
                            <dd>{{ $new_data['station1'] }}</dd>
                            <dt>給与</dt>
                            <dd>{{ $new_data['salary'] }}</dd>
                            <dt>休日・休暇</dt>
                            <dd>{{ $new_data['dayoff'] }}</dd>
                        </dl>
                    </li>
                    @endforeach
                </ul>
@endif