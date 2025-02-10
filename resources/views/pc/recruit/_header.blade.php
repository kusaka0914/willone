<!doctype html>
<html class="no-js pc" lang="ja">
<head>
    <meta charset="utf-8">
    @if(!$isPubliclyFlag)
    <title>{{$job->addr2_name}}・{{$job->job_type_name}}の{{$job->business}}非公開求人({{$job->inq_number}})【ウィルワンエージェント】</title>
    <meta name="description" content="{{$job->addr1_name}}{{$job->addr2_name}}・{{$job->job_type_name}}非公開求人の情報です。柔道整復師、鍼灸師、マッサージ師の転職活動を無料でサポートいたします。" />
    <meta name="keywords" content="{{$job->job_type_name}},{{$job->addr2_name}},{{$job->addr1_name}},ウィルワンエージェント" />
    @else
    <title>{{$job->office_name}}の{{$job->job_type_name}}求人【ウィルワンエージェント】</title>
    <meta name="description" content="{{$job->office_name}}({{$job->addr2_name}})の{{$job->job_type_name}}求人情報です。柔道整復師、鍼灸師、マッサージ師の転職活動を無料でサポートいたします。" />
    <meta name="keywords" content="{{$job->office_name}},{{$job->job_type_name}},{{$job->addr2_name}},{{$job->addr1_name}},ウィルワンエージェント" />
    @endif
    <meta name="robots" content="noindex,nofollow">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <link rel="stylesheet" href="/woa/css/recruit/pc/feed.css?20201028" media="screen, print">
    <link href="/woa/css/style.css?20221124" rel="stylesheet" media="all">
    <link href="/woa/css/styleapply.css?20200331" rel="stylesheet" media="all">
    <meta name="_token" content="{{ csrf_token() }}"> {{-- トークンチェック設定 --}}
    <meta http-equiv="Cache-Control" content="max-age=600">
    <script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js?20200120"></script>
    <script type="text/javascript" src="/woa/js/common/form/ga_tag_basic.js"></script>
    <script type="text/javascript" src="/woa/js/common/form/ga_feed.js?20200120"></script>
    <script type="text/javascript" src="{{ addQuery('/woa/js/common/form/ga4_feed.js') }}"></script>
    @include('common._gtag')
    <link rel="icon" href="/woa/favicon.ico">
</head>
<body onLoad="_ga('send','event','feedlp','click','{{$feed_id}}_{{$ab_pattern}}_open', 0, {nonInteraction: 1});">
    <header role="banner">
        <input id="feedAbPattern" type="hidden" value='{{$feed_id}}_{{$ab_pattern}}'>
        <div class="row woa-l_head">
            <div class="large-24 columns">
                <div class="row align-middle">
                    <div class="columns">
                        <h1 class="woa-siteCopy">柔道整復師、鍼灸師、マッサージ師の求人・就職支援ならウィルワン</h1>
                    </div>
                </div>

                <div class="row align-middle">
                    <div class="large-8 columns" style="text-align: center">
                        <img src="/woa/images/logo.png" alt="ウィルワンエージェント" width="130">
                    </div>
                </div>

            </div>
        </div>
    </header>
    <main role="main">
