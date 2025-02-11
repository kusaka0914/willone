@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')

    @include('pc.mainparts.breadcrump')

    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">
                @include('pc.staff._detailStaffContent')

                @include('pc.staff._detailExampleList')

                <div class="c-button-wrap-color"><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.other')])}}?action=spor-staffdetail-btn_mid" class="c-button-big">
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

                <div class="c-button-wrap-color"><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.other')])}}?action=spor-staffdetail-btn_bottom" class="c-button-big">
                    <span>キャリアカウンセリング・応募書類の添削・面接対応まで</span><br>
                    エージェントに相談する
                </a></div>

            </div>

            @include('pc.mainparts.staffdetailsidebar')

        </div>
    </main>

    @include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
</body>
@endsection
