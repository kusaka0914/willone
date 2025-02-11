<?php

namespace App\Http\Controllers\Reentry;

use App\Console\Commands\SfImportCustomerDigs;
use App\Http\Controllers\Controller;
use App\Mail\MailSender;
use App\Mail\SendMail;
use App\Managers\MasterManager;
use App\Managers\SelectBoxManager;
use App\Managers\SfCustomerManager;
use App\Managers\SlackServiceManager;
use App\Managers\UtilManager;
use App\Managers\WoaCustomerDigsManager;
use App\Managers\WoaOpportunityManager;
use App\Model\MasterAddr1Mst;
use App\Model\MasterAddr2Mst;
use App\Model\ParameterMaster;
use App\UseCases\Hubspot\HubSpotUserInfoUseCase;
use App\UseCases\Reentry\InquiryDataUseCase;
use Illuminate\Http\Request;
use Mail;
use Validator;

/**
 * 掘起しアンケート
 */
class ReentryController extends Controller
{
    private $view_data = [];
    const SLACK_CHANNEL = 'reentry_fin_controller_error';

    // 入力フォームValidate設定
    private $fin_rules_1 = [
        'req_emp_type'         => 'required', // 希望雇用形態
        'req_date'             => 'required', // 入職希望時期
        'retirement_intention' => 'required', // 退職意向
        'user'                 => 'required|string', // salesforce id
    ];

    private $fin_rules_2 = [
        'contact_inquiry'      => 'integer',
        'reentry_contact_time' => 'array',
        'toiawase'             => 'ctrl_emoji_char|max:1000', // 問い合わせ内容
    ];

    private $fin_rules_3 = [
        'reentry_contact_time_1' => 'nullable|string',
        'reentry_contact_time_2' => 'nullable|string',
        'reentry_contact_time_3' => 'nullable|string',
        'toiawase'               => 'ctrl_emoji_char|max:1000', // 問い合わせ内容
    ];

    private $fin_rules_4 = [
        'reentry_contact_time' => 'array',
        'toiawase'             => 'ctrl_emoji_char|max:1000', // 問い合わせ内容
    ];

    private $fin_rules_5 = [
        'contact_inquiry'        => 'required|integer', // 就職活動状況について
        'graduation_year'        => 'required|string', // 卒業予定年
        'input_birth_year'       => 'required|string', // 生まれ年
        'mob_phone'              => 'required|bail|custom_tel|custom_tel_length|custom_tel_format|custom_tel_exist', // 電話番号
        'mob_mail'               => 'required|custom_email|max:80', // メールアドレス
        'reentry_contact_time_1' => 'nullable|string', // 連絡希望時間帯第1希望
        'reentry_contact_time_2' => 'nullable|string', // 連絡希望時間帯第2希望
        'reentry_contact_time_3' => 'nullable|string', // 連絡希望時間帯第3希望
        'toiawase'               => 'ctrl_emoji_char|max:1000', // 問い合わせ内容
    ];

    private $fin_rules_6 = [
        'req_emp_type'         => 'required', // 希望雇用形態
        'req_date'             => 'required', // 入職希望時期
        'retirement_intention' => 'required', // 退職意向
        'user'                 => 'required|string', // salesforce id
    ];

    private $fin_rules_7 = [
        'req_emp_type'         => 'required', // 希望雇用形態
        'req_date'             => 'required', // 入職希望時期
        'retirement_intention' => 'required', // 退職意向
        'user'                 => 'required|string', // salesforce id
    ];

    public function __construct()
    {
        // 基本情報表示
        $this->view_data['noindex'] = 1;
        $this->parameter = new ParameterMaster;
        $this->slackServiceMgr = new SlackServiceManager();
    }

