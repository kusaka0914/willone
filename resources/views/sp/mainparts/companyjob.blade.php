<h2 class="c-title-l-bar">各施設の採用情報</h2>

            <ul class="c-job-big">
                @foreach($company_job as $job)
                <li class="c-job-item">
                    <h3 class="c-job-title matchHeight"><a href="{{ route('OpportunityDetail' , ['id' => $job->job_id ]) }}" class="c-job-link">{{ $job->office_name }}</a></h3>
                    <div class="c-job-detail-wrap">
                        <figure class="c-job-img"><img src="{{ addQuery($job->job_image) }}" alt=""></figure>
                        <dl class="c-job-detail">
                            <dt>勤務地</dt>
                            <dd>{{ mb_strimwidth($job->addr, 0 , 40 , '...')}}</dd>
                            <dt>募集職種</dt>
                            <dd>{{ mb_strimwidth($job->job_type_name, 0 , 40 , '...') }}</dd>
                            <dt>最寄駅</dt>
                            <dd>{{ mb_strimwidth($job->station1, 0 , 40 , '...') }}</dd>
                            <dt>給与</dt>
                            <dd>{{ mb_strimwidth($job->salary, 0 , 40 , '...') }}</dd>
                            <dt>休日・休暇</dt>
                            <dd>{{ mb_strimwidth($job->dayoff , 0 , 40 , '...') }}</dd>
                        </dl>
                    </div>
                </li>
                @endforeach
            </ul>
