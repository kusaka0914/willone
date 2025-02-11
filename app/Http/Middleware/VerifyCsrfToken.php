<?php
namespace App\Http\Middleware;

// use Session;
use App\Managers\UtilManager;
use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Cookie;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/*',
        'login',
    ];

    /**
     * TokenMismatchExceptionを発生させないパス
     *
     * @var array
     */
    protected $noException = [
        '*',
    ];

    /**
     * [オーバーライド] 特定パスは例外を発生させず、フラグをセットし処理を継続させる
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        // デバイス判定
        $utilMgr = new UtilManager;
        $device = $utilMgr->getDevice();

        // モバイルの場合トークンチェックを行わない
        if ($device == 'mb') {
            return $next($request);
        }

        if (
            $this->isReading($request) ||
            $this->runningUnitTests() ||
            $this->inExceptArray($request) ||
            $this->tokensMatch($request)
        ) {
            return $this->addCookieToResponse($request, $next($request));
        }
        if ($this->inNoExceptionArray($request)) {
            // トークン不整合フラグ
            // 完了アクションにて判定後に削除する事。念の為、flashでセットする
            session()->flash('token_mismatch_flag', true);

            return $next($request);
        } else {
            throw new TokenMismatchException;
        }
    }

    /**
     * [オーバーライド] トークンの有効期限を「ブラウザを閉じるまで」に指定出来る様にする
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function addCookieToResponse($request, $response)
    {
        $config = config('session');
        $expire = $config['expire_on_close'] ? 0 : Carbon::now()->getTimestamp() + 60 * $config['lifetime'];
        $response->headers->setCookie(
            new Cookie(
                'XSRF-TOKEN', $request->session()->token(), $expire,
                $config['path'], $config['domain'], $config['secure'], $config['http_only']
            )
        );

        return $response;
    }

    /**
     * 例外を発生させないパスを判定
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inNoExceptionArray($request)
    {
        foreach ($this->noException as $path) {
            if ($path !== '/') {
                $path = trim($path, '/');
            }

            if ($request->is($path)) {
                return true;
            }
        }

        return false;
    }
}
