@extends('sp.mainparts.head')

@section('content')
<body>
    @include('sp.mainparts.bodyheader')


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

            @include('sp.mainparts.experiencelistsidebar')


        </div>
    </main>

    @include('sp.mainparts.bodyfooter')

@include('sp.mainparts.topscript')
</body>
@endsection
