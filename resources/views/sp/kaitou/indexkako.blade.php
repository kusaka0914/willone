@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')

    <h1 class="p-answer-head">過去の解答速報</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">
                {{-- JINZAISYSTEM-9797 【WOA】国家試験解答速報ページ_黒本サイトバナー設置 --}}
                <div class="kurohon_bnr">
                    <a href="https://kurohon.jp/recruit/" target="_blank"><img src="/woa/image_file/kaitousokuho/{{config('const.kurohon_bnr')}}"></a>
                </div>
				<table class="c-table-info">
					@foreach( $kaitou_data as $kaitou_value)

					<tr>
						<th class="u-w20">{{date('Y年m月d日' ,strtotime($kaitou_value->shiken_date))}}</th>
						<td>
                            {{-- TODO:JINZAI-2327の対応でひとまずの実装
                                そのため要件をつめて再設計＆再実装する必要あり --}}
                            @if(strpos($kaitou_value->kaitouurl, 'https://') !== false)
                                <a href="{{$kaitou_value->kaitouurl}}">{{ $kaitou_value->title }}</a>
                            @else
                                <a href="{{ route('KaitouDetail' , ['id' => $kaitou_value->kaitouurl ]) }}">{{ $kaitou_value->title }}</a>
                            @endif
                        </td>
					</tr>
					@endforeach
				</table>

            </div>

            @include('sp.mainparts.kaitousidebar')

        </div>
    </main>

    @include('sp.mainparts.bodyfooter')

@include('sp.mainparts.topscript')
</body>
@endsection
