@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')

    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">
                <div class="p-staff-head">
                    <div class="p-staff-head-detail">
                        <p class="p-staff-head-job">エージェント</p>
                        <p class="p-staff-head-comment">{{$detail->catchcopy}}</p>
                        <p class="p-staff-head-name">{{ $detail->name }}</p>
                        <div class="u-clearfix">
                        <table class="p-staff-head-info">
                            <tr>
                                <th>出身地</th>
                                <td>{{ $detail->from_place }}</td>
                            </tr>
                            <tr>
                                <th>座右の銘</th>
                                <td>{{ $detail->zayuu }}</td>
                            </tr>
                            <tr>
                                <th>尊敬する人</th>
                                <td>{{ $detail->sonkei }}</td>
                            </tr>
                        </table>
                        <div class="p-staff-head-img" style="background-image:url({{getS3ImageUrl($detail->staff_image_path)}})"></div>
                    </div>
                    </div>
                </div>

                <p class="c-text-box">{{$detail->caption}}</p>

                @if (!empty($detail->question1))
                <dl class="p-staff-textbox">
                    <dt class="p-staff-textbox-title">キャリアカウンセリングで心がけていること</dt>
                    <dd class="p-staff-textbox-text">{{ $detail->question1 }}</dd>
                </dl>
                @endif

                @if (!empty($detail->question2))
                <dl class="p-staff-textbox">
                    <dt class="p-staff-textbox-title">仕事で一番嬉しかった事</dt>
                    <dd class="p-staff-textbox-text">{{ $detail->question2 }}</dd>
                </dl>
                @endif

                @if (!empty($detail->question3))
                <dl class="p-staff-textbox">
                    <dt class="p-staff-textbox-title">就職・転職を考えている方へメッセージ</dt>
                    <dd class="p-staff-textbox-text">{{ $detail->question3 }}</dd>
                </dl>
                @endif

                @include('sp.staff._detailExampleList')

                <div class="c-button-wrap-color"><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.sp.other')])}}?action=spor-staffdetail-midbtn" class="c-button-big">
                    <span>キャリアカウンセリング・応募書類の添削・面接対応まで</span><br>
                    エージェントに相談する
                </a></div>

                <h2 class="c-title-l">エージェント</h2>

                <dl class="p-staff-textbox-blue">
                    <dt class="p-staff-textbox-title">あなたのキャリアを一緒に考える、転職活動のプロフェッショナル</dt>
                    <dd class="p-staff-textbox-text">過去1000人以上のカウンセリング実績を持つスタッフがあなたの希望条件をヒアリング。<br>ご希望に沿ったお仕事先を探し出します。</dd>
                </dl>

                @if (count($agent) > 0)
                <ul class="c-staff">
                    @foreach($agent as $c_value)
                    <li class="c-staff-item"><a href="{{ route('StaffDetail', ['id'=> $c_value->id])}}" class="c-staff-link">
                        <div class="c-staff-img" style="background-image:url({{getS3ImageUrl($c_value->staff_image_path)}})"></div>
                        <div class="c-staff-name">{{ $c_value->name}}</div>
                    </a></li>
                    @endforeach
                </ul>
                @endif

            </div>

            @include('sp.mainparts.staffdetailsidebar')

        </div>
    </main>

    @include('sp.mainparts.bodyfooter')

@include('sp.mainparts.topscript')
</body>
@endsection
