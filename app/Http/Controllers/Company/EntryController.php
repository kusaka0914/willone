<?php
namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyEntryRequest;
use App\Http\Requests\CompanyOptoutRequest;
// use App\Mail\MailSender;
use App\Mail\SendMail;
use App\Managers\EmployInquiryManager;
use App\Managers\EntryCompanyManager;
use App\Managers\OptoutCompanyManager;
use Illuminate\Http\Request;
use Mail;

class EntryController extends Controller
{
    private $view_data = [];

    public function index(Request $request)
    {

        // action値を確認する
        $action = $this->getAction($request);
        $actionParams = $this->makeActions($action);
        if (!$this->validateAction($actionParams)) {
            abort(500);
        }

        // 画面データを作成する
        if ($request->input('type') == 'stop') {
            // 配信停止画面
            $defaultPath = "company.stop";

            $manager = new OptoutCompanyManager($request->input(), old('mail'));
            $this->view_data = $manager->getViewData();
        } else {
            // 採用担当者登録画面
            $defaultPath = "company.entry";
            $manager = new EntryCompanyManager($request->input(), old('addr1'), old('add2'), old('mail'));
            $this->view_data = $manager->getViewData();
        }

        // session管理
        session()->forget('referer');

        // 画面表示
        $device = config('app.device');

        return view("{$device}.{$defaultPath}", $this->view_data);
    }

    public function fin(CompanyEntryRequest $request)
    {

        // バリデーション後も加工したパラメータで登録処理をおこなう
        $normalizedParams = $request->normalize();

        // 二重送信チェック
        // トークン不整合フラグ
        $token_mismatch_flag = $request->session()->get("token_mismatch_flag");
        // トークン再生成とトークン不整合フラグ削除
        $request->session()->regenerateToken('_token');
        $request->session()->forget("token_mismatch_flag");
        if ($token_mismatch_flag) {
            // 登録処理をせず、完了画面を表示
            return $this->viewCompentry($normalizedParams);
        }

        // employ_inquiryテーブルへの登録処理
        $empInqMgr = new EmployInquiryManager;
        $rst = $empInqMgr->createEntryEmployInquiry($normalizedParams,
            config('ini.SITE_ID'),
            $this->makeActions($this->getAction($request)));
        if (!$rst) {
            abort(404);
        }

        // メール送信
        $this->sendMail($normalizedParams['mail']);

        // 完了画面表示

        return $this->viewCompentry($normalizedParams);
    }

    public function stop(CompanyOptoutRequest $request)
    {
        // バリデーション後も加工したパラメータで登録処理をおこなう
        $normalizedParams = $request->normalize();

        // 二重送信チェック
        // トークン不整合フラグ
        $token_mismatch_flag = $request->session()->get("token_mismatch_flag");
        // トークン再生成とトークン不整合フラグ削除
        $request->session()->regenerateToken('_token');
        $request->session()->forget("token_mismatch_flag");
        if ($token_mismatch_flag) {
            // 完了画面表示
            return $this->viewCompstop();
        }

        // employ_inquiryテーブルへの登録処理
        $empInqMgr = new EmployInquiryManager;
        $rst = $empInqMgr->createOptOutEmployInquiry($normalizedParams,
            config('ini.SITE_ID'),
            $this->makeActions($this->getAction($request)));
        if (!$rst) {
            abort(404);
        }

        // 完了画面表示

        return $this->viewCompstop();
    }

    /**
     * 採用担当者登録完了画面表示に必要なデータを作成して完了画面を表示する
     *
     * @param params
     */
    private function viewCompentry($params)
    {
        $this->view_data['name_kan'] = $params['name_kan'];

        $device = config('app.device');

        return view("{$device}.company.compentry", $this->view_data);
    }

    /**
     * 配信停止登録完了画面表示を表示する
     *
     * @param params
     */
    private function viewCompstop()
    {
        $device = config('app.device');

        return view("{$device}.company.compstop", $this->view_data);
    }

    /**
     * SessionやRequestからアクションパラメータを取得する
     *
     * @param request
     * @return string
     */
    private function getAction($request)
    {
        // Sessionにactionパラメータが設定されていたらSessionのactionパラメータを利用する
        $sessionAction = $request->session()->get('action');
        if ($sessionAction) {return $sessionAction;}

        // Requestにactionパラメータが設定されていたらRequestのactionパラメータを利用する
        $requestAction = $request->input('action');
        if ($requestAction) {return $requestAction;}

        return '';
    }

    /**
     * アクションパラメータの先頭と最後のパラメータを取得する
     *
     * @param action
     * @return array
     */
    private function makeActions($action)
    {
        if (is_null($action)) {return null;}

        $actions = collect(explode(',', trim($action)));
        $action1 = $actions->first();
        $action2 = $actions->last();
        if (empty($action2)) {
            $action2 = $action1;
        }

        return ['action1' => $action1, 'action2' => $action2];
    }

    /**
     * action値のバリデーションを行う
     * [NOTE] 出来る限り厳密なチェックをした方が良い
     *
     * @param params
     */
    private function validateAction($actionParams)
    {
        if (is_null($actionParams)) {return false;}

        // 各action値のサイズチェック（サイズはDBカラムのサイズ）
        if (mb_strlen($actionParams['action1']) > 255) {
            return false;
        }
        if (mb_strlen($actionParams['action2']) > 255) {
            return false;
        }

        return true;
    }

    /**
     * thanksメール送信
     *
     * @param mailTo
     */
    private function sendMail($mail_to)
    {

        $options = [
            'from'     => config('ini.FROM_MAIL'),
            'from_jp'  => config('ini.SITE_MEISYOU'),
            'to'       => $mail_to,
            'subject'  => 'お問い合わせありがとうございます。【' . config('ini.SITE_MEISYOU') . '】',
            'template' => 'mails.contact_completion_mail',
        ];

        $emaildata = [
            'mail_to' => $mail_to];

        Mail::to($mail_to)->send(new SendMail($emaildata, $options));
    }
}
