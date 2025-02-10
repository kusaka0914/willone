@extends('sp.mainparts.head')

@section('content')

<script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
    // エラーメッセージの位置までスクロール
    $(document).ready( function(){
        var target = document.getElementById("errorMsg");
        if (target.style.display != 'none') {
            $("html,body").animate({scrollTop:$("#errorMsg").offset().top -20});
        }
    });

    // 置換処理
    function convertStr(str) {
        var $tel = str.replace(/[Ａ-Ｚａ-ｚ０-９（）]/g, function(s) {
            return String.fromCharCode(s.charCodeAt(0)-0xfee0);
        }) // 全角を半角に置換
        .replace(/[‐－―]/g, '-') // 長音、ダッシュを半角ハイフンに置換
        .replace(/[ 　]/g, ''); // 半角スペースと全角スペースを除去

        return $tel;
    }

    // 電話番号項目のフォーカスアウト時の処理
    $(function() {
        $('input[name="tel"]').on('blur', function() {
            var str = $(this).val();
            $(this).val(convertStr(str));
        });
    });
</script>

<body>
    @include('sp.mainparts.bodyheader')
    <h1 class="p-recruit-head">治療院の院長・経営者さまへ</h1>

    <main class="l-contents">
        <div class="l-contents-container">
            <div class="l-contents-main">

                <div class="u-text-c"><img src="/woa/images/recruit1.jpg" class="c-img-responsive"></div>
				<div class="u-text-c"><img src="/woa/images/recruit2.jpg" class="c-img-responsive"></div>
				<div class="c-button-wrap u-mb40"><a href="#formlink" class="c-button">
                    <i class="fa fa-caret-right"></i> ウィルワンに人材紹介を申し込む
                </a></div>

				<div class="u-text-c"><img src="/woa/images/recruit3.jpg" class="c-img-responsive"></div>

				<div class="p-recruit-main u-mb20">
					<div><img src="/woa/images/recruit_image1.jpg" class="c-img100"></div>
					<p class="u-p15 u-mt0">
						院長・経営者さまの求める人物像をウィルワンのスタッフが丁寧にお聞きします。<br>
						豊富なスタッフ登録数なので、欲しい人材が来る可能性が最も高くなります。<br>
						<img src="/woa/images/recruit4.png"><br>
						<span class="p-recruit-point">院長・経営者さまの求める人物像を把握します</span><br>
						治療院・サロンに特化した採用のスペシャリストが院長・経営者さまにきめ細かにヒアリングします。<br>
						院長・経営者さまの求める人材の本質を理解してご紹介するので採用後のミスマッチが起きにくく、ピンポイントな人材をご紹介することができます。
					</p>
				</div>

				<div class="p-recruit-main u-mb20">
					<div><img src="/woa/images/recruit_image2.jpg" class="c-img100"></div>
					<p class="u-p15 u-mt0">
						ウィルワンが提供する人材紹介サービスは、完全成功報酬型なので、採用が決定するまで費用はかかりません。<br>
						院長・経営者さまにとって導入リスクを低くして、優秀な人材に出会えます。<br>
						<img src="/woa/images/recruit4.png"><br>
						<span class="p-recruit-point">もしもの時も安心な返金保証があります</span><br>
						ウィルワンでは、ミスマッチのないご紹介を前提としていますが、さらなる安心のため返金保証があります。<br>
						採用後に、万が一自己都合により予期せぬ退職や本人の責任で退職する場合は、採用6ヶ月以内であれば返金保証が適応されます。
					</p>
				</div>

				<div class="p-recruit-main u-mb0">
					<div><img src="/woa/images/recruit_image3.jpg" class="c-img100"></div>
					<p class="u-p15 u-mt0">
						面接時には求職者への治療院・サロンのアピールや補足の説明をしたり、面談後も細かなすり合せなどご案内した方のフォローをきめ細やかに行います。<br>
						フォローがしっかりしているので、雇用関係を円滑に進めることができます。<br>
						<img src="/woa/images/recruit4.png"><br>
						<span class="p-recruit-point">エージェントが全面バックアップします</span><br>
						エージェント(貴院を担当させていただく弊社スタッフ)が院長・経営者さまを全面バックアップします。<br>
						採用のお手伝いはもちろん、業界の情報や経営が良好な治療院・サロンの運営法まで経営に役立つ情報も提供します。
					</p>
				</div>
				<div class="p-recruit-detail-arrow">
					<i class="fa fa-caret-down" aria-hidden="true"></i>
					<i class="fa fa-caret-down" aria-hidden="true"></i>
					<i class="fa fa-caret-down" aria-hidden="true"></i>
				</div>
				<div class="c-button-wrap u-mb20 u-mt0"><a href="#formlink" class="c-button">
                    <i class="fa fa-caret-right"></i> ウィルワンに人材紹介を申し込む
                </a></div>

				<h2 class="c-title-l-bar u-mt0" id="contents01">スタッフを雇用するまでの流れ</h2>
				<div class="c-textbox">
                    <div class="c-textbox-title"><i class="fa fa-check-square" aria-hidden="true"></i> 雇用支援サービスお申し込み(求人登録)</div>
                    <div class="c-textbox-detail">
                        <p class="c-textbox-text">
                            <img class="c-textbox-img" src="/woa/images/recruit_flow1.png" alt="">
							お申し込みの翌営業日(通常)までに担当エージェント(貴院を担当させていただく弊社スタッフ)よりご連絡を差し上げます。<br>
							ご不明な点がある場合は、何なりとご相談ください。
                        </p>
                    </div>
                </div>
				<hr class="c-line-arrow">
				<div class="c-textbox">
                    <div class="c-textbox-title"><i class="fa fa-check-square" aria-hidden="true"></i> 担当者による詳細ヒアリング</div>
                    <div class="c-textbox-detail">
                        <p class="c-textbox-text">
                            <img class="c-textbox-img" src="/woa/images/recruit_flow2.png" alt="">
							ウィルワン登録スタッフの雇用にご興味がある場合は、担当エージェントがお伺いし、求人に関する詳細のヒアリングやシステムのご説明を致します。
                        </p>
                    </div>
                </div>
				<hr class="c-line-arrow">
				<div class="c-textbox">
                    <div class="c-textbox-title"><i class="fa fa-check-square" aria-hidden="true"></i> 面接</div>
                    <div class="c-textbox-detail">
                        <p class="c-textbox-text">
                            <img class="c-textbox-img" src="/woa/images/recruit_flow3.png" alt="">
							お聞きしたご要望を基に適切な人材をご紹介致します。<br>
							ご希望に沿わない場合は、何度でも面談をすることが可能です。
                        </p>
                    </div>
                </div>
				<hr class="c-line-arrow">
				<div class="c-textbox u-mb20">
                    <div class="c-textbox-title"><i class="fa fa-check-square" aria-hidden="true"></i> スタッフの就業開始</div>
                    <div class="c-textbox-detail">
                        <p class="c-textbox-text">
                            <img class="c-textbox-img" src="/woa/images/recruit_flow4.png" alt="">
							雇用したい・働いてもらいたいスタッフがいましたら、採用を出していただき、実際の就業が開始されます。<br>
							就業開始までは料金は一切発生致しませんので、安心してご利用できます。
                        </p>
                    </div>
                </div>

				<h2 class="c-title-l-bar">よくある質問</h2>
                <dl class="c-faq">
                    <dt class="c-faq-q">Q. スタッフの雇用までにどのくらい時間がかかりますか</dt>
                    <dd class="c-faq-a">
                        <span class="c-text-point">早ければ一週間以内のご紹介も可能です。</span><br>
                        スタッフの方は常に動いているので、必ずしも1週間でご紹介できるとは限りませんが、非常に多くの方にご登録いただいており、また、毎日新しいスタッフの方がご登録されていますので、スピーディーにご紹介できることも珍しくありません。
                    </dd>
                    <dt class="c-faq-q">Q. スタッフが短期で辞めたらどうなりますか？</dt>
                    <dd class="c-faq-a">
                        <span class="c-text-point">充実の返金制度がありますので、安心してご利用いただけます。</span><br>
                        なるべく長く働ける方をご案内しておりますが、予期せぬ退職や出産による退職など、雇用にはリスクがつきものです。通常の雇用や求人サイト等からの雇用では回避できない雇用リスクをウィルワンの紹介制度では補うことができます。採用後6ヶ月以内であれば、返金保証が適応されるので、安心してご利用いただきます。
                    </dd>
					<dt class="c-faq-q">Q. なかなか良い人が面接に来なくて困っているんですが…</dt>
                    <dd class="c-faq-a">
                        <span class="c-text-point">ご希望に沿った人材をご紹介することが私達の仕事です。お任せください。</span><br>
                        ご自身の院・サロンなどの魅力をご自身（または働いている方）が伝えることには限界があります。ウィルワンでは、それぞれの院・サロンなどの魅力を客観的に最大限お伝え致しますので、良い方をご紹介することができています。
                    </dd>
					<dt class="c-faq-q">Q. どんな資格を持った人を雇用すればいいか迷っているんですが？</dt>
                    <dd class="c-faq-a">
                        <span class="c-text-point">ぜひご相談ください。ウィルワンの最新情報を基に適切なご提案を致します。</span><br>
                        時代の急激な変化、需給バランスの逆転、過当競争…このような社会情勢で、どのような人を雇えばいいのか迷われている方も多くいらっしゃいます。ウィルワンでは、各院・サロンなどの方向性と時勢とを考え、適切なご提案を行います。
                    </dd>
                </dl>

				<div id="formlink" class="c-text-attention">
                    <form method="post" action="{{ route('RecruitComp') }}">
                    {{ csrf_field() }}
                <h2 class="c-title-l-pink u-mb0">
                    ウィルワンに人材紹介を申し込む
                </h2>
                <div class="c-form-base">
                    <div class="c-form-base-wrap">
                        <div style="margin-top: 10px;">
                            @if (count($errors))
                                <div style="padding: 10px; margin: 10px; font-size: 1.5rem; color: #a94442; background-color: #f2dede; border: 2px solid #ebccd1;" id="errorMsg">
                                    @foreach ($errors->all() as $message)
                                        <p>●{{$message}}</p>
                                    @endforeach
                                </div>
                            @else
                                <div style="display: none;" id="errorMsg"></div>
                            @endif
                        </div>

                        <input type="hidden" name="type" value="10">
                        <div class="c-form-base-detail">

                            <p class="c-form-base-title">会社【必須】</p>
                            <p><input type="text" name="inc_name" placeholder="会社名を入力してください" class="c-form-base-txt" value="{{old('inc_name')}}" maxlength="64"></p>
                        </div>
                        <div class="c-form-base-detail">
                            <p class="c-form-base-title">担当名【必須】</p>
                            <p><input type="text" name="staff_name" placeholder="担当名を入力してください" class="c-form-base-txt" value="{{old('staff_name')}}" maxlength="64"></p>
                            <p><input type="text" name="staff_name_kana" placeholder="フリガナを入力してください"  class="c-form-base-txt" value="{{old('staff_name_kana')}}" maxlength="64"></p>
                        </div>
                        <div class="c-form-base-detail">
                            <p class="c-form-base-title">住所【必須】</p>
                            <p><input type="text" name="addr" placeholder="住所を入力してください" class="c-form-base-txt" value="{{old('addr')}}" maxlength="64"></p>
                        </div>

                        <div class="c-form-base-detail">
                            <p class="c-form-base-title">メールアドレス【必須】</p>
                            <p><input type="text" name="mail" placeholder="メールアドレスを入力してください" class="c-form-base-txt" value="{{old('mail')}}" maxlength="80"></p>
                        </div>

                        <div class="c-form-base-detail">
                            <p class="c-form-base-title">電話番号【必須】</p>
                            <p><input type="text" name="tel" placeholder="電話番号を入力してください" class="c-form-base-txt" value="{{old('tel')}}" maxlength="20"></p>
                        </div>

                    	<div class="c-form-base-detail">
                            <p class="c-form-base-title">希望職種【必須】</p>
                            <div class="c-form-base-check">
                                @foreach($req_work_type_list as $key => $value)
                                    <input type="checkbox" name="req_work_type[]" id="select{{$key}}" value="{{$key}}" @if(is_array(old("req_work_type")) && in_array($key, old("req_work_type"))) checked="checked" @endif >
                                    <label for="select{{$key}}">{{$value}}</label>
                                @endforeach
							</div>
                        </div>

                        <div class="c-form-base-detail">
							<p class="c-form-base-title">お問い合わせ内容</p>
							<textarea name="inquiry" class="c-form-base-txtbox" maxlength="1000">{{old('inquiry')}}</textarea>
						</div>
                        <div class="c-form-base-detail">
                            <p>
                            【個人情報の取扱いについて】</br>
                            ・本フォームからお客様が記入・登録された個人情報は、資料送付・電子メール送信・電話連絡などの目的で利用・保管します。</br>
                            ・<a href="/woa/privacy" target='_blank' rel="nofollow">プライバシーポリシー</a>に同意の上、下記ボタンを押してください。
                            </p>
                        </div>
                        </div>
                    <div class="c-form-base-button-wrap">
                        <input type="submit" value="この内容で申し込む" class="c-form-base-button">
                    </div>
                </div>
                </form>
                </div>

            </div>

            @include('sp.mainparts.normalsidebar')
        </div>
    </main>
    @include('sp.mainparts.bodyfooter')

    @include('sp.mainparts.topscript')

</body>
@endsection
