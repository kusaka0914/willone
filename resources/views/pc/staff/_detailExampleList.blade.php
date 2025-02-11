@if(($examples ?? collect())->isNotEmpty())
<h2 class="c-title-l">サポート事例</h2>
@foreach($examples as $example)
<a href="{{ route('StaffExample', ['staffId' => $detail->id, 'caseNo' => $loop->iteration]) }}" class="p-staff-case">
    <div class="p-staff-case-img">
        <img src="{{ getS3ImageUrl("/images/example/example{$loop->iteration}.jpg") }}" alt="サポート事例">
    </div>
    <div class="p-staff-case-inner">
        <div class="p-staff-case-title">case</div>
        <div class="p-staff-case-text">{{$example->catchphrase}}</div>
    </div>
    <div class="p-staff-case-next">
        <img src="/woa/img/staff-right-allow.svg" width="15px" class="p-saff-case-next-arrow">
    </div>
</a>
@endforeach
@endif
