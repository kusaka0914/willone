@extends ('pc.mainparts.head')

@section ('content')

<link rel="stylesheet" href="{{ addQuery('/woa/css/static/osaka2020.css') }}">

<body>
    <header class="osaka-header">
        <h1>大阪府×積極採用求人特集</h1>
        <div class="osaka-header-wrap">
            <div class="osaka-header-inner">
                <a href="{{ route('Top') }}"><img src="/woa/images/logo.png" alt="ウィルワン"></a>
            </div>
        </div>
    </header>
    <main class="osaka-main">
        <div class="osaka-heading">
            <img src="{{ addQuery('/woa/img/osaka2020/osaka_logo.png') }}" alt="大阪府×積極採用求人特集">
        </div>
        <div class="osaka-body">
            <h2>積極採用求人特集</h2>
            <p>本特集は、大阪府と民間人材サービス事業者が協力し、コロナ禍で転職・転職活動を行う府民の皆様へ多くの求人情報をお届け、就職機会の確保を目指す 「OSAKA求職者支援コンソーシアム」の一環としての取り組みです。事業の詳細は大阪府のホームページをご確認ください。<span>※大阪府以外全国の求人のお取り扱いもございます。</span></p>
        </div>

        <div class="osaka-button">
            <a href="{{ $bonesetterJobsUrl }}">大阪府民を積極採用<br>柔道整復師求人一覧はこちら</a>
            <a href="{{ $acupunctureJobsUrl }}">大阪府民を積極採用<br>鍼灸師求人一覧はこちら</a>
            <a href="{{ $masseurJobsUrl }}">大阪府民を積極採用<br>あん摩マッサージ指圧師求人一覧はこちら</a>
        </div>

        <div class="osaka-information">
            <h2>1. 当社運営サイトへ特設ページの設置</h2>
            <p>「大阪府緊急雇用対策事業特設ページ」を『ウィルワン』に設置し、コロナ禍において、より採用に意欲的な企業の求人情報を提供します。</p>
            <h2>2. 当社取り組み</h2>
            <p>求職者に対して求人情報の配信等を行い、スムーズな就職活動が出来るよう支援を行います。</p>
        </div>
    </main>

    @include ('pc.mainparts.bodyfooter')
    @include ('pc.mainparts.topscript')

</body>

@endsection
