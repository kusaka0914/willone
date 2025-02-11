@extends('sp.mainparts.head')

@section('content')

<body>
    @include('sp.mainparts.bodyheader')

    <h1 class="p-answer-head">解答速報</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">
                <!-- <div class="c-img-box"><img class="c-img-responsive" src="/woa/images/answer_image.jpg" alt=""></div> -->
                <p class="u-m15">「あん摩マッサージ指圧師国家試験」「はり師国家試験」「きゅう師国家試験」「柔道整復師国家試験」は下記の日程にて執り行われます。<br>以下リンクより、各試験の解答速報をご確認ください。順次公開予定です。</p>
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
				<p class="c-text-link-wrap"><a href="{{Route('KaitouKako')}}" class="c-text-link">過去の解答へ</a></p>

                {{-- JINZAISYSTEM-9797 【WOA】国家試験解答速報ページ_黒本サイトバナー設置 --}}
                <div class="kurohon_bnr is-sp">
                    <a href="https://fair.kurohon.jp/fair/?k_wo" target="_blank"><img src="/woa/image_file/kaitousokuho/kurohon_bnr_fair_2021spr.png"></a>
                </div>
            </div>
            @include('sp.mainparts.kaitousidebar')
        </div>
    </main>

    @include('sp.mainparts.bodyfooter')
    @include('sp.mainparts.topscript')
</body>
@endsection
