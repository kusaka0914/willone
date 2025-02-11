<?php
namespace App\Http\Controllers\Kurohon;

use App\Console\Commands\SfImportCustomer;
use App\Http\Controllers\Controller;
use App\Mail\MailSender;
use App\Mail\SendMail;
use App\Managers\SelectBoxManager;
use App\Managers\UtilManager;
use App\Managers\WoaCustomerDigsManager;
use App\Managers\WoaCustomerManager;
use App\Managers\MasterManager;
use App\Managers\WoaOpportunityManager;
use App\Managers\SlackServiceManager;
use App\Model\WoaCustomerTrackingMapping;
use App\Model\KurohonPurchaseInformation;
use App\Model\ParameterMaster;
use App\Model\MasterAddr1Mst;
use App\Model\MasterAddr2Mst;
use App\Managers\AreaManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mail;
use Validator;

/**
 * 黒本リストLP
 */
class KurohonLpController extends Controller
{
    private $view_data = [];
    private $studentList;

    const SLACK_CHANNEL = 'entry_controller_error';

    // 入力フォームValidate設定
    private $fin_rules = [
        'license'              => 'required', // 保有資格
        'req_emp_type'         => 'required', // 希望雇用形態
        'req_date'             => 'required', // 入職希望時期
        'retirement_intention' => 'required', // 退職意向
        'tel'                  => 'required|bail|custom_tel|custom_tel_length|custom_tel_format|custom_tel_exist', // 電話番号
        'user'                 => 'required|string', // salesforce id
    ];

    public function __construct()
    {
        // 基本情報表示
        $this->view_data['noindex'] = 1;
        $this->studentList = config('ini.studentList');
        $this->parameter = new ParameterMaster;
        $this->slackServiceMgr = new SlackServiceManager();
    }