    /**
     * 画面表示
     */
    public function index(Request $request)
    {
        session()->forget('reCompleteData'); // 完了画面で処理をするデータ
        $this->view_data['headtitle'] = '【ウィルワン】ご希望条件入力フォーム';

        // デバイスとLPの整合性判断（PC⇔SPリダイレクト）
        $device = config('app.device');
        $isSmartPhone = config('app.isSmartPhone');
        $url = $request->getRequestUri() ?? '';
        if ($isSmartPhone) {
            $redirectUrl = str_replace("PC_", "SP_", $url);
            if ($redirectUrl != $url) {
                return redirect($redirectUrl);
            }
        } else {
            $redirectUrl = str_replace("SP_", "PC_", $url);
            if ($redirectUrl != $url) {
                return redirect($redirectUrl);
            }
        }
        // action
        $action = $request->input('action');
        $this->view_data['action'] = $action;

        $this->view_data['client_id'] = null;
        $this->view_data['user'] = null;

        // 求職者SFID
        $user = $request->input('user');

        if (!empty($request->input('client_id') && empty($user))) {
            $viewSpotUserData = (new HubSpotUserInfoUseCase)($request);
            if (empty($viewSpotUserData)) {
                return view("{$device}/errors/SfNotFound", $this->view_data);
            }
            // HubSpotのクライアントID
            $this->view_data['client_id'] = $request->input('client_id');
            $this->view_data = array_merge($this->view_data, $viewSpotUserData);
            // 卒業年度
            $this->view_data['graduationYearList'] = (new SelectBoxManager)->sysGraduationYearKurohonStudentSb();
        } else {
            // Userのチェック、SF求職者IDの切り出し
            $sfUserId = sfIdCheckAndExtract($user);
            if ($sfUserId === '') {
                return view("{$device}/errors/SfNotFound", $this->view_data);
            }
            $this->view_data['user'] = $sfUserId;

            // SFコメディカル求職者オブジェクト取得
            $sfCustomer = (new SfCustomerManager)->getSfCustomer($sfUserId);
            if (empty($sfCustomer)) {
                // SFにいない場合エラー画面
                return view("{$device}/errors/SfNotFound", $this->view_data);
            }

            // セッション保持
            $request->session()->put('SfCustomerInfo', $sfCustomer);
        }

        // テンプレートNo
        $t = $request->input('t');

        // 表示初期値
        $this->setViewDefault($request);

        // 表示するテンプレートの判定
        $form_path = "{$device}/reentry/";
        $form_file = '1';
        if (!empty($t)) {
            $exists_check = resource_path() . '/views/' . $form_path . $t;

            if (!file_exists($exists_check . '.blade.php')) {
                // 存在しない場合は1.blade.php
            } else {
                $form_file = $t;
            }
        }
        $this->view_data['t'] = $form_file;

        return view("{$form_path}{$form_file}", $this->view_data);
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
        $t = $allRequest['t'];
        $ruleProperty = 'fin_rules_' . $t;

        // 基本Validate
        $validator = Validator::make($allRequest, $this->$ruleProperty);

        // カスタムバリデーションロジックを追加
        if ($t === '5') {
            $validator->after(function ($validator) use ($allRequest) {
                if (empty($allRequest['reentry_contact_time_1']) &&
                    empty($allRequest['reentry_contact_time_2']) &&
                    empty($allRequest['reentry_contact_time_3'])) {
                    $validator->errors()->add('reentry_contact_time', '連絡希望時間帯のいずれか1つ以上を入力してください。');
                }
            });
        }

        $errors = 0;
        // 基本Validateエラー時
        if (!empty($validator->errors()->all())) {
            $errors++;
        }
        if ($errors > 0) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // セッションにない場合は再取得
        $customerInfo = null;
        if ($t === '5') {
            if (!empty($request->input('client_id'))) {
                $viewSpotUserData = (new HubSpotUserInfoUseCase)($request);
                $customerInfo = (object) $viewSpotUserData;
            }
        } else {
            $customerInfo = $request->session()->get('SfCustomerInfo');
            if (empty($customerInfo)) {
                $customerInfo = (new SfCustomerManager)->getSfCustomer($allRequest['user']);
            }
        }

        if (in_array($t, [2, 3, 4, 5])) {
            try {
                new InquiryDataUseCase($customerInfo, $allRequest);
                // テンプレートパス
                $view_path = $this->getTemplatePath();

                return view($view_path, $this->view_data);
            } catch (\Exception $e) {
                $this->slackServiceMgr->channel(self::SLACK_CHANNEL)->send($e->getMessage());

                return view("{$device}/errors/registError", $this->view_data);
            }
        }

        // DBに登録する項目をセット
        $allRequest['mail'] = $customerInfo->mail;
        $allRequest['salesforce_id'] = $customerInfo->salesforce_id;

        // action項目
        $action_first = $action = $allRequest['action'];
        if ($request->session()->has('action')) {
            $tmp = explode(',', $request->session()->get('action'));
            $action_first = current($tmp);
            $action = end($tmp);
        }
        $allRequest['action_first'] = $action_first;
        $allRequest['action'] = $action;

        $allRequest['ip'] = $request->ip() ?? '';
        $allRequest['ua'] = $request->header('User-Agent') ?? '';
        $allRequest['ua'] = mb_substr($allRequest['ua'], 0, 255);

        // 選択項目でコードから文字列への置き換えが必要なので呼び出す
        $this->setViewDefault($request);
        $utilMgr = new UtilManager;
        $allRequest['req_emp_type_text'] = $utilMgr->getArrayVal($this->view_data["req_emp_type_list"], $allRequest['req_emp_type']);
        $allRequest['req_date_text'] = $utilMgr->getArrayVal($this->view_data["req_date_list"], $allRequest['req_date']);
        $allRequest['retirement_intention_text'] = $utilMgr->getArrayVal($this->view_data["req_retirement_list"], $allRequest['retirement_intention']);
        $allRequest['name'] = $customerInfo->name;
        $allRequest['entry_route'] = config('ini.ETNRY_ROUTE_REENTRY');
        $allRequest['web_customer_id'] = $customerInfo->web_customer_id;

        try {
            \DB::beginTransaction();

            // 求職者掘起し登録
            $woaCustomerMgr = new WoaCustomerDigsManager;
            $digsId = $woaCustomerMgr->registWoaCustomerDigs($allRequest);
            if (!$digsId) {
                \DB::rollBack();
                throw new \Exception('【customer求職者掘起し登録失敗】DB登録エラー');
            }

            // アンケート回答サンクスメール送信
            if (!empty($customerInfo->mail)) {
                // メールアドレスが入力されている場合のみ、メール送信
                $this->sendEntryFinMail($allRequest);
            }

            // 黒本経由等、掘り起こし以外の場合true
            $this->view_data['exceptReentryFrag'] = false;

            // 運営側へのメール通知
            $this->sendEntryFinMailToManagement($allRequest);

            // レコメンド求人取得
            $recommendOrderList = $this->getRecommends($customerInfo);
            $this->view_data['recommendOrderList'] = $recommendOrderList;
            $this->view_data['jobetnryUrl'] = config('app.url') . '/jobentryfin';

            // セッション保持
            $request->session()->put('t', $allRequest['t']);
            $request->session()->put('name_kan', $customerInfo->name);

            // テンプレートパス
            $view_path = $this->getTemplatePath();
            $this->view_data['name'] = $allRequest['name'];
            $this->view_data['user'] = $allRequest['salesforce_id'];
            $this->view_data['action'] = $allRequest['action'];

            \DB::commit();

            // 掘起しSF連携(非同期)
            SfImportCustomerDigs::startId($digsId);

            if ($request->input('complete_modal')) {
                session()->put('reCompleteData', ['sfCustomerInfo' => $customerInfo, 't' => $t]);
            }

            return view($view_path, $this->view_data);
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->slackServiceMgr->channel(self::SLACK_CHANNEL)->send($e->getMessage());

            return view("{$device}/errors/registError", $this->view_data);
        }
    }

