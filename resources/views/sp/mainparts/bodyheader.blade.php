<header class="l-header js-header">
        <div class="l-header-container">
            <div class="l-header-logo"><a href="{{ route('Top') }}"><img src="/woa/images/new_logo.png" alt="ウィルワン"></a></div>
            <nav class="l-global-nav-wrap">
                <ul class="l-global-nav">
                    <li class="l-global-nav-item"><a class="l-global-nav-link" href="/woa">トップ</a></li>
                    <li class="l-global-nav-item"><a class="l-global-nav-link" href="/woa/area">エリアから探す</a></li>
                    <li class="l-global-nav-item"><a class="l-global-nav-link" href="/woa/job">職種から探す</a></li>
                    <li class="l-global-nav-item"><a class="l-global-nav-link" href="/woa/service">キャリア支援サービス</a></li>
                </ul>
            </nav>
            <ul class="l-global-nav-sub">
                <li class="l-global-nav-item nav-register"><a class="l-global-nav-link" href="{{ route('Glp', ['lpId' => config('ini.ENTRY_TEMPLATE_ID.pc.common')]) }}?action={{config('app.device')}}or-gnav-btn&job_type={{getJobIdFromTypeRoma($type_roma ?? null)}}"><img src="/woa/images/pen-icon.png" alt="新規会員登録"　>新規会員登録</a></li>
            </ul>
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
    </header>