    /**
     * 画面表示
     */
    public function index(Request $request)
    {
        $this->view_data['headtitle'] = '【ウィルワン】ご希望条件入力フォーム';

        // デバイスとLPの整合性判断（PC⇔SPリダイレクト）
        $device = config('app.device');
        // action
        $requestAction = $request->input('action');
        if (!empty($requestAction)) {
            $this->view_data['action'] = $requestAction;
        } else {
            $this->view_data['action'] = 'woa_kurohon';
        }

        $userId = $request->input('user');
        $userInfo = KurohonPurchaseInformation::where('user_id', $userId)->first();
        if (empty($userInfo)) {
            return view("{$device}/errors/SfNotFound", $this->view_data);
        }
        $this->view_data['user'] = $userId;
        // セッション保持
        $request->session()->put('UserInfo', $userInfo);

        // 表示初期値
        $this->setViewDefault();

        // 学生チェック
        $student = false;
        if (!empty($def_license)) {
            foreach ($def_license as $k => $v) {
                if (in_array($v, $this->studentList)) {
                    $student = true;
                    break;
                }
            }
        }
        $this->view_data['student'] = $student;
        $this->view_data['client_id'] = null;

        // 表示するテンプレートの判定
        $this->view_data['t'] = 'kurohonEntry';

        return view("{$device}/kurohon/{$this->view_data['t']}", $this->view_data);
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

        // 保有資格および卒業年度のチェック
        if (isset($allRequest['license']) && count($allRequest['license']) > 0) {
            foreach ($allRequest['license'] as $k => $v) {
                if (!ctype_digit($v)) {
                    $validator->errors()->add('license', '正しい保有資格を選択してください');
                    $errors++;
                }
                if (in_array($v, $this->studentList)) {
                    if (!isset($allRequest['graduation_year']) || empty($allRequest['graduation_year']) || !preg_match("/20[0-9]{2}年卒|不明/", $allRequest['graduation_year'])) {
                        $validator->errors()->add('graduation_year', '学生の場合は卒業年度を選択してください');
                        $errors++;
                    } else {
                        $selectBoxMgr = new SelectBoxManager;
                        $graduationYearList = $selectBoxMgr->sysGraduationYearSb();
                        // 存在チェック
                        if (!array_key_exists($allRequest['graduation_year'], $graduationYearList)) {
                            $validator->errors()->add('graduation_year', '卒業年を正しく選択してください');
                            $errors++;
                        }
                    }
                }
            }
        }

        // 基本Validateエラー時
        if (!empty($validator->errors()->all())) {
            $errors++;
        }
        if ($errors > 0) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userInfo = $request->session()->get('UserInfo');
        if (empty($userInfo)) {
            $userInfo = KurohonPurchaseInformation::where('userId', $userId)->first();
            if (empty($userInfo)) {
                return view("{$device}/errors/SfNotFound", $this->view_data);
            }
        }

        // 選択項目でコードから文字列への置き換えが必要なので呼び出す
        $sessionAction = '';
        if ($request->session()->has('action')) {
            $sessionAction = $request->session()->get('action');
        }
        $allRequest = $this->setRegisterData($allRequest, $userInfo, $sessionAction);

        try {
            \DB::beginTransaction();

            // 登録処理
            $woaCustomerMgr = new WoaCustomerManager;
            $customerId = $woaCustomerMgr->registWoaCustomer($allRequest);

            // WoaCustomerTrackingMappingを作成し、Tracking用のCookieを作成
            (new WoaCustomerTrackingMapping)->createWithCookie($customerId);

            if (!$customerId) {
                \DB::rollBack();
                throw new \Exception('【黒本登録失敗】DB登録エラー');
            }

            // アンケート回答サンクスメール送信
            if (!empty($userInfo->mail)) {
                // メールアドレスが入力されている場合のみ、メール送信
                $this->sendEntryFinMail($allRequest);
            }

            // 運営側へのメール通知
            $this->sendEntryFinMailToManagement($allRequest);

            // おすすめ求人表示
            $recommendOrderList = $this->getRecommends($allRequest['license_text'], $allRequest['addr1_text'], $allRequest['addr2_text']);
            $this->view_data['recommendOrderList'] = $recommendOrderList;
            $this->view_data['jobetnryUrl'] = config('app.url') . '/jobentryfin';

            // セッション保持
            $request->session()->put('t', $allRequest['t']);
            $request->session()->put('name_kan', $allRequest['name_kan']);

            // テンプレートパス
            $view_path = $this->getTemplatePath();
            $this->view_data['name'] = $allRequest['name_kan'];
            $this->view_data['user'] = $customerId;
            $this->view_data['exceptReentryFrag'] = true;
            $this->view_data['action'] = $allRequest['action'];

            \DB::commit();

            // 求職者SF連携(非同期)
            SfImportCustomer::startId($customerId);

            return view($view_path, $this->view_data);
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->slackServiceMgr->channel(self::SLACK_CHANNEL)->send($e->getMessage());
            return view("{$device}/errors/registError", $this->view_data);
        }
    }

