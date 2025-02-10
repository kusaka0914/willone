@extends('pc.mainparts.head')

@section('content')

<body>
    @include('pc.mainparts.bodyhead')
    @include('pc.mainparts.breadcrump')
    <h1 class="p-answer-head">{{ $kaitou->title}}</h1>
    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">
                <a class="kaito_sokuho_bnr" href="https://job-note.jp/kokushi/jyusei" target="_blank"><img src="/woa/image_file/kaitousokuho/sokuho_kokai.png" alt="柔道整復師国家試験【解答速報】はこちら"></img></a>
            	<table class="c-table">
            	@if( !empty($kaitou->kaitou_image1))
            	<tr>
            		<td align="center">
                        <img src="{{getS3ImageUrl(config('const.kaitou_image_path_s3') . '/' . $kaitou->kaitou_image1)}}" alt="">
            		</td>
            	</tr>
            	@endif
                <tr>
                    <td align="center">
                        {{-- JINZAISYSTEM-9797 【WOA】国家試験解答速報ページ_黒本サイトバナー設置 --}}
                        <div class="kurohon_bnr">
                            <a href="https://kurohon.jp/recruit/" target="_blank"><img src="/woa/image_file/kaitousokuho/{{config('const.kurohon_bnr')}}"></a>
                        </div>
                    </td>
                </tr>
            	@if( !empty($kaitou->kaitou_image2))
            	<tr>
            		<td align="center">
            			<img src="{{getS3ImageUrl(config('const.kaitou_image_path_s3') . '/' . $kaitou->kaitou_image2)}}" alt="">
            		</td>
            	</tr>
            	@endif
            	@if( !empty($kaitou->kaitou_image3))
            	<tr>
            		<td align="center">
            			<img src="{{getS3ImageUrl(config('const.kaitou_image_path_s3') . '/' . $kaitou->kaitou_image3)}}" alt="">
            		</td>
            	</tr>
            	@endif
            	<tr>
            		<td align="center">
            			<font color="red">訂正箇所は赤字で表示します。</font>
            		</td>
            	</tr>
            	<tr>
            		<td align="center">
            			<p>※速報ですので、参考解答であることをご了承ください。</p>
                        {{-- JINZAISYSTEM-9836 【WOA】国家試験回答速報ページ_2019年の掲載準備2(第27回 柔道整復師国家試験決め打ち) --}}
                        @if($kaitou->kaitouurl == "judoseifukushi27")
                        <p>解答提供：<a href="https://www.kyobun.ac.jp/kyobunjuku/" target="_blank">杏文塾</a></p>
                        @endif
            		</td>
                </tr>
                </table>

                {{-- JINZAISYSTEM-9836 【WOA】国家試験回答速報ページ_2019年の掲載準備2(第27回 柔道整復師国家試験決め打ち) --}}
                @if($kaitou->kaitouurl == "judoseifukushi27")
                <p>【柔道整復師 国家資格試験について】</p>
                <ul><li>・試験日</li></ul>
                <p>2019年(平成31年)3月3日　日曜日</p>
                <br>

                <ul><li>・試験会場</li></ul>
                <p>北海道、宮城県、東京都、石川県、愛知県、大阪府、広島県、香川県、福岡県、沖縄県</p>
                <p>※柔道整復師国家試験の名古屋会場において試験会場の号館が変更されています。</p>
                <p>※第27回柔道整復師国家試験の東京会場受験者の方の注意点</p>
                <p>例年通り、柔道整復師国家試験開催日と、東京マラソンの開催日が同一です。</p>
                <p>公共交通機関など交通状況の混雑が影響されるため、国家資格試験の集合時間に遅れることのないよう前持った行動をおすすめいたします。</p>
                <br>

                <ul><li>・試験科目</li></ul>
                <p>解剖学、生理学、運動学、病理学概論、衛生学・公衆衛生学、一般臨床医学、外科学概論、整形外科学、リハビリテーション医学、柔道整復理論、関係法規</p>
                <br>

                <ul><li>・合格発表日時</li></ul>
                <p>2019年(平成31年)3月26日　火曜日　午後2時</p>
                <br>

                <p>【柔道整復師 解答速報について】</p>
                <p>ウィルワンは柔道整復師の方の就職サポートをしており、治療業界のの就職を行う柔道整復師の学生の方へ様々な情報を提供しています。本ページは、2019年3月3日(日)に実施される第27回柔道整復師国家試験の解答速報を無料で公開しております。</p>
                <p>午前の解答速報を先に公開し(15:00頃を予定)、午後の解答速報も当日中(18：00頃を予定)に公開いたします。</p>
                @endif

            </div>

            @include('pc.mainparts.kaitousidebar')


        </div>
    </main>

    @include('pc.mainparts.bodyfooter')

@include('pc.mainparts.topscript')
</body>
@endsection
