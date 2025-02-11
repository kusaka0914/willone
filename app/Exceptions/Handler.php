<?php

namespace App\Exceptions;

use App\Managers\WoaOpportunityManager;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;
use Mail;
use Sentry\State\HubInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        // 親クラスのログ出力を先に実行
        parent::report($exception);

        // 親クラスでログ出力対象外とされているものはエラー通知しない
        if ($this->shouldntReport($exception)) {
            return;
        }

        // sentryにエラー通知
        $hub = app(HubInterface::class);
        if ($hub && $this->shouldReport($exception)) {
            $hub->captureException($exception);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        $agent = new Agent();

        // デバイス判定
        if ($agent->isMobile()) {
            $device = 'sp';
        } else {
            $device = 'pc';
        }

        try {
            // FVヘッダー用 有効な掲載求人数取得
            $countActiveWoaOpportunity = number_format((new WoaOpportunityManager())->countActiveWoaOpportunity());
        } catch (\Exception $e) {
            // MySQL server has gone away を想定
            \Log::error(errorLogCommonOutput($e));
        }

        // view assign data
        $viewData = [
            'countActiveWoaOpportunity' => $countActiveWoaOpportunity ?? ''
        ];

        // Routeで許可されてないHTTPメソッドからのアクセスは404エラーとする
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->view($device . '.errors.404', $viewData, 404);
        }

        // ValidationException(バリデーションエラー)
        if ($exception instanceof ValidationException) {
            return parent::render($request, $exception);
        }

        // 基本的に500エラーの画面がベース
        $errorViewName = $device . '.errors.500';

        // httpステータスが返ってきた場合
        if ($this->isHttpException($exception)) {
            // 404
            if ($exception->getStatusCode() == 404) {
                // 404エラーの時は、専用の404エラー画面を表示
                return response()->view($device . '.errors.404', $viewData, 404);
            }

            // 404エラー以外のhttpステータスが返った場合は500エラー画面を表示

            return response()->view($errorViewName, $viewData, 500);
        } elseif (config('app.debug') === false) {
            // debugモードがオフ（prdもしくはstg）では、
            // その他のシステムエラーはerrors/500を表示する。
            return response()->view($errorViewName, $viewData, 500);
        }

        // debugモードがOn（dev環境）の場合、Whoops画面（debug画面）を表示

        return parent::render($request, $exception);
    }
}