    /**
     * 表示用設定値
     * ※都道府県はFormComposer.phpでセット
     * @param Request $request
     *
     */
    private function setViewDefault(Request $request)
    {
        if ($request->input('t') != 2) {
            $selectBoxMgr = new SelectBoxManager;

            $reqEmpTypeList = $selectBoxMgr->sysEmpTypeMstSb();

            if ($request->input('t') == 7) {
                $reqEmpTypeList = $selectBoxMgr->sysEmpTypeMstSbNew();
                if (!empty($reqEmpTypeList)) {
                    // 改行をする必要があるため、変換したパラメータを設定する
                    foreach ($reqEmpTypeList as $val) {
                        $val->emp_type = preg_replace('/^非常勤\(/', "非常勤\n(", $val->emp_type);
                    }
                }
            }

            // 希望雇用形態
            $this->view_data['req_emp_type_list'] = $this->convertArray($reqEmpTypeList, 'emp_type');
            // 転職希望時期
            $this->view_data['req_date_list'] = $this->convertArray($selectBoxMgr->sysReqdateMstSb(), 'req_date');
            // 退職意向
            $this->view_data['req_retirement_list'] = config('ini.RETIREMENT_INTENTIONS');
        } elseif (empty($request->old('contact_inquiry'))) {
            $this->view_data['contact_inquiry'] = 1;
        }
    }

