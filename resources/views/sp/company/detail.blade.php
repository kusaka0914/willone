@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')

    <main class="l-contents">
        <div class="l-contents-container">
            <div class="p-company-head">
                <h1 class="p-company-name">{{ $company->company_name }}</h1>
                @if( !empty($company->description) )
                <p>{{ $company->description}}</p>
                @endif
            </div>

            <div class="c-button-wrap">
                <a href="#shisetsu" class="c-button">各施設の採用情報はこちら</a>
            </div>

            @if( !empty($company->tantou_description))
            <div class="p-company-message">
                <div class="p-company-message-img" style="background-image:url(/woa/images/experience_img_sample2.png)"></div>
                <div class="p-company-message-text">
                    <h2 class="p-company-message-title">採用担当メッセージ</h2>
                    <h3 class="p-company-message-subtitle">{{ $company->tantou_title }}</h3>
                    <p>{{ $company_tantou_message1 }}</p>
                    <h3 class="p-company-message-subtitle">{{ $company->tantou_title2 }}</h3>
                    <p>{{ $company_tantou_message2 }}</p>
                    <h3 class="p-company-message-subtitle">{{ $company->tantou_title3 }}</h3>
                    <p>{{ $company_tantou_message3 }}</p>
                    <div class="p-company-message-staff">
                        <figure class="p-company-message-staff-img"><img src="/woa/images/experience_img_sample2.png" alt=""></figure>
                        <div class="p-company-message-staff-detail">
                            <p class="p-company-message-staff-name">{{ $company->tantou_katagaki }}　{{ $company->tantou_name }}</p>
                            <p>{{ $company->tantou_description }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if( !empty($company->point_title1) or !empty($company->point_title2) or  !empty($company->point_title3) )
            <h2 class="c-title-l-bar">MCP株式会社のおすすめポイント</h2>
            <ul class="p-company-point">
                <li class="p-company-point-item">
                    <h3 class="p-company-point-title">{{ $company->point_title1 }}</h3>
                    <p>{{ $company->point_description1 }}</p>
                </li>
                <li class="p-company-point-item">
                    <h3 class="p-company-point-title">{{ $company->point_title2 }}</h3>
                    <p>{{ $company->point_description2 }}</p>
                </li>
                <li class="p-company-point-item">
                    <h3 class="p-company-point-title">{{ $company->point_title3 }}</h3>
                    <p>{{ $company->point_description3 }}</p>
                </li>
            </ul>
            @endif

            @if( !empty($company->senpai_title1) or !empty($company->senpai_title2) or  !empty($company->senpai_title3) )
            <h2 class="c-title-l-bar">先輩メッセージ</h2>
            <ul class="p-company-point">
                <li class="p-company-point-item">
                    <div class="p-company-point-img" style="background-image:url(/woa/images/experience_img_sample2.png)"></div>
                    <p class="p-company-point-caption">{{ $company->senpai_caption1 }}</p>
                    <h3 class="p-company-point-title">{{ $company->senpai_title1 }}</h3>
                    <p>{{ $company->senpai_message1 }}</p>
                </li>
                <li class="p-company-point-item">
                    <div class="p-company-point-img" style="background-image:url(/woa/images/experience_img_sample2.png)"></div>
                    <p class="p-company-point-caption">{{ $company->senpai_caption2 }}</p>
                    <h3 class="p-company-point-title">{{ $company->senpai_title2 }}</h3>
                    <p>{{ $company->senpai_message2 }}</p>
                </li>
                <li class="p-company-point-item">
                    <div class="p-company-point-img" style="background-image:url(/woa/images/experience_img_sample2.png)"></div>
                    <p class="p-company-point-caption">{{ $company->senpai_caption3 }}</p>
                    <h3 class="p-company-point-title">{{ $company->senpai_title3 }}</h3>
                    <p>{{ $company->senpai_message3 }}</p>
                </li>
            </ul>
            @endif

            @include('sp.mainparts.companyjob')
        </div>
    </main>

    @include('sp.mainparts.bodyfooter')

@include('sp.mainparts.companyscript')
</body>
@endsection