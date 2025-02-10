<script type="application/ld+json">
{
    "@context" : "http://schema.org/",
    "@type": "JobPosting",
    "title" : "{{ $jobposting->jobList }}",{{-- 募集職種 --}}
    "description" : "<p>{{ $jobposting->description }}
    </p>",{{-- 説明文 --}}
@if (!empty($jobposting->responsibilities))
    "responsibilities": "{{ $jobposting->responsibilities }}",{{-- 仕事内容 --}}
@endif
    "qualifications": "{{ $jobposting->jobList }}",{{-- 募集職種 --}}
    "identifier": { {{-- 識別子 --}}
    "@type": "PropertyValue",
@if (!empty($jobposting->identifier_name))
        "name": "{{ $jobposting->identifier_name }}",
@endif
        "value": "{{ $jobposting->identifier_id }}"
    },
    "industry": "代替医療",
    "employmentType" : "FULL_TIME",{{-- 雇用形態 --}}
    "mainEntityOfPage": "{{ URL::current() }}",{{-- 求人ページURL --}}
@if (!empty($jobposting->working_time))
    "workHours": "{{ $jobposting->working_time }}",{{-- 勤務時間 --}}
@endif
    "datePosted" : "{{ $jobposting->date_posted }}",{{-- 求人情報が公開された日 --}}
    "jobLocation" : { {{-- 働く場所 --}}
        "@type" : "Place",
        "address" : {
            "@type" : "PostalAddress",
@if (!empty($jobposting->job_location_street_address))
            "streetAddress" : "{{ $jobposting->job_location_street_address }}",
@endif
            "addressLocality" : "{{ $jobposting->job_location_address_locality }}",
            "addressRegion" : "{{ $jobposting->job_location_address_region }}",
            "addressCountry": "JP"
        }
    },
    "hiringOrganization" : { {{-- 企業情報 --}}
        "@type" : "Organization",
        "name" : "{{ $jobposting->hiring_organization_name }}",
        "logo" : "{{ asset('/woa/images/wo_log.jpg') }}"
    },
    "baseSalary": {
        "@type": "MonetaryAmount",
        "currency": "JPY",
        "value": {
            "@type": "QuantitativeValue",
@if (!empty($jobposting->baseSalaryValue))
            "value": "{{ $jobposting->baseSalaryValue }}",{{-- 給与 --}}
@endif
@if (!empty($jobposting->baseSalaryUnitText))
            "unitText": "{{ $jobposting->baseSalaryUnitText }}"
@endif
        }
    },
    "directApply": "false",{{-- 直接応募できるか --}}
    "salaryCurrency": "JPY",{{-- 給与通貨 --}}
    "url": "{{ URL::current() }}"{{-- 求人ページURL --}}
}
</script>