    private function setRegisterData($allRequest, $userInfo, $sessionAction)
    {
        // 選択項目でコードから文字列への置き換えが必要なので呼び出す
        $this->setViewDefault();
        $utilMgr = new UtilManager;
        // 登録データ
        // 黒本リストから取得
        $allRequest['name_kan'] = $userInfo->name_kan;
        // 黒本リストから取得
        $allRequest['name_cana'] = $userInfo->name_cana;
        // 固定値
        $allRequest['birth'] = '1700-01-01 00:00:00';
        $allRequest['birth_year'] = '1700';
        // 黒本リストから取得
        $allRequest['zip'] = $userInfo->zip_code;
        // 黒本リストから取得
        $allRequest['addr1'] = $userInfo->prefecture;
        $allRequest['addr1_text'] = $this->getSelectDataToText($allRequest['addr1'], 'addr1');
        // 黒本リストから取得
        $allRequest['addr2'] = $userInfo->city;
        $allRequest['addr2_text'] = $this->getSelectDataToText($allRequest['addr2'], 'addr2');
        // 黒本リストから取得
        $allRequest['addr3'] = $userInfo->house_number;
        // 黒本リストから取得
        $allRequest['mail'] = $userInfo->mail;
        // LPからの取得
        $allRequest['mob_phone'] = $this->convertTel($allRequest['tel']);
        // LPからの取得
        $allRequest['license_text'] = $this->createSelectDataToText($allRequest['license']);
        // LPからの取得
        $allRequest['req_emp_type_text'] = $utilMgr->getArrayVal($this->view_data["req_emp_type_list"], $allRequest['req_emp_type']);
        // LPからの取得
        $allRequest['req_date_text'] = $utilMgr->getArrayVal($this->view_data["req_date_list"], $allRequest['req_date']);
        // LPからの取得
        $allRequest['retirement_intention_text'] = $utilMgr->getArrayVal($this->view_data["req_retirement_list"], $allRequest['retirement_intention']);
        // LPからの取得
        if (empty($allRequest['graduation_year'])) {
            $allRequest['graduation_year'] = null;
        }
        $allRequest['entry_order'] = null;
        $allRequest['introduce_name'] = null;
        $cpSfid = '';
        if (!empty($allRequest['cp'])) {
            $cpSfid = $this->extractSfId($allRequest['cp']);
        }
        $allRequest['cp_sfid'] = '';
        $allRequest['entry_category_manual'] = null;
        $allRequest['ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $allRequest['ua'] = mb_substr($userAgent, 0, 255);
        $allRequest['entry_memo'] = '';
        $allRequest['agreement_flag'] = 1;
        $action_first = $allRequest['action'];
        $action = $allRequest['action'];
        if (!empty($sessionAction)) {
            $tmp = explode(',', $sessionAction);
            $action_first = current($tmp);
            $action = end($tmp);
        }
        $allRequest['action_first'] = $action_first;
        $allRequest['action'] = $action;

        return $allRequest;
    }

    /**
     * 表示用設定値
     * ※都道府県はFormComposer.phpでセット
     */
    private function setViewDefault()
    {
        $selectBoxMgr = new SelectBoxManager;

        // 希望雇用形態
        $this->view_data['req_emp_type_list'] = $this->convertArray($selectBoxMgr->sysEmpTypeMstSb(), 'emp_type');
        // 転職希望時期
        $this->view_data['req_date_list'] = $this->convertArray($selectBoxMgr->sysReqdateMstSb(), 'req_date');
        // 退職意向
        $this->view_data['req_retirement_list'] = config('ini.RETIREMENT_INTENTIONS');
        // 保有資格
        $licenseList = $selectBoxMgr->sysLicenseMstSbNew();
        $this->view_data['licenseList'] = $licenseList;
        // 保有資格マスタの生徒のid
        $this->view_data['licenseStudent'] = config('ini.studentList');
        // 卒業年度
        $graduationYearList = $selectBoxMgr->sysGraduationYearSb();
        $this->view_data['graduationYearList'] = $graduationYearList;
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
            'name' => $customer['name_kan'],
        ];
        $options = [
            'to'        => $customer['mail'],
            'from'      => config('ini.FROM_MAIL'),
            'from_name' => 'willone',
            'subject'   => 'ご登録ありがとうございます 【' . config('ini.SITE_MEISYOU') . '】',
            'template'  => 'mails.entryKurohon',
        ];
        // mail送信
        Mail::to($options['to'])->send(new MailSender($options, $data));
    }

