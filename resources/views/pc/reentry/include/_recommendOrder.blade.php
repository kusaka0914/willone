@if (!empty($recommendOrderList))
<div class="recommendTopMessage">
    <p>
        ※各求人の更なる詳細についてはご回答頂いたアンケートに応じて、キャリアパートナーよりご連絡させて頂きます。<br>
        掲載している求人以外にもご条件に近い求人をご案内致します。<br>
        お急ぎの場合は 03-6778-5276 までお問い合わせください。<br>
        ※近隣にマッチした求人がない場合はその他エリアからおすすめしております。<br>
    </p>
</div>
<!--近隣求人情報-->
    <div class="recommendBox">
        <div class="recommendOrderWrap">
            <h2 class="recommendHeader">あなたにおすすめの求人</h2>
                @foreach ($recommendOrderList as $recommendOrder)
                <form name="recommendForm" action="{{ $jobetnryUrl }}" method="post">
                @include ('common/_hidden_jobentry')
                <article class="recommendOrder">
                    <div>
                        @if($recommendOrder->publicly_flag == '0')
                        <h3 class="recommendOrderName">{{ $recommendOrder->addr2_name }}の{{ $recommendOrder->business }}  <span style="color: #f9c608;">非公開求人</span></h3>
                        @else
                        <h3 class="recommendOrderName">{{ $recommendOrder->office_name }}</h3>
                        @endif
                    </div>
                    @if($recommendOrder->publicly_flag == '1' && $recommendOrder->order_pr_title != '')
                    <div class="recommendTitle">・この求人のおすすめポイント！</div>
                    <div class="recommendComment">{!! nl2br(e($recommendOrder->order_pr_title)) !!}</div>
                    @endif
                    <div class="recommendDetail">
                        <ul>
                        <div>
                            {{-- 求人情報 --}}
                            @if($recommendOrder->salary)
                            <li>【給与】 {!! nl2br(e($recommendOrder->salary)) !!}</li>
                            @endif
                            @if($recommendOrder->job_type_name)
                            <li>【募集職種】 {{ $recommendOrder->job_type_name }}</li>
                            @endif
                            @if($recommendOrder->employment_type)
                            <li>【雇用形態】 {{ $recommendOrder->employment_type }}</li>
                            @endif
                            @if($recommendOrder->business)
                            <li>【施設】 {{ $recommendOrder->business }}</li>
                            @endif
                            @if(!empty($recommendOrder->station))
                            <li>【最寄駅】 {{ $recommendOrder->station }}</li>
                            @endif
                            @if(!empty($recommendOrder->inq_number))
                            <li>【お問い合わせ番号】 {{ $recommendOrder->inq_number }}</li>
                            @endif
                        </div>
                        </ul>
                    </div>
                    <div style="text-align: center;">
                        <input type="submit" class="recommendEntryBtn" value="応募">
                    </div>
                </article>
                </form>
                @endforeach
        </div>
    </div>
@endif