    /**
     * idをキーにした配列に変換する
     * @return array()
     */
    private function convertArray($arr, $key)
    {
        $res = [];
        foreach ($arr as $k => $v) {
            $res[$v->id] = $v->{$key};
        }

        return $res;
    }

    /**
     * 登録完了時の自動返信メール
     * @return void
     */
    private function sendEntryFinMail($customer)
    {
        $data = [
            'name' => $customer['name'],
        ];
        $options = [
            'to'        => $customer['mail'],
            'from'      => config('ini.FROM_MAIL'),
            'from_name' => 'willone',
            'subject'   => 'ご回答ありがとうございます 【' . config('ini.SITE_MEISYOU') . '】',
            'template'  => 'mails.reentry_fin_mail',
        ];
        // mail送信
        Mail::to($options['to'])->send(new MailSender($options, $data));
    }

    /**
     * 登録完了時の自動返信メール(To運営側)
     * @return void
     */
    private function sendEntryFinMailToManagement($request)
    {
        $maildata = $this->parameter->where('genre_id', config('const.genre_seminar_mail_address'))->where('key_value', 1)->first();
        $mailTo = $maildata->value1;

        $options = [
            'from'     => config('ini.FROM_MAIL'),
            'from_jp'  => 'willone',
            'to'       => $mailTo,
            'subject'  => '掘り起こしの登録がありました',
            'template' => 'mails.reEntryToManagement',
        ];
        $emaildata = [
            'mailTo'              => $mailTo,
            'salesforceId'        => $request['salesforce_id'],
            'webCustomerId'       => $request['web_customer_id'],
            'name'                => $request['name'],
            'mail'                => $request['mail'],
            'reqEmpType'          => $request['req_emp_type_text'],
            'reqDate'             => $request['req_date_text'],
            'retirementIntention' => $request['retirement_intention_text'],
            'ip'                  => $request['ip'],
            'ua'                  => $request['ua'],
            'changetype'          => $request['changetype'],
        ];

        Mail::to($options['to'])->send(new SendMail($emaildata, $options));
    }

    /**
     * 登録完了画面テンプレートのパスを取得する
     * @return string $form_path 登録完了画面テンプレートのパス
     */
    private function getTemplatePath()
    {
        $device = config('app.device');
        $form_path = "{$device}/reentry/Compjobentry";

        return $form_path;
    }

