<!doctype html>
<html class="no-js sp" lang="ja">
<head>
    <meta charset="utf-8">
@if(!$isPubliclyFlag)
    <title>{{$job->addr2_name}}・{{$job->job_type_name}}の{{$job->business}}非公開求人({{$job->inq_number}})【ウィルワンエージェント】</title>
    <meta name="description" content="{{$job->addr1_name}}{{$job->addr2_name}}・{{$job->job_type_name}}非公開求人の情報です。柔道整復師、鍼灸師、マッサージ師の転職活動を無料でサポートいたします。" />
    <meta name="keywords" content="{{$job->job_type_name}},{{$job->addr2_name}},{{$job->addr1_name}},カイゴジョブエージェント" />
@else
    <title>{{$job->office_name}}の{{$job->job_type_name}}求人【ウィルワンエージェント】</title>
    <meta name="description" content="{{$job->office_name}}({{$job->addr2_name}})の{{$job->job_type_name}}求人情報です。柔道整復師、鍼灸師、マッサージ師の転職活動を無料でサポートいたします。" />
    <meta name="keywords" content="{{$job->office_name}},{{$job->job_type_name}},{{$job->addr2_name}},{{$job->addr1_name}},ウィルワンエージェント" />
@endif
    <meta name="robots" content="noindex,nofollow" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
    <link href="/css/style_sp.css?20221125" rel="stylesheet" media="all">
    <link href="/css/styleapply.css?20200331" rel="stylesheet" media="all">
    <link href="/css/recruit/sp/feed.css?20201028" rel="stylesheet" media="all">
    <script src="/woa/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/woa/js/popup_sp.js" charset="utf-8"></script>
    <script type="text/javascript">
        var feedId="{{ $feed_id }}";
    </script>
    <link rel="stylesheet" type="text/css" href="/css/popup_sp.css" />
    <meta name="_token" content="{{ csrf_token() }}">  {{-- トークンチェック設定 --}}
    <link rel="icon" href="/woa/favicon.ico">
</head>
<body class="sp" ontouchstart="">
<header role="banner" class="feedLP_header">
    <div class="row kja-l_head">
        <div class="large-24 columns">
            <div class="row align-middle text-center">
                <div class="small-24 columns">
                    <img src="/woa/images/logo.png" alt="ウィルワンエージェント" width="80">
                </div>
                <!-- / .columns -->
            </div>
            <!-- / .row -->
        </div>
        <!-- / .columns -->
    </div>
    <!-- / .row -->
</header>

<main role="main">