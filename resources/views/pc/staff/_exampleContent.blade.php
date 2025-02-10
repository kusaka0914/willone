<main class="l-contents u-mt0">
        <div class="l-contents-container">
            <div class="c-staff-top-header">
                <img src="{{ getS3ImageUrl("/images/example/example{$caseNo}.jpg") }}" alt="お客様事例">
                <div class="c-staff-top-box">
                    <div class="c-staff-top-sub-text">
                        <div class="c-staff-top-sub-text-title">お客様事例</div>
                        @foreach([
                            '年代' => 'age',
                            '性別' => 'gender',
                            '資格' => 'license',
                            '学年' => 'grade',
                        ] as $label => $property)
                        @if(!empty($staffExample->{$property}))<p>{{ $label }}：{{ $staffExample->{$property} }}</p>@endif
                        @endforeach
                    </div>
                    <div class="c-staff-top-heading">{{ $staffExample->catchphrase }}</div>
                </div>
            </div>
            <h2 class="c-title-l c-title-l-case">求職者のお悩み{{ !empty($staffExample->research) ? 'と情報収取' : '' }}{{ !empty($staffExample->customer_comment) ? 'と感想' : '' }}</h2>
            <div class="c-saff-case-box">
                @foreach ([
                    'お悩み' => 'worry',
                    '情報収集' => 'research',
                    '感想' => 'customer_comment',
                ] as $title => $property)
                @if(!empty($staffExample->{$property}))
                <div class="c-saff-case-point">
                    <div class="c-staff-case-point-heading">
                        <div class="c-staff-case-point-heading-text">{{ $title }}</div>
                    </div>
                    <div class="c-staff-case-point-text">
                        <p>{{ $staffExample->{$property} }}</p>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
            @if(!empty($staffExample->cp_comment))
            <h2 class="c-title-l c-title-l-case">エージェントのメッセージ</h2>
            <div class="c-saff-case-box">
                <div class="c-saff-case-voice">
                    <div class="c-saff-case-voice-item">
                        <div class="c-staff-case-voice-text">
                            <p>{{ $staffExample->cp_comment }}</p>
                        </div>
                    </div>
                    <img src="/woa/img/case-arrow.svg" width="30px" class="c-saff-case-voice-arrow">
                </div>
            </div>
            @endif
        </div>
    </main>
