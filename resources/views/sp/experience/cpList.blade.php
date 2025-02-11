@extends('sp.mainparts.head')

@section('content')
<body>
    @include('sp.mainparts.bodyheader')

    <h1 class="p-experience-head">就職・転職体験談一覧</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">
                <div style="text-align: center;margin: 2rem 0;">
                    <button type="button" class="example-type-btn" data-example-type-btn="all">すべて</button>
                    <button type="button" class="example-type-btn example-type1-btn" data-example-type-btn="1">既卒</button>
                    <button type="button" class="example-type-btn example-type2-btn" data-example-type-btn="2">新卒</button>
                </div>
            @php $caseImgIndex = 1; @endphp
            @foreach($staffList as $staff)
                @php $caseNoIndex = 1; @endphp
                @foreach($examplesData as $exampleData)
                    @if($staff->id === $exampleData->staff_id)
                    <div class="example-data" data-example-type="{{ $exampleData->example_type }}">
                        <a href="{{route('StaffExample', ['staffId' => $staff->id, 'caseNo' => $caseNoIndex])}}" class="p-staff-case">
                            <div class="p-staff-case-img">
                                <span class="example-type-icon example-type-icon{{ $exampleData->example_type }}">{{ config('ini.EXAMPLE_TYPE')[$exampleData->example_type] ?? '' }}</span>
                                <img src="{{ getS3ImageUrl("/images/example/example{$caseImgIndex}.jpg") }}" alt="お客様事例">
                            </div>
                            <div class="p-staff-case-inner">
                                <div class="p-staff-case-title">case</div>
                                <div class="p-staff-case-text">{{$exampleData->catchphrase}}</div>
                            </div>
                            <div class="p-staff-case-next">
                                <img src="/woa/img/staff-right-allow.svg" width="15px" class="p-saff-case-next-arrow">
                            </div>
                        </a>
                    </div>
                    @php
                    $caseNoIndex++;
                    $caseImgIndex++;
                    if($caseImgIndex > $exampleImgMaxCount){
                        $caseImgIndex = 1;
                    }
                    @endphp
                    @endif
                @endforeach
            @endforeach
            </div>
        </div>
            @include('sp.mainparts.experiencelistsidebar')

        </div>
    </main>

    @include('sp.mainparts.bodyfooter')

@include('sp.mainparts.topscript')
<script>
$(document).ready(function() {
    $('.example-type-btn').on('click', function() {
        exampleType = $(this).attr('data-example-type-btn');
        $('[data-example-type]').hide();
        if(exampleType === 'all'){
            $(`[data-example-type]`).show();
        }else{
            $(`[data-example-type="${exampleType}"]`).show();
        }
    });
});
</script>
</body>
@endsection
