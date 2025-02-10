@extends('sp.mainparts.head')

@section('content')
<body>
    @include('sp.mainparts.bodyheader')
    <h1 class="p-know-how-head">就職活動ノウハウ</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

                <div class="bluebox u-m15">
                    <h2>キャリアパートナーに相談する</h2>
                    <h3 class="u-mt20">お気持ちはどちらに近いですか？</h3>
                    <ul class="bluebox-flex u-m10 u-mb20">
                        <li><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.other')]) }}?action=spor-knowhow-motiv-1&branch=A" class="bluebox-btn"><img src="/woa/img/branch_a.png" alt="近いうちに転職したい" class="u-mb10">近いうちに転職したい</a></li>
                        <li><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.other')]) }}?action=spor-knowhow-motiv-0&branch=B" class="bluebox-btn"><img src="/woa/img/branch_b.png" alt="今は情報収集したい" class="u-mb10">今は情報収集したい</a></li>
                    </ul>
                </div>

                <div class="c-leadForm">
                    <div class="c-leadForm-eyeCatch">
                        <p>まずは<br/><span>無料登録</span>から</p>
                        <img src="/woa/img/branch_a.png" alt="まずは無料登録" class="">
                    </div>
                    <div class="c-leadForm-form">
                        <select id="leadFormItem1">
                            <option value="">希望職種を選択</option>
                            @foreach($licenseList as $value)
                            <option value="{{$value->id}}">{{$value->license}}</option>
                            @endforeach
                        </select>
                        <select id="leadFormItem2">
                            <option value="">雇用形態を選択</option>
                            @foreach($reqEmpTypeList as $value)
                            <option value="{{$value->id}}">{{$value->emp_type}}</option>
                            @endforeach
                        </select>
                        <select id="leadFormItem3">
                            <option value="">転職時期を選択</option>
                            @foreach($reqDataList as $value)
                            <option value="{{$value->id}}">{{$value->req_date}}</option>
                            @endforeach
                        </select>
                        <div class="c-leadForm-form-button">
                            <input type="button" value="無料登録スタート" class="c-form-base-button entry_start" device='SP'>
                        </div>
                    </div>
                </div>

                <ul class="c-know-how">

                    <li class="c-know-how-item"><a href="{{ route('KnowhowList' , ['knowhow' => $list['1']->value3])}}" class="c-know-how-link">
                        <div class="c-know-how-title">就職・転職活動の<br>始め方</div>
                        <div class="c-know-how-detail">
                            <p class="c-know-how-text matchHeight">{{ $list['1']->value2}}
							</p>
                            <div class="c-text-link-wrap"><span class="c-text-link">詳細はこちら</span></div>
                        </div>
                    </a></li>
                    <!--
                    <li class="c-know-how-item"><a href="{{ route('KnowhowList' , ['knowhow' => $list['2']->value3])}}" class="c-know-how-link">
                        <div class="c-know-how-title">情報収集の仕方・<br>応募の仕方</div>
                        <div class="c-know-how-detail">
                            <p class="c-know-how-text matchHeight">{{ $list['2']->value2}}</p>
                            <div class="c-text-link-wrap"><span class="c-text-link">詳細はこちら</span></div>
                        </div>
                    </a></li>
                    -->
                    <li class="c-know-how-item"><a href="{{ route('KnowhowList' , ['knowhow' => $list['3']->value3])}}" class="c-know-how-link">
                        <div class="c-know-how-title">履歴書・<br>職務経歴書ノウハウ</div>
                        <div class="c-know-how-detail">
                            <p class="c-know-how-text matchHeight">
                                {{ $list['3']->value2}}
                            </p>
                            <div class="c-text-link-wrap"><span class="c-text-link">詳細はこちら</span></div>
                        </div>
                    </a></li>
                    <li class="c-know-how-item"><a href="{{ route('KnowhowList' , ['knowhow' => $list['4']->value3])}}" class="c-know-how-link">
                        <div class="c-know-how-title">面接<br>ノウハウ</div>
                        <div class="c-know-how-detail">
                            <p class="c-know-how-text matchHeight">{{ $list['4']->value2 }}</p>
                            <div class="c-text-link-wrap"><span class="c-text-link">詳細はこちら</span></div>
                        </div>
                    </a></li>

                    <li class="c-know-how-item"><a href="{{ route('KnowhowList' , ['knowhow' => $list['6']->value3])}}" class="c-know-how-link">
                        <div class="c-know-how-title">就職・転職活動に<br>まつわるQ＆A</div>
                        <div class="c-know-how-detail">
                            <p class="c-know-how-text matchHeight">{{ $list['6']->value2 }}</p>
                            <div class="c-text-link-wrap"><span class="c-text-link">詳細はこちら</span></div>
                        </div>
                    </a></li>
                    <li class="c-know-how-item"><a href="{{ route('KnowhowList' , ['knowhow' => $list['7']->value3])}}" class="c-know-how-link">
                        <div class="c-know-how-title">退職の<br>流れ</div>
                        <div class="c-know-how-detail">
                            <p class="c-know-how-text matchHeight">{{ $list['7']->value2 }}</p>
                            <div class="c-text-link-wrap"><span class="c-text-link">詳細はこちら</span></div>
                        </div>
                    </a></li>
                    <li class="c-know-how-item"><a href="{{ route('KnowhowList' , ['knowhow' => $list['8']->value3])}}" class="c-know-how-link">
                        <div class="c-know-how-title">求人特集<br></div>
                        <div class="c-know-how-detail">
                            <p class="c-know-how-text matchHeight">{{ $list['8']->value2 }}</p>
                            <div class="c-text-link-wrap"><span class="c-text-link">詳細はこちら</span></div>
                        </div>
                    </a></li>
                    <li class="c-know-how-item"><a href="{{ route('KnowhowList' , ['knowhow' => $list['9']->value3])}}" class="c-know-how-link">
                        <div class="c-know-how-title">給与相場<br></div>
                        <div class="c-know-how-detail">
                            <p class="c-know-how-text matchHeight">{{ $list['9']->value2 }}</p>
                            <div class="c-text-link-wrap"><span class="c-text-link">詳細はこちら</span></div>
                        </div>
                    </a></li>
                    <li class="c-know-how-item"><a href="{{ route('KnowhowList' , ['knowhow' => $list['10']->value3])}}" class="c-know-how-link">
                        <div class="c-know-how-title">キャリアアップ・<br>働き方について</div>
                        <div class="c-know-how-detail">
                            <p class="c-know-how-text matchHeight">{{ $list['10']->value2 }}</p>
                            <div class="c-text-link-wrap"><span class="c-text-link">詳細はこちら</span></div>
                        </div>
                    </a></li>
                    <li class="c-know-how-item"><a href="{{ route('KnowhowList' , ['knowhow' => $list['11']->value3])}}" class="c-know-how-link">
                        <div class="c-know-how-title">仕事内容<br>について</div>
                        <div class="c-know-how-detail">
                            <p class="c-know-how-text matchHeight">{{ $list['11']->value2 }}</p>
                            <div class="c-text-link-wrap"><span class="c-text-link">詳細はこちら</span></div>
                        </div>
                    </a></li>
                    <!--
                    <li class="c-know-how-item"><a href="{{ route('KnowhowList' , ['knowhow' => $list['12']->value3])}}" class="c-know-how-link">
                        <div class="c-know-how-title">国家試験<br>について</div>
                        <div class="c-know-how-detail">
                            <p class="c-know-how-text matchHeight">{{ $list['12']->value2 }}</p>
                            <div class="c-text-link-wrap"><span class="c-text-link">詳細はこちら</span></div>
                        </div>
                    </a></li>
                    <li class="c-know-how-item"><a href="{{ route('KnowhowList' , ['knowhow' => $list['13']->value3])}}" class="c-know-how-link">
                        <div class="c-know-how-title">治療家になるには？<br></div>
                        <div class="c-know-how-detail">
                            <p class="c-know-how-text matchHeight">{{ $list['13']->value2 }}</p>
                            <div class="c-text-link-wrap"><span class="c-text-link">詳細はこちら</span></div>
                        </div>
                    </a></li>
                    -->
                </ul>
                <div class="c-know-how-arcive">
                    <a href="{{ route('KnowhowList' , ['knowhow' => $list['14']->value3])}}">
                    過去の記事
                    </a>
                </div>
            </div>

            @include('sp.mainparts.knowhowsidebar')
        </div>
    </main>

    @include('sp.mainparts.bodyfooter')

@include('sp.mainparts.topscript')
<script src="{{addQuery('/woa/js/entry_link.js')}}"></script>
</body>
@endsection
