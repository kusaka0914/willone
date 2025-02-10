<?php
namespace App\Http\Middleware;

use App\Managers\CrawlerManager;
use App\Managers\TerminalManager;
use App\Managers\UtilManager;
use App\Managers\WoaOpportunityManager;
use Closure;
use Illuminate\Contracts\View\Factory as ViewFactory;

class CommonController
{
    /**
     * Create a new instance.
     *
     * @param  \Illuminate\Contracts\View\Factory  $view
     * @return void
     */
    public function __construct(ViewFactory $view)
    {
        $this->view = $view;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // サイトIDの取得
        $GLOBALS['SITE_ID'] = config('ini.SITE_ID');
        $site_name = config('ini.SITE_NAME');
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
        $terminalMgr = new TerminalManager();

        $utilMgr = new UtilManager;
        $device = $utilMgr->getDevice();

        // 共通設定ALL
        $this->view->share('site_url', config('app.url'));

        // アクション内で config('app.xxxxxx') で取得可能
        config([
            'app.site_id'            => config('ini.SITE_ID'),
            'app.site_name'          => config('ini.SITE_NAME'),
            'app.site_tel'           => config('ini.SITE_TEL'),
            'app.isSmartPhone'       => $terminalMgr->isSmartPhone($ua), // スマートフォンか
            'app.device'             => $device,
            'app.envelope_from_mail' => config('ini.ENVELOPE_FROM_MAIL'),
        ]);

        // 全てのテンプレートで使用可能( {{$～}} )
        $this->view->share('site_name', config('ini.SITE_NAME')); // サイト名
        $this->view->share('isSmartPhone', config('app.isSmartPhone')); // スマートフォンか

        // FVヘッダー用 有効な掲載求人数取得
        $this->view->share('countActiveWoaOpportunity', number_format((new WoaOpportunityManager())->countActiveWoaOpportunity()));

        // リファラ情報をセッションに書き込む
        if (is_null(session()->get('referer', null))) {
            $this->setSearchWord();
        }

        // actionの取得
        $action = $request->input('action');
        if ($action) {
            $join_action = "{$request->session()->get('action')},{$action}";
            $request->session()->put('action', trim($join_action, ','));
            $request->session()->reflash();
            $request->session()->save();
        }

        $res = $next($request);

        // クローラー用処理
        $crawlerMgr = new CrawlerManager;
        if ($crawlerMgr->isCrawler($ua)) {
            $crawlerMgr->removeUrlParam($res);
        }

        return $res;
    }

    /**
     * リファラーを取得する
     * @return void
     */
    public function setSearchWord(): void
    {
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
        session()->put('referer', $referer);

        // 検索エンジンURLパターン設定
        $url_pattern_array = config('ini.URL_PATTERN_ARRAY');

        foreach ($url_pattern_array as $pattern_array) {
            // 正規表現とマッチ
            $regMatch = preg_match($pattern_array, $referer, $regs);
            if ($regMatch) {
                // マッチしたらサイトとキーワードを適用
                session()->put('referer_site_url', $regs[1]);
                // キーワードはエンコーディング返還
                $keyword = urldecode($regs[2]);
                $keyword = mb_convert_encoding($keyword, 'UTF-8', mb_detect_encoding($keyword, 'ASCII,JIS,UTF-8,EUC-JP,SJIS'));
                session()->put('referer_keyword', $keyword);
            } else {
                // マッチしなかったら次へ
                continue;
            }
        }

        return;
    }
}
