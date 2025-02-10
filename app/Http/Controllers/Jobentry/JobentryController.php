<?php
namespace App\Http\Controllers\Jobentry;

use App\Console\Commands\SfImportMatching;
use App\Http\Controllers\Controller;
use App\Mail\MailSender;
use App\Mail\SendMail;
use App\Managers\SfCustomerManager;
use App\Model\ParameterMaster;
use App\Model\WoaMatching;
use App\Model\WoaCustomer;
use Illuminate\Http\Request;
use Mail;
use Validator;

/**
 * 求人応募
 */
class JobentryController extends Controller
{
    private $view_data = [];
    const USER_LENGTH = 23;
    const USER_CHECK_LENGTH = 8;

    // 入力フォームValidate設定
    private $fin_rules = [
        'orderId'             => 'required|string', // オーダーのSFID
        'user'                 => 'required|string', // 求職者のSFID
    ];

    public function __construct()
    {
        // 基本情報表示
        $this->view_data['noindex'] = 1;
        $this->parameter = new ParameterMaster;
    }

    /**
     * 登録処理
     */
    public function fin(Request $request)
    {
        $this->view_data['headtitle'] = '柔道整復師、鍼灸師、マッサージ師の求人・就職支援ならウィルワン';

        // デバイスとLPの整合性判断（PC⇔SPリダイレクト）
        $device = config('app.device');

        // 全URLパラメーター取得
        $allRequest = $request->all();

        // トークン不整合フラグ取得
        $token_mismatch_flag = $request->session()->get('token_mismatch_flag');

        // トークン再生成とトークン不整合フラグ削除
        session()->regenerateToken();
        $request->session()->forget('token_mismatch_flag');
        // トークン不整合フラグ が true の場合、登録処理をせずに、完了画面を表示
        if ($token_mismatch_flag) {
            $this->view_data['name'] = $request->session()->get('name_kan');
            $view_path = $this->getTemplatePath();

            return view($view_path, $this->view_data);
        }

        // 基本Validate
        $validator = Validator::make($allRequest, $this->fin_rules);
        $errors = 0;

        // 基本Validateエラー時
        if (!empty($validator->errors()->all())) {
            $errors++;
        }
        if ($errors > 0) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sfCustomerInfo = $request->session()->get('SfCustomerInfo');
        // セッションにない場合は再取得
        $sfCustomerMgr =  new SfCustomerManager;
        if (empty($sfCustomerInfo) && !$allRequest['exceptReentryFrag']) {
            $sfCustomerInfo = $sfCustomerMgr->getSfCustomer($allRequest['user']);
            if (empty($sfCustomerInfo)) {
                // SFにいない場合エラー画面
                return view("{$device}/errors/SfNotFound", $this->view_data);
            }
        }
        // action項目
        $action_first = $action = $allRequest['action'];
        if ($request->session()->has('action')) {
            $tmp = explode(',', $request->session()->get('action'));
            $action_first = current($tmp);
            $action = end($tmp);
        }
        $allRequest['action_first'] = $action_first;
        $allRequest['action'] = $action;

        // マッチング登録 黒本LP経由
        $woaMatchingModel = new WoaMatching;
        $woaCustomerModel = new WoaCustomer;
        if ($allRequest['exceptReentryFrag']) {
            $matchingId = $woaMatchingModel->registWoaMatchingFromKurohon($allRequest);
            if (!$matchingId) {
                abort(500);
            }
            // woa_customer テーブルから求職者情報を取得
            $woaCustomerInfo = $woaCustomerModel->getCustomer($allRequest['user']);
            if (empty($woaCustomerInfo)) {
                abort(500);
            }

            // 求人応募サンクスメール送信
            if (!empty($woaCustomerInfo->mail)) {
                // メールアドレスが入力されている場合のみ、メール送信
                $this->sendEntryFinMail($woaCustomerInfo);
            }
            // セッション保持
            $request->session()->put('name_kan', $woaCustomerInfo->name_kan);
        } else {
            // マッチング登録
            $allRequest['salesforce_id'] = $sfCustomerInfo->salesforce_id;
            $matchingId = $woaMatchingModel->registWoaMatching($allRequest);
            if (!$matchingId) {
                abort(500);
            }
            // 掘起しSF連携(非同期)
            SfImportMatching::startId($matchingId);

            // 求人応募サンクスメール送信
            if (!empty($sfCustomerInfo->mail)) {
                // メールアドレスが入力されている場合のみ、メール送信
                $this->sendEntryFinMail($sfCustomerInfo);
            }

            // セッション保持
            $request->session()->put('name_kan', $sfCustomerInfo->name);
        }

        // テンプレートパス
        $view_path = $this->getTemplatePath();

        return view($view_path, $this->view_data);
    }

    /**
     * 応募完了時の自動返信メール
     *
     * @param object $customer 求職者情報
     * @return void
     */
    private function sendEntryFinMail($customer)
    {
        if ($customer->name) {
            $data = [
                'name' => $customer->name,
            ];
        } else {
            $data = [
                'name' => $customer->name_kan,
            ];
        }
        $options = [
            'to'        => $customer->mail,
            'from'      => config('ini.FROM_MAIL'),
            'from_name' => 'willone',
            'subject'   => 'ご応募ありがとうございます 【' . config('ini.SITE_MEISYOU') . '】',
            'template'  => 'mails.jobentry_fin_mail',
        ];
        // mail送信
        Mail::to($options['to'])->send(new MailSender($options, $data));
    }

    /**
     * 応募完了画面テンプレートのパスを取得する
     *
     * @return string $form_path 登録完了画面テンプレートのパス
     */
    private function getTemplatePath()
    {
        $device = config('app.device');
        $form_path = "{$device}/jobentry/jobentryFin";

        return $form_path;
    }
}
