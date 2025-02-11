<?php

namespace App\Http\Controllers\Entry;

use App\Console\Commands\SfImportCustomer;
use App\Http\Controllers\Controller;
use App\Managers\ApiManager;
use App\Managers\AreaManager;
use App\Managers\MasterManager;
use App\Managers\SelectBoxManager;
use App\Managers\SlackServiceManager;
use App\Managers\UtilManager;
use App\Managers\WoaCustomerManager;
use App\Model\FriendReferral;
use App\Model\MasterAddr1Mst;
use App\Model\WoaOpportunity;
use App\Model\WoaCustomerTrackingMapping;
use App\UseCases\Entry\EntryMailUseCase;
use App\UseCases\Hubspot\HubSpotUserInfoUseCase;
use App\UseCases\JobNoteEntryUseCase;
use App\Services\AffiliateTagService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class EntryController extends Controller
{
    const MIN_BEFORE_YEAR = 80;
    const MAX_BEFORE_YEAR = 18;

    const USER_CHECK_LENGTH = 5;

    const ENTRY_FULLTIME_EVENT = '常勤';

    const ENTRY_ANY_EVENT = 'こだわらない';

    // 整体師
    const LICENSE_CHIROPRACTOR = 43;

    const KUROHON_LP = [
        'PC_3000',
        'SP_3000',
        'PC_3006',
        'SP_3006',
        'PC_3007',
        'PC_3008',
        'SP_3008',
        'SP_3009',
    ];

    // 黒本系で理由がない限りこちらはLP番号を入れる
    const KUROHON_LP_STUDENT = [
        'PC_3001',
        'SP_3001',
        'PC_3002',
        'SP_3002',
        'PC_3003',
        'SP_3003',
        'PC_3004',
        'SP_3004',
        'PC_3005',
        'SP_3005',
    ];

    // hubspot連携するLPを入れる
    const KUROHON_LP_STUDENT_HUBSPOT = [
        'PC_3001',
        'SP_3001',
        'PC_3002',
        'SP_3002',
        'PC_3003',
        'SP_3003',
        'PC_3004',
        'SP_3004',
        'PC_3005',
        'SP_3005',
    ];

    // 登録カテゴリを固定にするLP、指示がない限り追加しない
    const KUROHON_LP_STUDENT_CATEGORY_FIX = [
        'PC_3001',
        'SP_3001',
    ];

    const NOT_SELECT_LICENSE_LP = [
        'SP_21',
        'SP_22',
        'SP_23',
        'SP_24',
        'SP_27',
    ];

    // 黒本系で理由がない限りこちらはLP番号を入れる
    const NOT_REQ_EMP_TYPE_LP = [
        'SP_23',
        'SP_24',
        'SP_27',
        'PC_3001',
        'SP_3001',
        'PC_3002',
        'SP_3002',
        'PC_3003',
        'SP_3003',
        'PC_3004',
        'SP_3004',
        'PC_3005',
        'SP_3005',
    ];

    const LICENSE_SKIP_LP = [
        'PC_20',
        'SP_20',
        'SP_23',
        'SP_24',
    ];

    const BRANCH_DATA = ['A', 'B'];

    const SLACK_CHANNEL = 'entry_controller_error';

    private $view_data = [];

    // 入力フォームValidate設定
    private $rules = [
        'license'              => 'required', // 保有資格
        'req_emp_type'         => 'required', // 希望雇用形態
        'req_date'             => 'required|max:255', // 入職希望時期
        'zip'                  => 'string|regex:/^[0-9]{7,7}$/', // 郵便番号
        'addr1'                => 'required', // 都道府県
        'addr2'                => 'required', // 市区町村
        'addr3'                => 'max:255', // 番地以下
        'name_kan'             => 'required|max:64', // お名前
        'name_cana'            => 'required|max:64', // ふりがな
        'retirement_intention' => 'required|max:255', // 退職意向
        'mob_phone'            => 'required|bail|custom_tel|custom_tel_length|custom_tel_format|custom_tel_exist', // 電話番号
    ];

    // 紹介者氏名のValidate設定
    private $rulesIntroduce = [
        'introduce_name' => 'required|max:64', // 紹介者氏名
    ];

    // 資格（学生）のIDリスト ※DBから like検索して取得しても良いかも
    private $studentList;
    private $utilManager;
    private $device;

    public function __construct()
    {
        $this->studentList = config('ini.studentList');

        $this->utilManager = new UtilManager;
        $this->slackServiceMgr = new SlackServiceManager();

        // デバイスとLPの整合性判断（PC⇔SPリダイレクト）
        $this->device = $this->utilManager->getDevice();

        // 基本情報表示
        $this->view_data['headtitle'] = '柔道整復師、鍼灸師、マッサージ師の求人・就職支援ならウィルワン';
        $this->view_data['noindex'] = 1;

        $this->opportunity = new WoaOpportunity;
    }

    /**
     * 画面表示
     */
    public function index(Request $request, $type, $t)
    {
        $url = $_SERVER['REQUEST_URI'];
        if ($this->device == "sp") {
            if ($type == 'PC') {
                $rurl = str_replace("PC_", "SP_", $url);

                return redirect($rurl);
            }
        } else {
            if ($type == 'SP') {
                $rurl = str_replace("SP_", "PC_", $url);

                return redirect($rurl);
            }
        }
        // お気持ちの選択肢をスキップ
        $branchSkip = $request->input('branch_skip');
        // お気持ち
        $this->view_data['branch'] = $this->getBranchData($request->input('branch'));
        // 応募求人
        $def_entry_order = $request->old('entry_order');
        if (empty($def_entry_order)) {
            $def_entry_order = $request->input('entry_order');
        }
        $this->view_data['entry_order'] = $def_entry_order;

        // addr1のセット
        $def_addr1 = $request->old('addr1');
        if (empty($def_addr1)) {
            // デフォルトをセットする(初回遷移は26：東京都)
            $def_addr1 = '26';
        }
        $this->view_data["addr1"] = $def_addr1;

        $licenseNextStepFlag = false;
        $licenseStudentCnt = 0;
        // 保有資格デフォルトセット
        $license = in_array($type . '_' . $t, self::NOT_SELECT_LICENSE_LP) ? [] : [40];
        if (!empty($branchSkip)) {
            $license = [];
        }

        // 保有資格チェックボックスデフォルトChecked指定、特定のページからの遷移でjob_typeが付与される
        $job_type = $request->input('job_type');
        if (in_array($job_type, ['40', '41', '42', '43', '44', '45', '46'])) {
            $license = [$job_type];
            $licenseStudentCnt = count(array_intersect(config('ini.studentList'), $license));
        }

        // 入力エラー時は入力値を復元
        $def_license = $request->old('license');
        if (empty($def_license)) {
            $def_license = $license;
        }
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
        // STEP1を飛ばすかどうか、学生の場合卒業年の入力があるので飛ばさない。特定のLPでパラメーターがある場合のみ飛ばす。
        if (!empty($job_type) && in_array($type . '_' . $t, self::LICENSE_SKIP_LP) && $student === false) {
            $licenseNextStepFlag = true;
        }

        $this->view_data['license_next_step_flag'] = $licenseNextStepFlag;
        $this->view_data['license'] = $def_license;
        // 学生が選択されているかどうか
        $this->view_data['license_student_cnt'] = $licenseStudentCnt;

        // 働き方
        $req_emp_type = in_array($type . '_' . $t, self::NOT_REQ_EMP_TYPE_LP) ? [] : [1];
        // お気持ちをスキップする場合は、働き方は空となる
        if (!empty($branchSkip)) {
            $req_emp_type = [];
        }
        // パラメーターで来た値を格納
        $reqEmpType = $request->input('req_emp_type');
        // oldにある値を格納、こちらが最優先となる
        $def_req_emp_type = $request->old('req_emp_type');
        if (empty($def_req_emp_type)) {
            $def_req_emp_type = !empty($reqEmpType) ? [$reqEmpType] : $req_emp_type;
        }
        $this->view_data['req_emp_type'] = $def_req_emp_type;

        // 希望転職時期
        $req_date = '';
        $req_date = $request->input('req_date');
        $def_req_date = $request->old('req_date');
        if (empty($def_req_date)) {
            $def_req_date = $req_date;
        }
        $this->view_data['req_date'] = $def_req_date;

        // 市区町村
        $addr2 = [];
        $def_addr2 = $request->old('v');
        if (empty($def_addr2)) {
            $def_addr2 = $addr2;
        }
        $this->view_data['addr2'] = $def_addr2;

        // 求人ID(求人詳細ページからのアクセス時)
        $job_id = $request->input('job_id');
        $this->view_data['job_id'] = $job_id;

        // actionのセット
        $action = $request->input('action');
        $this->view_data['action'] = $action;
        // tのセット
        $lp = $type . '_' . $t;
        $this->view_data['t'] = $lp;

        // 現在の西暦-18年を初期表示とする
        $this->view_data['example_year'] = date('Y') - 18;

        // URLのパラメータに設定されているCPのSFID
        $this->view_data['cp'] = $request->input('cp', '');
        // 表示初期値
        $this->setViewDefault($type . '_' . $t);
        // どのフィードから登録されたかをSFに記録する
        $this->view_data['tracking_url'] = url()->previous();
        // job_idがあったらwoa_opportunity検索し、事業所名公開のオーダーの事業所名を取得する、
        $utmSource = $request->input('utm_source');
        if (!empty($job_id) && is_numeric($job_id)) {
            $orderInfo = $this->opportunity->getOrderInfoByJobId($job_id);
            if (!empty($orderInfo)) {
                $areaManager = new AreaManager;
                $tmp = $areaManager->getListAddr2ById($orderInfo->addr2);
                $this->view_data['addr2_name'] = $tmp[0]->addr2 ?? null;
                $tmp = $areaManager->getListAddr1ById($orderInfo->addr1);
                $this->view_data['addr1_name'] = $tmp[0]->addr1;
                $this->view_data['office_employment_type'] = $orderInfo->employment_type;
                $this->view_data['office_job_type'] = getJobTypeGroupNameFromJobType($orderInfo->job_type);

                $isOfficePublic = !empty($orderInfo) && $orderInfo->publicly_flag && $orderInfo->exist_order_flag;
                if ($utmSource === 'feed' && $request->input('sec', '') !== '1') {
                    $isOfficePublic = false;
                }
                if ($isOfficePublic) {
                    // {事業所名}表示
                    $this->view_data['office_name'] = $orderInfo->office_name ?? null;
                } elseif (!empty($orderInfo)) {
                    $this->view_data['business'] = $orderInfo->business ?? null;
                }
            }
        }

        // 黒本連携 hubpostからユーザー情報を取得しフォームにセットする
        if (in_array($type . '_' . $t, self::KUROHON_LP_STUDENT_HUBSPOT)) {
            $viewSpotUserData = (new HubSpotUserInfoUseCase)($request);
            $this->view_data = array_merge($this->view_data, $viewSpotUserData);
        }
        $this->setCityList();

        // JINZAI-2091
        // utmパラメータ情報保持
        $utmSource = $request->input('utm_source', '');
        $this->view_data['utm_source'] = $utmSource;

        $utmMedium = $request->input('utm_medium', '');
        $this->view_data['utm_medium'] = $utmMedium;

        $utmCampaign = $request->input('utm_campaign', '');
        $this->view_data['utm_campaign'] = $utmCampaign;

        if ($request->is('glp/friend') || $request->is('woa/glp/friend') || $request->is("entry/{$type}_1000") || $request->is("woa/entry/{$type}_1000")) {
            $this->view_data['introduceEntryPath'] = $this->makeRegistUrl($request);
        }

        // アフィリエイト（ASPレントラックス）用タグ設置判定
        $this->view_data['is_target_rentracks_trading_result_tag'] = (new AffiliateTagService)->isTargetRentracksTag($action);

        // 表示するテンプレートの判定
        $form_path = "{$this->device}/entry/";
        $form_file = '1';
        if (!empty($t)) {
            $exists_check = resource_path() . '/views/' . $form_path . $t;

            if (file_exists($exists_check . '.blade.php')) {
                $form_file = $t;
            }
        }

        return view("{$form_path}{$form_file}", $this->view_data);
    }

    /**
     * 登録処理
     */
    public function fin(Request $request, JobNoteEntryUseCase $jobNoteEntryUseCase)
    {
        // 全URLパラメーター取得
        $allRequest = $request->all();
        // 入力値の置換
        foreach ($allRequest as $key => $val) {
            if ($key == 'name_kan' || $key == 'name_cana' || $key == 'introduce_name') {
                $allRequest[$key] = str_replace(["　", "\t", " "], "", $val);
            } elseif ($key == 'birth_year' || $key == 'input_birth_year' || $key == 'zip' || $key == 'mob_phone') {
                // 全角で書かれている場合半角に変換し、全角スペースを除去
                $val = trim(mb_convert_kana($val, 'as', 'UTF-8'));
                // 半角数字以外の文字列は除去
                $allRequest[$key] = preg_replace('/[^0-9]/', '', $val);
            } elseif ($key == 'mail') {
                // 全角で書かれている場合半角に変換し、全角スペースを除去
                $val = trim(mb_convert_kana($val, 'as', 'UTF-8'));
                // 小文字に変換
                $val = strtolower($val);
                // 半角英数字、特定記号以外の文字列は除去
                $allRequest[$key] = preg_replace('/[^a-z0-9\._@\/\?\+-]/', '', $val);
            }
            if ($key == 'addr3' || $key == 'name_kan' || $key == 'introduce_name') {
                //制御文字の削除
                $allRequest[$key] = $this->utilManager->removeCtrlChar($allRequest[$key]);
                //絵文字の削除
                $allRequest[$key] = $this->utilManager->removeEmojiChar($allRequest[$key]);
            }
            if ($key == 'branch') {
                $allRequest[$key] = $this->getBranchData($allRequest['branch']);
            }
        }
        // トークン不整合フラグ取得
        $token_mismatch_flag = $request->session()->get('token_mismatch_flag');

        // トークン再生成とトークン不整合フラグ削除
        session()->regenerateToken();
        $request->session()->forget('token_mismatch_flag');

        // メールフォームの非表示(初期化対応も含む)
        $this->view_data['mail'] = 'default_settings';
        $feedCheck = $this->detailFeedTransitionCheck(false, $allRequest['action']);
        // トークン不整合フラグ が true の場合、登録処理をせずに、完了画面を表示
        if ($token_mismatch_flag) {
            $jobNoteEntryUrl = $request->session()->get('jobNoteEntryUrl');
            $this->setCompViewData($request, null, $feedCheck, $jobNoteEntryUrl, null);
            $view_path = $this->getTemplatePath($allRequest);

            return view($view_path, $this->view_data);
        }
        // 特定条件下でのvalidateルールを追加
        if ($allRequest['t'] == 'PC_3') {
            $this->rules['entry_category_manual'] = 'required'; // 登録カテゴリ
        }
        if (!isset($allRequest['moving_flg']) || in_array($allRequest['addr1'], array_keys(config('ini.STATE_NOT_MOVE')))) {
            // 転居可否を問わない都道府県（主要都市）
            $allRequest['moving_flg'] = null;
        } else {
            // 転居可否を問う都道府県（必須チェックをつける）
            $this->rules['moving_flg'] = 'required';
        }

        // 掘起し用LPフラグ
        $digs_flg = $this->isDigs($allRequest['t']);

        // 掘起し用LPの場合、以下の必須チェックを外し、空の値をセット
        if ($digs_flg) {
            unset($this->rules['addr1']); // 都道府県
            unset($this->rules['addr2']); // 市区町村
            unset($this->rules['license']); // 保有資格

            $allRequest['zip'] = ''; // nullだとvalidateに引っかかるので空文字
            $allRequest['addr1'] = null;
            $allRequest['addr2'] = null;
            $allRequest['addr3'] = null;
            $allRequest['license'] = null;
            $allRequest['graduation_year'] = null;
        }

        // 紹介者氏名Validate
        if (isset($allRequest['introduce_name'])) {
            $this->rules = array_merge($this->rules, $this->rulesIntroduce);
        }

        // 基本Validate
        $validator = Validator::make($allRequest, $this->rules);

        // validateエラーがある場合の処理（フォーム画面に戻る）
        if (!empty($validator->errors()->all())) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $errors = 0;

        // 個別Validate実行
        // ふりがな
        $name_cana = $allRequest['name_cana'] ?? '';
        if (mb_strlen($name_cana) > 0) {
            if (!preg_match('/^[ぁ-んー]+$/u', $name_cana)) {
                $errors++;
                $validator->errors()->add('name_cana', 'ふりがなは、ひらがなで入力してください');
            }
        }

        // 生まれ年
        if (isset($allRequest['input_birth_year'])) {
            $input_birth_year = $allRequest['input_birth_year'] ?? '';
            // 入力形式で生まれ年が入力された場合、入力形式を優先とする。
            // 無いとは思うが念のため、選択形式の値も入ってきたことを考慮して、
            // 選択形式の値を空文字に置き換える
            if (isset($allRequest['birth_year'])) {
                $allRequest['birth_year'] = '';
            }

            // 入力形式でのチェック
            if (empty($input_birth_year)) {
                $errors++;
                $validator->errors()->add('input_birth_year', '生まれ年を入力してください');
            } else {
                $minYear = date('Y') - self::MIN_BEFORE_YEAR;
                $maxYear = date('Y') - self::MAX_BEFORE_YEAR;
                if (!preg_match('/^[0-9]{4}$/', $input_birth_year)) {
                    // 数値及び桁数チェックエラー
                    $errors++;
                    $validator->errors()->add('input_birth_year', '生まれ年は4桁の数値で入力してください');
                } elseif ($minYear > $input_birth_year || $maxYear < $input_birth_year) {
                    // 入力範囲チェックエラー
                    $errors++;
                    $validator->errors()->add('input_birth_year', '生まれ年は ' . $minYear . ' から ' . $maxYear . ' の間で入力してください');
                }
            }
        } else {
            // 選択形式でのチェック」
            if (empty($allRequest['birth_year'])) {
                $errors++;
                $validator->errors()->add('birth_year', '生まれ年を選択してください');
            }
        }

        // 住所
        // 掘起し用LPの場合、チェックしない
        if (!$digs_flg) {
            $addr1 = $allRequest['addr1'] ?? '';
            $addr2 = $allRequest['addr2'] ?? '';
            if (empty($addr1) || empty($addr2)) {
                $errors++;
                $validator->errors()->add('addr', 'お住まいを選択または入力してください');
            } elseif (!preg_match('/^[0-9]{2}$/', $addr1) || !preg_match('/^[0-9]{5}$/', $addr2)) {
                $errors++;
                $validator->errors()->add('addr', 'お住まいを選択または入力してください');
            }
        }

        // メールの存在チェック
        $mob_mail = $allRequest['mail'] ?? '';
        if (!empty($mob_mail)) {
            if (strlen($mob_mail) > 80) {
                $validator->errors()->add('mob_mail', 'メールアドレスが長すぎます');
                $errors++;
            } elseif (!empty($mob_mail)) {
                $apiMgr = new ApiManager;
                $isDns = $apiMgr->isDnsByMail($mob_mail);
                if (!$isDns) {
                    $validator->errors()->add('mob_mail', 'メールアドレスを正しく入力してください');
                    $errors++;
                }
            }
        }

        // 保有資格および卒業年度のチェック
        if (isset($allRequest['license']) && count($allRequest['license']) > 0) {
            foreach ($allRequest['license'] as $k => $v) {
                if (!ctype_digit($v)) {
                    $validator->errors()->add('license', '正しい保有資格を選択してください');
                    $errors++;
                }
                if (in_array($v, $this->studentList)) {
                    if (!isset($allRequest['graduation_year']) || empty($allRequest['graduation_year']) || !preg_match("/20[0-9]{2}年卒|不明|既卒|その他/", $allRequest['graduation_year'])) {
                        $validator->errors()->add('graduation_year', '学生の場合は卒業年度を選択してください');
                        $errors++;
                    }
                }
            }
        }

        // 登録カテゴリ（手動入力時）
        if (isset($allRequest['entry_category_manual']) && strlen($allRequest['entry_category_manual']) > 64) {
            $validator->errors()->add('entry_category_manual', '登録カテゴリは64文字以内で入力してください');
            $errors++;
        }

        // 退職意向
        $retirement_intention = $allRequest['retirement_intention'] ?? '';
        if (!empty($retirement_intention) && !array_key_exists($retirement_intention, config('ini.RETIREMENT_INTENTIONS'))) {
            $validator->errors()->add('retirement_intention', '正しい退職意向を選択してください');
            $errors++;
        }

        // validateエラーがある場合の処理（フォーム画面に戻る）
        if ($errors > 0) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 生まれ年が入力形式である場合、バリデーションチェック後に本来の値へ移す
        if (empty($allRequest['birth_year']) && isset($allRequest['input_birth_year'])) {
            $allRequest['birth_year'] = $allRequest['input_birth_year'];
            unset($allRequest['input_birth_year']); // 受け渡した後は、入力形式の生まれ年項目を削除
        }

        $detailTransition = $this->detailFeedTransitionCheck(true, $allRequest['action']);
        // 応募情報（備考）: 求人詳細ページのURLを格納
        $allRequest['entry_memo'] = '';
        if (!empty($allRequest['job_id'])) {
            // フィードLP経由 or 通常求人URLを設定
            $allRequest['entry_memo'] = '';
            // 直前の画面のURLを確認、「 https://job-note.jp/4073/」のような値を格納させないため厳密に見る必要がある。LPの前にいたページかLP自体のURLで判定
            if (strpos($allRequest['tracking_url'], '/detail/') !== false) {
                $allRequest['entry_memo'] = $allRequest['tracking_url'];
            } elseif ($detailTransition) {
                $allRequest['entry_memo'] = url()->previous();
            }
        }
        // 利用規約への同意
        $allRequest['agreement_flag'] = !empty($allRequest['agreement_flag']) ? 1 : 0;

        // 入力値補正
        $allRequest['name_kan'] = htmlspecialchars($allRequest['name_kan'], ENT_QUOTES, 'UTF-8');
        $allRequest['birth'] = "{$allRequest['birth_year']}-01-01 00:00:00";
        $action_first = $action = $allRequest['action'];
        if ($request->session()->has('action')) {
            $tmp = explode(',', $request->session()->get('action'));
            $action_first = current($tmp);
            $action = end($tmp);
        }
        $allRequest['action_first'] = $action_first;
        $allRequest['action'] = $action;
        $allRequest['ip'] = $_SERVER['REMOTE_ADDR'];
        // facebookアプリ等で長くなってしまったUAをDBへ登録できる文字数でカットする
        if (strlen($_SERVER['HTTP_USER_AGENT']) > 255) {
            $allRequest['ua'] = substr($_SERVER['HTTP_USER_AGENT'], 0, 255);
        } else {
            $allRequest['ua'] = $_SERVER['HTTP_USER_AGENT'];
        }

        // 文字列化
        $allRequest['addr1_text'] = $this->getSelectDataToText($allRequest['addr1'], 'addr1');
        $allRequest['addr2_text'] = $this->getSelectDataToText($allRequest['addr2'], 'addr2');
        $allRequest['req_date_text'] = $this->getSelectDataToText($allRequest['req_date'], 'req_date');
        $allRequest['req_emp_type_text'] = $this->getSelectDataToText($allRequest['req_emp_type'], 'emp_type');
        $allRequest['license_text'] = $this->getSelectDataToText($allRequest['license'], 'license', ";");
        $allRequest['retirement_intention_text'] = $allRequest['retirement_intention'];
        $allRequest['moving_flg'] = $allRequest['moving_flg'] ?? null;
        if (!empty($allRequest['t']) && in_array($allRequest['t'], self::KUROHON_LP_STUDENT_CATEGORY_FIX)) {
            $allRequest['entry_category_manual'] = '国試黒本（会員登録）';
        }

        try {
            \DB::beginTransaction();

            // 登録処理
            $woaCustomerMgr = new WoaCustomerManager;
            $customerId = $woaCustomerMgr->registWoaCustomer($allRequest);

            // WoaCustomerTrackingMappingを作成し、Tracking用のCookieを作成
            (new WoaCustomerTrackingMapping)->createWithCookie($customerId);

            if (!$customerId) {
                \DB::rollBack();
                throw new \Exception('【customer登録失敗】DB登録エラー');
            }

            if ($request->input('cp') || $request->input('referral_salesforce_id') || $request->input('introduce_name')) {
                if (!FriendReferral::create([
                    'customer_id'            => $customerId,
                    'cp_sms_id'              => $request->input('cp'),
                    'referral_salesforce_id' => $request->input('referral_salesforce_id'),
                    'referral_name'          => $request->input('introduce_name'),
                ])) {
                    throw new \Exception('【友人紹介テーブル】DB登録エラー');
                }
            }

            (new EntryMailUseCase)([
                'allRequest'       => $allRequest,
                'kurohonLp'        => self::KUROHON_LP,
                'kurohonLpStudent' => self::KUROHON_LP_STUDENT,
            ]);

            $request->session()->put('name_kan', $allRequest['name_kan']);

            // メールフォームの表示判定
            $this->view_data['mail'] = $mob_mail;
            if (empty($this->view_data['mail'])) {
                //hiddenでなくセッションで記録しておく
                $request->session()->put('customer_id', $customerId);
            }

            // ご希望の働き方のdatalayer用(常勤)
            if ($allRequest['req_emp_type_text'] == self::ENTRY_FULLTIME_EVENT) {
                $this->view_data['entry_fulltime_event'] = true;
            }
            $redirectUrl = '';
            $jobNoteEntryUrl = '';

            if ($this->isRedirectTarget($allRequest)) {
                $redirectUrl = $jobNoteEntryUseCase(collect($allRequest));
            } else {
                $jobNoteEntryUrl = $jobNoteEntryUseCase(collect($allRequest));
            }

            // サンクスページのリロード対策でジョブノートへのURLをセッションに保存
            $request->session()->put('jobNoteEntryUrl', $jobNoteEntryUrl);

            // 完了画面表示用
            $this->setCompViewData($request, $redirectUrl, $feedCheck, $jobNoteEntryUrl, $customerId);
            $view_path = $this->getTemplatePath($allRequest);

            \DB::commit();

            // 求職者SF連携(非同期)
            SfImportCustomer::startId($customerId);

            // アフィリエイト（メディパートナーズ）用タグ設置判定
            $this->view_data['is_target_rentracks_result_tag'] = (new AffiliateTagService)->isTargetRentracksTag($action_first);

            return view($view_path, $this->view_data);
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->slackServiceMgr->channel(self::SLACK_CHANNEL)->send($e->getMessage());

            return view("{$this->device}/errors/registError", $this->view_data);
        }
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
            // 希望転職時期
            case "req_date":
                $mgr = new MasterManager;
                $res = $mgr->getListReqDateById($value);
                break;
            // 雇用形態
            case "emp_type":
                if (is_array($value)) {
                    $value = array_shift($value);
                }
                $mgr = new MasterManager;
                $res = $mgr->getListEmpTypeById($value);
                break;
            // 保有資格（複数選択）
            case "license":
                $mgr = new MasterManager;
                $res = $mgr->getListLicenseByIds($value);
                break;
            default:
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
     * 表示用設定値
     * ※都道府県はFormComposer.phpでセット
     * @param string $template テンプレート番号
     */
    private function setViewDefault(string $template)
    {
        $selectBoxMgr = new SelectBoxManager;
        // 都道府県
        $prefectureList = $selectBoxMgr->sysPrefectureSb();
        $this->view_data['prefectureList'] = $prefectureList;
        // 生まれ年
        $birthYearList = $selectBoxMgr->sysBirthYearSb();
        $this->view_data['birthYearList'] = $birthYearList;
        // プレースホルダ表示用（生まれ年）
        $this->view_data['exBirthYear'] = date('Y') - self::MAX_BEFORE_YEAR;
        // 保有資格
        $licenseList = $selectBoxMgr->sysLicenseMstSbNew();
        $this->view_data['licenseList'] = $licenseList;
        // 保有資格マスタの生徒のid
        $this->view_data['licenseStudent'] = config('ini.studentList');
        // 就業形態
        $reqEmpTypeList = $selectBoxMgr->sysEmpTypeMstSb();
        if (!empty($reqEmpTypeList)) {
            // テンプレートによっては改行をする必要があるため、変換したパラメータを設定する
            foreach ($reqEmpTypeList as $val) {
                $val->emp_type_br = preg_replace('/^非常勤\(/', "非常勤\n(", $val->emp_type);
            }
        }
        $this->view_data['reqEmpTypeList'] = $reqEmpTypeList;
        // 転職時期
        $req_dateList = $selectBoxMgr->sysReqdateMstSb();
        $this->view_data['req_dateList'] = $req_dateList;
        // 卒業年度
        if (!empty($template) && in_array($template, self::KUROHON_LP_STUDENT)) {
            $graduationYearList = $selectBoxMgr->sysGraduationYearKurohonStudentSb();
        } else {
            $graduationYearList = $selectBoxMgr->sysGraduationYearSb();
        }
        $this->view_data['graduationYearList'] = $graduationYearList;
        // 退職意向
        $this->view_data['retirement_intentionList'] = config('ini.RETIREMENT_INTENTIONS');
        // 登録カテゴリ
        $this->view_data['entry_category_manual'] = config('ini.ENTRY_CATEGORY_MANUAL');
    }

    /**
     * 表示用設定値
     * 市区町村をセット
     */
    private function setCityList()
    {
        $selectBoxMgr = new SelectBoxManager;
        // 市区町村、hubspotデータがある場合はそちらを優先する
        if (!empty($this->view_data['user_addr1'])) {
            $addr1Model = new MasterAddr1Mst;
            $addr1Id = $addr1Model->getAddr1idByName($this->view_data['user_addr1']);
            $cityList = $selectBoxMgr->sysCitySb($addr1Id);
        } else {
            $cityList = $selectBoxMgr->sysCitySb($this->view_data["addr1"]);
        }
        $this->view_data['cityList'] = $cityList;
    }

    /**
     * 登録完了画面用のview_dataをセットする
     *
     * @param Request $request リクエストオブジェクト
     * @param string|null $redirectUrl リダイレクト先のURL
     * @param bool $feedCheck フィードの遷移フラグ
     * @param string|null $jobNoteEntryUrl ジョブノートエントリーURL
     * @param int|null $customerId 顧客ID
     *
     * @return void
     */
    private function setCompViewData(Request $request, ?string $redirectUrl = null, bool $feedCheck = false, ?string $jobNoteEntryUrl = null, ?int $customerId = null)
    {
        // セッションから値を取得
        $name_kan = $request->session()->get('name_kan');

        // 画面表示用
        $this->view_data['name_kan'] = $name_kan;
        $this->view_data['redirect_url'] = $redirectUrl;
        $this->view_data['feed_transition_flag'] = $feedCheck;
        $this->view_data['job_note_entry_url'] = $jobNoteEntryUrl;
        $this->view_data['customer_id'] = $customerId;
    }

    /**
     * 登録完了画面テンプレートのパスを取得する
     * @param array $allRequest
     * @return string $view_path 登録完了画面テンプレートのパス
     */
    private function getTemplatePath(array $allRequest): string
    {
        $t = $allRequest['t'] ?? null;
        if ($t && !empty($allRequest['req_emp_type']) && (in_array($t, self::KUROHON_LP) || in_array($t, self::KUROHON_LP_STUDENT))) {
            return "{$this->device}/entry/kurohonfin";
        }

        return "{$this->device}/entry/fin";
    }

    /**
     * 掘起し用の登録LPかを判定（暫定処理）
     *
     * WOAの暫定的な掘起しは、他JBの掘起しとは作りが異なり、WEB側は登録LPから
     * 「保有資格」「都道府県」「市区町村」の項目を除き新規登録させ、
     * SF側で自動名寄せを行うというもの
     * 他JBと同様の掘起しを作成した際に、関連処理を削除する事
     * @return boolean true: 掘起し用LP, false: 登録LP
     */
    private function isDigs($t)
    {
        // 暫定的な作りなので、設定ファイル等には定義しない
        $digs_lp = [
            'PC_4',
            'SP_5',
        ];

        return in_array($t, $digs_lp);
    }

    /**
     * 紹介用登録画面のURLを返却
     *
     *
     * @access private
     * @param String $request リクエスト
     * @return String 登録画面のURL
     */
    private function makeRegistUrl($request)
    {
        $baseUrl = '/glp/friend2/?action=';
        if ($request->is('woa/glp/friend') || $request->is("woa/entry/PC_1000") || $request->is("woa/entry/SP_1000")) {
            $baseUrl = '/woa/glp/friend2/?action=';
        }

        $action = $request->input('action', 'sms_id');
        $cpId = $request->input('cp', '');
        $cp = !empty($cpId) ? "_cp&cp={$cpId}" : '';
        $url = $baseUrl . $action . $cp;

        return $url;
    }

    /**
     * お気持ちの値を取得 (A/B以外の値を空にする)
     *
     * @param string|null $branch
     * @return string
     */
    private function getBranchData(?string $branch): string
    {
        return in_array($branch, self::BRANCH_DATA, true) ? $branch : '';
    }

    /**
     * 詳細画面、Feedから来たかどうかを判定
     * @param bool $detailCheck
     * @param string|null $getActionParam
     * @return bool
     */
    private function detailFeedTransitionCheck(bool $detailCheck, ?string $getActionParam): bool
    {
        $searchUrlString = '/indeed|stanby|kyujinbox|jobda/';
        if ($detailCheck) {
            $searchUrlString = '/detail|indeed|stanby|kyujinbox|jobda/';
        }
        $result = preg_match($searchUrlString, url()->previous());
        if ($getActionParam) {
            $checkParam = [
                '_indeed',
                '_stanby',
                '_kyujinbox',
                '_jobda',
            ];
            foreach ($checkParam as $value) {
                if (strpos($getActionParam, $value) !== false) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * リダイレクト対象か判定
     * @param array $allRequest
     * @return boolean
     */
    private function isRedirectTarget(array $allRequest): bool
    {
        // 黒本LPの場合はリダイレクト対象外
        if (!empty($allRequest['t']) && (in_array($allRequest['t'], self::KUROHON_LP_STUDENT) || in_array($allRequest['t'], self::KUROHON_LP))) {
            return false;
        }

        // サンクスページ表示対象外かつfeed以外の場合はリダイレクト対象
        if (empty($allRequest['thanks_lp'])) {
            $feedCheck = $this->detailFeedTransitionCheck(false, $allRequest['action']);
            return !$feedCheck;
        }

        // 資格が整体師かつ常勤・どちらでも 以外の場合はリダイレクト対象
        if (in_array(self::LICENSE_CHIROPRACTOR, $allRequest['license']) ||
            !in_array($allRequest['req_emp_type_text'], [self::ENTRY_FULLTIME_EVENT, self::ENTRY_ANY_EVENT])) {
            return true;
        }

        return false;
    }
}