    /**
     * 登録完了時の自動返信メール(To運営側)
     * @return void
     */
    private function sendEntryFinMailToManagement($customer)
    {
        $maildata = $this->parameter->where('genre_id', config('const.genre_seminar_mail_address'))->where('key_value', 1)->first();
        $mail_to = $maildata->value1;

        $options = [
            'from'     => config('ini.FROM_MAIL'),
            'from_jp'  => 'willone',
            'to'       => $mail_to,
            'subject'  => '求人検索の登録がありました',
            'template' => 'mails.entry',
        ];
        $emaildata = [
            'mail_to'               => $mail_to,
            'name_kan'              => $customer['name_kan'],
            'name_cana'             => $customer['name_cana'],
            'birth'                 => $customer['birth'],
            'zip'                   => $customer['zip'],
            'addr1'                 => $customer['addr1_text'],
            'addr2'                 => $customer['addr2_text'],
            'addr3'                 => $customer['addr3'],
            'tel'                   => $customer['mob_phone'],
            'mail'                  => $customer['mail'],
            'license'               => $customer['license_text'],
            'req_emp_type'          => $customer['req_emp_type_text'],
            'req_date'              => $customer['req_date_text'],
            'birth_year'            => $customer['birth_year'],
            'retirement_intention'  => $customer['retirement_intention_text'],
            'graduation_year'       => $customer['graduation_year'],
            'entry_order'           => $customer['entry_order'],
            'action'                => $customer['action'],
            'action_first'          => $customer['action_first'],
            'entry_category_manual' => $customer['entry_category_manual'],
            'template_id'           => $customer['t'],
            'ip'                    => $customer['ip'],
            'ua'                    => $customer['ua'],
            'entry_memo'            => $customer['entry_memo'],
            'agreement_flag'        => $customer['agreement_flag'],
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
        $form_path = "{$device}/kurohon/registered";

        return $form_path;
    }

    private function createSelectDataToText($selectLicense)
    {
        $selectBoxMgr = new SelectBoxManager;
        $licenseList = $this->convertArray($selectBoxMgr->sysLicenseMstSbNew(), 'license');
        $license = [];
        foreach ($selectLicense as $val) {
            if (isset($licenseList[$val])) {
                $license[] = $licenseList[$val];
            }
        }
        return implode(';', $license);
    }

    /**
     * 選択データの表示用文字列化
     *
     */
    private function getSelectDataToText($value, $column, $delimiter = "")
    {
        if (empty($column) || empty($value)) {
            return "";
        }
        $res = null;
        // データ取得
        switch ($column) {
            // 都道府県
            case "addr1":
                $mgr = new AreaManager;
                $res = $mgr->getListAddr1ById($value);
                break;
            // 市区町村
            case "addr2":
                $mgr = new AreaManager;
                $res = $mgr->getListAddr2ById($value);
                break;
        }

        // 検索結果を文字列に変換
        $result = "";
        if (!empty($res) && is_array($res)) {
            foreach ($res as $key => $val) {
                if (isset($val->{$column})) {
                    if ($result != "") {
                        $result .= $delimiter;
                    }
                    $result .= $val->{$column};
                }
            }
        }

        return $result;
    }

    /**
     * 電話番号の変換
     * @return string tel 電話番号
     *
     */
    private function convertTel($tel)
    {
        if (!empty($tel)) {
            // 全角で書かれている場合半角に変換し、全角スペースを除去
            $tel = trim(mb_convert_kana($tel, 'as', 'UTF-8'));
            // 半角数字以外の文字列は除去
            $tel = preg_replace('/[^0-9]/', '', $tel);
        }
        return $tel;
    }

    /**
     * おすすめ求人の取得
     * @param string $license 資格 addr1 都道府県 addr2 市区町村
     * @return object $recommendOrder おすすめ求人
     */
    private function getRecommends($license, $addr1, $addr2)
    {
        $addr1Model = new MasterAddr1Mst;
        $addr2Model = new MasterAddr2Mst;
        $jobType = $this->replaceLicense($license);
        $addr1Id = $addr1Model->getAddr1idByName($addr1);
        $addr2Id = $addr2Model->getAddr2idByName($addr2);

        $keys = [
            'job_type' => $jobType,
            'addr1_id' => $addr1Id,
            'addr2_id' => $addr2Id,
        ];

        $woaOpportunityMgr = new WoaOpportunityManager;
        $recommendOrder = $woaOpportunityMgr->getMatchLicenseNearOrder($keys);

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
