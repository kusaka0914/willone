<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    @include('common._ogp')
    {{-- <title>{{ $headtitle }}</title> --}}
    <script type="text/javascript" src="/woa/js/jquery-1.11.1.min.js"></script>
    <link href="{{addQuery('/woa/css/static/findworkagent.css')}}" rel="stylesheet" type="text/css">
@include('common._gtag')

    <link rel="icon" href="/woa/favicon.ico">
</head>
<body>
    <section class="fka">
        <main>
            <div class="fv relative">
                <img src="/woa/img/findworkagent/fv.webp" alt="国試黒本が提供する就職支援サービス「ウィルワン」">
                <a href="{{ $findWorkAgentPath }}" class="fva absolute"></a>
            </div>

            <div class="toggle relative">
                <div class="guide">
                    <img src="/woa/img/findworkagent/guide.webp" alt="こちらで開閉できます">
                </div>
                <div class="box box1">
                    <img src="/woa/img/findworkagent/step1off.webp" class="off" alt="step1 情報収集">
                    <img src="/woa/img/findworkagent/step1.webp" class="on" alt="step1 情報収集">
                    <div id="btn1" class="btn"></div>
                </div>

                <div class="box box2">
                    <img src="/woa/img/findworkagent/step2off.webp" class="off" alt="step2 見学">
                    <img src="/woa/img/findworkagent/step2.webp" class="on" alt="step2 見学">
                    <div id="btn2" class="btn"></div>
                </div>

                <div class="box box3">
                    <img src="/woa/img/findworkagent/step3off.webp" class="off" alt="step3 面接">
                    <img src="/woa/img/findworkagent/step3.webp" class="on" alt="step3 面接">
                    <div id="btn3" class="btn"></div>
                </div>

                <div class="box box4">
                    <img src="/woa/img/findworkagent/step4off.webp" class="off" alt="step4 内定後～入社前">
                    <img src="/woa/img/findworkagent/step4.webp" class="on" alt="step4 内定後～入社前">
                    <div id="btn4" class="btn"></div>
                </div>
            </div>

            <footer class="relative">
                <img src="/woa/img/findworkagent/footer.webp" alt="国試黒本が提供する就職支援サービス「ウィルワン」">
                <a href="{{ $findWorkAgentPath }}" class="fva absolute"></a>
            </footer>
        </main>
    </section>

    <script>
    // すべてのボタンに対して処理を適用
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function() {
        // 親要素（box）を取得
        const parentBox = this.parentElement;

        // そのbox内のoff/on要素を取得
        const offElement = parentBox.querySelector('.off');
        const onElement = parentBox.querySelector('.on');

        // 現在の状態を確認して反転
        if (offElement.style.display === 'none') {
            // OFFの状態に戻す
            offElement.style.display = 'block';
            onElement.style.display = 'none';
        } else {
            // ONの状態にする
            offElement.style.display = 'none';
            onElement.style.display = 'block';
        }
        });
    });
    </script>
</body>

</html>
