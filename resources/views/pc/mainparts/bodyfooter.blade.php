    <footer class="l-footer">
        <div class="l-footer-contents l-contents-container">
        <div class="l-footer-container">
            <div class="l-footer-logo"><a href="{{ route('Top') }}"><img src="/woa/images/footer-logo.png" alt="ウィルワン"></a></div>
            <nav class="l-global-nav-wrap">
                <ul class="l-global-nav">
                    <li class="l-global-nav-item"><a class="l-global-nav-link" href="/woa">トップ</a></li>
                    <li class="l-global-nav-item"><a class="l-global-nav-link" href="/woa/area">エリアから探す</a></li>
                    <li class="l-global-nav-item"><a class="l-global-nav-link" href="/woa/job">職種から探す</a></li>
                    <li class="l-global-nav-item"><a class="l-global-nav-link" href="/woa/service">キャリア支援サービス</a></li>
                    <li class="l-global-nav-item nav-register"><a class="l-global-nav-link" href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.common')]) }}?action={{config('app.device')}}or-gnav-btn&job_type={{getJobIdFromTypeRoma($type_roma ?? null)}}"><img src="/woa/images/pen-icon.png" alt="新規会員登録"　>会員登録</a></li>
                </ul>
            </nav>
                <!-- <li>
                    <ul class="l-header-nav">
                        <li class="l-header-nav-item"><a class="l-header-nav-link" href="/woa/job">柔道整復師・鍼灸師・あん摩マッサージ指圧師の求人</a></li>
                        <li class="l-header-nav-item"><a class="l-header-nav-link" href="/woa/service">就職支援のウィルワン</a></li>
                    </ul>
                </li> -->
                <!-- <li>
                    <ul class="l-header-nav2">
                        @if(!empty($countActiveWoaOpportunity))
                        <li class="l-header-nav-item">
                            <span class="l-header-count-text">掲載求人数({{ date("m")."月".date("d")."日" }} 更新)</span>
                            <span class="l-header-count-num">{{ $countActiveWoaOpportunity }}</span><span class="l-header-count-text">件</span>
                        </li>
                        <li class="l-header-nav-item">{{--<a class="l-header-nav-link" href="">よくあるご質問</a>--}}</li>
                        @endif
                        <li class="l-header-nav-item"><a class="l-header-nav-link" href="/woa/contact">お問い合わせ</a></li>
                    </ul>
                </li> -->
            </ul>
        </div>
            <ul class="l-footer-nav">
                <li class="l-footer-nav-item"><a href="https://www.bm-sms.co.jp/company/">会社概要</a></li>
                <li class="l-footer-nav-item"><a href="https://policy.bm-sms.co.jp/consumer/privacy/policy" rel="nofollow" target="_blank">個人情報保護方針</a></li>
                <li class="l-footer-nav-item"><a href="https://policy.bm-sms.co.jp/consumer/terms/jinzaibank-com" rel="nofollow" target="_blank">利用規約</a></li>
                <li class="l-footer-nav-item"><a href="{{ route('Contact') }}">お問い合わせ</a></li>
                <li class="l-footer-nav-item"><a href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.common')]) }}?action=pcor-footer-btn&job_type={{getJobIdFromTypeRoma($type_roma ?? null)}}">就職支援への登録</a></li>
                <li class="l-footer-nav-item"><a href="{{ route('Guide') }}">就業中(既卒)の方へ</a></li>
                <li class="l-footer-nav-item"><a href="{{ route('Service') }}">キャリア支援サービス</a></li>
                <li class="l-footer-nav-item"><a href="{{ route('Recommended')}}">新卒の方へ</a></li>
            </ul>
            <ul class="l-footer-nav">
                <li class="l-footer-nav-item"><a href="{{ route('StaffList')}}">スタッフ紹介</a></li>

                <li class="l-footer-nav-item"><a href="{{ route('Recruit') }}">治療院の院長・経営者様</a></li>
                <li class="l-footer-nav-item"><a href="{{ route('Access') }}">アクセス</a></li>
            </ul>
        </div>
        <div class="l-footer-company-info l-contents-container">
            <ul class="company-info-ul">
              <li class="company-info-li">
                <img class="company-info-img" src="/woa/img/pmark.gif" alt="プライバシーマーク">
                <p class="company-info-p">ウィルワンエージェントは運営元である<a href="https://www.bm-sms.co.jp/" rel="noopener" target="_blank">株式会社エス・エム・エス</a>が<a href="https://privacymark.jp/" rel="noopener" target="_blank">プライバシーマーク</a>を取得しており徹底した個人情報保護・情報管理を行っております。
                </p>
              </li>
              <li class="company-info-li">
                <img class="company-info-img" src="/woa/img/jpx.png" alt="JPX東証プライム上場">
                <p class="company-info-p">ウィルワンエージェントを運営する株式会社エス・エム・エスは東京証券取引所 プライム市場上場の株式会社です。</p>
              </li>
              <li class="company-info-li">
                <img class="company-info-img" src="/woa/img/certification.png" alt="認定マーク">
                <p class="company-info-p">
                ウィルワンエージェントを運営する<a href="https://www.bm-sms.co.jp/" rel="nofollow" target="_blank">株式会社エス・エム・エス</a>は、厚生労働省の「<a href="https://www.jesra.or.jp/tekiseinintei/" rel="nofollow" target="_blank">医療・介護・保育分野における適正な有料職業紹介事業者の認定制度</a>」において、第1回の医療分野認定事業者として認定されております。
                </p>
              </li>
            </ul>
        </div>
        <!-- <div class="l-footer-logo"><img src="/woa/images/logo_02.png" alt=""></div> -->
        <div class="l-footer-copy-wrap">
            <div class="l-footer-copy"><small>(C) SMS CO., LTD. All Rights Reserved.</small></div>
        </div>
    </footer>
    <a href="#" id="pagetop" class="c-pagetop"><i class="fa fa-chevron-up"></i><span>page top</span></a>
