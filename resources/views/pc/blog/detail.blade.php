@extends('pc.mainparts.head')

@section('content')
<body>
    @include('pc.mainparts.bodyhead')

    <ol class="l-breadcrumb">
            <li class="l-breadcrumb-item"><a href="/woa">{{ $breadcrump[0] }}</a></li>
            <li class="l-breadcrumb-item"><a href="{{ route('Knowhow') }}">{{ $breadcrump[1] }}</a></li>
            <li class="l-breadcrumb-item"><a href="{{ $category_url }}">{{ $breadcrump[2] }}</a></li>
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

                <div class="c-leadForm c-leadForm-blog">
                    <div class="c-leadForm-eyeCatch">
                        <p>まずは<br/><span>無料登録</span>から</p>
                        <img src="/woa/img/branch_a.png" alt="まずは無料登録" class="">
                    </div>
                    <div class="c-leadForm-form">
                        <select name="" id="leadFormItem1">
                            <option value="">希望職種を選択</option>
                            @foreach($licenseList as $value)
                            <option value="{{$value->id}}">{{$value->license}}</option>
                            @endforeach
                        </select>
                        <select name="" id="leadFormItem2">
                            <option value="">雇用形態を選択</option>
                            @foreach($reqEmpTypeList as $value)
                            <option value="{{$value->id}}">{{$value->emp_type}}</option>
                            @endforeach
                        </select>
                        <select name="" id="leadFormItem3">
                            <option value="">転職時期を選択</option>
                            @foreach($reqDataList as $value)
                            <option value="{{$value->id}}">{{$value->req_date}}</option>
                            @endforeach
                        </select>
                        <div class="c-leadForm-form-button">
                            <input type="button" value="無料登録スタート" class="c-form-base-button entry_start" device='PC'>
                        </div>
                    </div>
                </div>

@if (isset($links) && $links)
				<div class="c-pagenation-simple-wrap">
					<ul class="c-pagenation-simple">
    @if (isset($links['prev']) && $links['prev'])
						<li><a href="{{$links['prev']}}"><i class="fa fa-caret-left" aria-hidden="true"></i>PREV</a></li>
    @endif
    @if (isset($links['parent']) && $links['parent'])
						<li><a href="{{$links['parent']}}">一覧に戻る</a></li>
    @endif
    @if (isset($links['next']) && $links['next'])
						<li><a href="{{$links['next']}}">NEXT<i class="fa fa-caret-right" aria-hidden="true"></i></a></li>
    @endif
					</ul>
				</div>
@endif

            </div>

            @include('pc.mainparts.blogsidebar')


        </div>
    </main>

    @include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
<script src="{{addQuery('/woa/js/entry_link.js')}}"></script>
</body>
@endsection
