@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')

    <ol class="l-breadcrumb">
            <li class="l-breadcrumb-item"><a href="/woa">{{ $breadcrump[0] }}</a></li>
            <li class="l-breadcrumb-item"><a href="{{ $breadcrumpurl[1] }}">{{ $breadcrump[1] }}</a></li>
            <li class="l-breadcrumb-item"><a href="{{ $breadcrumpurl[2] }}">{{ $breadcrump[2] }}</a></li>
            <li class="l-breadcrumb-item"><span>{{ $breadcrump[3] }}</span></li>
    </ol>
    <h1 class="p-seminar-head">就職活動ノウハウ</h1>

    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

				<div class="c-leftborder">
					<div class="date">{{ $blog_data->post_date }}</div>
					<div class="title">{{ $blog_data->title }}</div>
				</div>

				{!! $blog_data->post_data_img_replace !!}
            </div>

            @include('pc.mainparts.experiencelistsidebar')


        </div>
    </main>

    @include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
</body>
@endsection
