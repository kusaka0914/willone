<h2 id="shisetsu" class="c-title-l-bar">各施設の採用情報</h2>

            <ul class="c-job">
                @foreach($company_job as $job)
                <li class="c-job-item">
                    <h3 class="c-job-title"><a href="{{ route('OpportunityDetail' , ['id' => $job->job_id ]) }}" class="c-job-link">{{ $job->office_name }}</a></h3>
                    <div class="c-job-detail-wrap">
                        <figure class="c-job-img"><img src="{{ addQuery($job->job_image) }}" alt=""></figure>
                        <dl class="c-job-detail">
                            <dt>勤務地</dt>
                            <dd>{{ $job->addr}}</dd>
                            <dt>募集職種</dt>
                            <dd>{{ $job->job_type_name }}</dd>
                            <dt>最寄駅</dt>
                            <dd>{{ $job->station1 }}</dd>
                            <dt>給与</dt>
                            <dd>{{ $job->salary }}</dd>
                            <dt>休日・休暇</dt>
                            <dd>{{ $job->dayoff }}</dd>
                        </dl>
                    </div>
                </li>
                @endforeach
            </ul>
