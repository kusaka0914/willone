@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')

    @include('pc.mainparts.breadcrump')

    @include('pc.staff._exampleContent')
    <div class="l-contents-container u-mb30 ">
    @include('pc.staff._detailStaffContent', ['detail' => $staff])

    <div class="c-button-wrap-color">
        <a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.other')])}}?action=spor-staffdetail-btn_mid" class="c-button-big">
        <span>キャリアカウンセリング・応募書類の添削・面接対応まで</span><br>
        エージェントに相談する
        </a>
    </div>

    @if($staffExampleList->count() > 1)
    <h2 class="c-title-l c-title-l-case">同一エージェントの他事例</h2>
    @foreach($staffExampleList as $staffExample)
        @if($caseNo == $loop->iteration)
            @continue
        @endif
    <a href="{{ route('StaffExample', ['staffId' => $staff->id, 'caseNo' => $loop->iteration]) }}" class="p-staff-case">

        <div class="p-staff-case-img">
            <img src="{{ getS3ImageUrl("/images/example/example{$loop->iteration}.jpg") }}" alt="サポート事例">
        </div>
        <div class="p-staff-case-inner">
            <div class="p-staff-case-title">case</div>
            <div class="p-staff-case-text">{{$staffExample->catchphrase}}</div>
        </div>
        <div class="p-staff-case-next">
            <img src="/woa/img/staff-right-allow.svg" width="15px" class="p-saff-case-next-arrow">
        </div>

    </a>
    @endforeach
    @endif
    </div>
    @include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
</body>
@endsection