    /**
     * おすすめ求人の取得
     * @param object $sfCustomerInfo SFの求職者情報
     * @return object $recommendOrder おすすめ求人
     */
    private function getRecommends($sfCustomerInfo)
    {
        $addr1Model = new MasterAddr1Mst;
        $addr2Model = new MasterAddr2Mst;

        $jobType = $this->replaceLicense($sfCustomerInfo->license);
        $addr1Id = $addr1Model->getAddr1idByName($sfCustomerInfo->addr1);
        $addr2Id = $addr2Model->getAddr2idByName($sfCustomerInfo->addr2);

        $keys = [
            'job_type' => $jobType,
            'addr1_id' => $addr1Id,
            'addr2_id' => $addr2Id,
        ];

        $woaOpportunityMgr = new WoaOpportunityManager;
        $recommendOrder = $woaOpportunityMgr->getMatchLicenseNearOrder($keys);
        $recommendOrderCount = count($recommendOrder);
        $limit = config('const.recommend_order_bounds');
        // レコメンド求人10件以下の場合求職者から近い順に取得
        if ($recommendOrderCount < $limit) {
            $bounds = $limit - $recommendOrderCount;
            $addRecommendOrder = $woaOpportunityMgr->getNearOfficeByCustomer($sfCustomerInfo, $bounds);
            // 追加する求人が指定件数を満たさなかった場合同じ都道府県の求人を取得
            if (count($addRecommendOrder) < $bounds && !empty($addRecommendOrder)) {
                $addRecommendOrderFromPref = $woaOpportunityMgr->getNearOfficeByCustomer($sfCustomerInfo, $limit, true, $keys['addr1_id']);
                foreach ($addRecommendOrderFromPref as $orderFromPrefValue) {
                    // 10キロ圏内のオーダーから同じ都道府県で取得したオーダーのidを検索
                    $orderFromPrefKey = array_search($orderFromPrefValue->job_id, array_column($addRecommendOrder, 'job_id'));
                    // 検索結果が被っていなかった場合そのオーダーを追加
                    if (empty($orderFromPrefKey)) {
                        $addRecommendOrder[] = $orderFromPrefValue;
                        // 不足件数分取得したら終了
                        if (count($addRecommendOrder) == $bounds) {
                            break;
                        }
                    }
                }
            }
            if (!empty($addRecommendOrder)) {
                $recommendOrder = array_merge($recommendOrder, $addRecommendOrder);
            }
        }

        foreach ($recommendOrder as $order) {
            // 最寄駅
            $stations = $woaOpportunityMgr->setStations($order);
            if (!empty($stations)) {
                $order->station = $stations;
            }
        }

        return $recommendOrder;
    }

    /**
     * 保有資格を募集資格の値に変換
     * @param string $license 求職者の保有資格
     * @return string $jobType 募集職種
     */
    private function replaceLicense($license)
    {
        if (empty($license)) {
            return '';
        }

        $licenseList = explode(';', $license);

        $jobTypeList = [];
        // もし職種が上手く作成できなくてもエラーにならず全職種でおすすめ求人が表示されるように
        $jobType = '.*';
        $masterMgr = new MasterManager;
        $licenseMappingList = config('ini.LICENSE_MAPPING');
        foreach ($licenseList as $key => $value) {
            $jobTypeName = $licenseMappingList[$value];
            if (isset($jobTypeName)) {
                array_push($jobTypeList, $this->replaceJobTypeId($masterMgr->getJobtypeId($jobTypeName)));
                if ($jobTypeName === '柔道整復師') {
                    // 保有資格が柔道整復師の場合は
                    // 募集職種が「柔道整復師（管理）の求人も表示させるようにする
                    array_push($jobTypeList, $this->replaceJobTypeId($masterMgr->getJobtypeId('柔道整復師（管理）')));
                }
            }
        }

        $jobType = implode('|', $jobTypeList);

        return $jobType;
    }

    /**
     * 正規表現用に変換
     * @param $jobTypeId 募集職種ID
     * @return .*募集職種ID.*
     */
    private function replaceJobTypeId($jobTypeId)
    {
        return '^' . $jobTypeId . ',|.*\,' . $jobTypeId . '\,.*|,' . $jobTypeId . '$';
    }
}
