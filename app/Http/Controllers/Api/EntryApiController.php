<?php
namespace App\Http\Controllers\Api;

use App\Console\Commands\SfImportCustomer;
use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Managers\ApiManager;
use App\Managers\AreaManager;
use App\Managers\MasterManager;
use App\Managers\TerminalManager;
use App\Managers\UtilManager;
use App\Managers\WoaCustomerManager;
use App\Model\WoaCustomerTrackingMapping;
use Exception;
use Illuminate\Http\Request;
use Log;
use Mail;
use Throwable;
use Validator;

class EntryApiController extends Controller
{
    const CODE_SUCCESS = 1; // 成功
    const CODE_ERROR = 2; // 異常
    // お気持ち
    const BRANCH_DATA = ['A', 'B'];

    // 基本Validateルール
    private $rules = [
        'name_kan'             => 'required|max:64', // 氏名
        'name_cana'            => 'required|max:64', // ふりがな
        'birth'                => 'required|date_format:Ymd', // 生年月日
        'addr1'                => 'required|digits:2', // 都道府県ID
        'addr2'                => 'required|digits:5', // 市区町村ID
        'addr3'                => 'max:255', // 市区町村以下
        'tel'                  => 'required|custom_tel_length', // 電話番号
        'mail'                 => 'max:80', // メールアドレス
        'license'              => 'required', // 保有資格
        'req_emp_type'         => 'required|integer', // 希望雇用形態
        'req_date'             => 'integer', // 入職希望時期
        'retirement_intention' => 'integer', // 退職意向
        'src_site_name'        => 'required|max:64', // 連携元サイト名
        'src_customer_id'      => 'integer', // 連携元顧客ID
        'action'               => 'max:255', // アクションパラメータ
        'action_first'         => 'max:255', // アクションパラメータ（最初）
        'ip'                   => 'max:255', // IPアドレス
    ];

    const INT_MAX_VALUE = 2147483647; // int型最大値

    /**
     * 求職者登録処理
     *
     */
    public function post(Request $request)
    {
        $result = [];
        $message = [];
        $allRequest = [];
        try {
            $allRequest = $request->all();

            // 基本Validate
            $validator = Validator::make($request->all(), $this->rules);
            if (!empty($validator->errors()->all())) {
                // 入力チェックエラー発生時
                foreach ($validator->errors()->all() as $key => $value) {
                    array_push($message, $value);
                }
            }

            // 個別Validate

            // ふりがな
            if (!preg_match('/^[ぁ-んー]+$/u', ($allRequest['name_cana'] ?? ''))) {
                array_push($message, 'ふりがな(name_cana)はひらがなで入力してください');
            }
            // 都道府県ID
            if (empty($this->convertValue2Text(($allRequest['addr1'] ?? ''), 'addr1'))) {
                array_push($message, '都道府県ID(addr1)が不正です');
            }
            // 市区町村ID
            if (empty($this->convertValue2Text(($allRequest['addr2'] ?? ''), 'addr2'))) {
                array_push($message, '市区町村ID(addr2)が不正です');
            }
            // 電話番号
            if (!preg_match('/^[0-9]{10,11}$/', ($allRequest['tel'] ?? ''))) {
                array_push($message, '電話番号(tel)が不正です');
            }
            // メールアドレス
            $mail = $allRequest['mail'] ?? '';
            if (!empty($mail) && !(new ApiManager)->isDnsByMail($mail)) {
                array_push($message, 'メールアドレス(mail)が不正です');
            }
            // 保有資格
            $license = $allRequest['license'] ?? '';
            $license_text = $this->convertValue2Text(explode(',', $license), 'license', ";");
            if (!preg_match("/^[0-9,]+$/", $license) || empty($license_text)) {
                array_push($message, '保有資格(license)が不正です');
            } else {
                $allRequest['license'] = $license_text;
            }
            // 学生
            $licenseList = explode(',', $license);
            foreach ($licenseList as $key => $value) {
                if (in_array($value, config('ini.studentList'))) {
                    if (!isset($allRequest['graduation_year']) || empty($allRequest['graduation_year'])
                     || !preg_match("/20[0-9]{2}年卒|不明/", $allRequest['graduation_year'])) {
                        array_push($message, '学生の場合は卒業年度(graduation_year)を選択してください');
                    }
                }
            }
            // 希望雇用形態
            $req_emp_type_text = $this->convertValue2Text(($allRequest['req_emp_type'] ?? ''), 'emp_type');
            if (empty($req_emp_type_text)) {
                array_push($message, '希望雇用形態(req_emp_type)が不正です');
            } else {
                $allRequest['req_emp_type'] = $req_emp_type_text;
            }
            // 入職希望時期
            if (!empty($allRequest['req_date'])) {
                $req_date_text = $this->convertValue2Text(($allRequest['req_date'] ?? ''), 'req_date');
                if (empty($req_date_text)) {
                    array_push($message, '入職希望時期(req_date)が不正です');
                } else {
                    $allRequest['req_date'] = $req_date_text;
                }
            } else {
                $allRequest['req_date'] = null;
            }
            // 退職意向
            if (!empty($allRequest['retirement_intention'])) {
                $retirement_intention_text = $this->convertValue2Text(($allRequest['retirement_intention'] ?? ''), 'retirement_intention');
                if (empty($retirement_intention_text)) {
                    array_push($message, '退職意向(retirement_intention)が不正です');
                } else {
                    $allRequest['retirement_intention'] = $retirement_intention_text;
                }
            } else {
                $allRequest['retirement_intention'] = null;
            }
            // 連携元サイト名
            if (!in_array($allRequest['src_site_name'], config('ini.SRC_SITE_NAMES'))) {
                array_push($message, '連携元サイト名(src_site_name)が不正です');
            }
            // アクションパラメータ
            if (!preg_match('/^[A-Za-z0-9_]*$/', ($allRequest['action'] ?? ''))) {
                array_push($message, 'アクションパラメータ(action)が不正です');
            }
            // アクションパラメータ（最初）
            if (!preg_match('/^[A-Za-z0-9_]*$/', ($allRequest['action_first'] ?? ''))) {
                array_push($message, 'アクションパラメータ（最初）(action_first)が不正です');
            }
            // IPアドレス
            $ip = $allRequest['ip'] ?? '';
            if (strlen($ip) > 0 && !filter_var($ip, FILTER_VALIDATE_IP)) {
                array_push($message, 'IPアドレス(ip)が不正です');
            }
            $serviceUsageIntention = $allRequest['branch'] ?? '';
            if (!empty($serviceUsageIntention) && !in_array($serviceUsageIntention, self::BRANCH_DATA, true)) {
                array_push($message, 'お気持ち(service_usage_intention)が不正です。service_usage_intention = ' . $serviceUsageIntention);
            }

            if (count($message) > 0) {
                // 入力チェックエラー時は、SF連携フラグを「対象外」に設定
                $allRequest['salesforce_flag'] = WoaCustomerManager::SALESFORCE_FLAG_EXCLUDED;
            } else {
                // 入力チェックエラーなしの時は、SF連携フラグを「未連携」に設定
                $allRequest['salesforce_flag'] = WoaCustomerManager::SALESFORCE_FLAG_NOT_LINKED;
            }

            // 入力値の置換
            foreach ($allRequest as $key => $val) {
                if ($key == 'name_kan' || $key == 'addr3') {
                    // 全角半角スペース＆タブを除去
                    $allRequest[$key] = str_replace(['　', '\t', ' '], '', $allRequest[$key]);
                }
                if ($key == 'name_kan' || $key == 'addr3' || $key == 'entry_memo') {
                    // サニタイズ
                    $allRequest[$key] = htmlspecialchars($allRequest[$key], ENT_QUOTES, 'UTF-8');
                }
                if ($key == 'ua') {
                    // User agentは先頭255文字で切る
                    $allRequest[$key] = mb_substr($allRequest[$key], 0, 255);
                }
            }

            $template_id = null;
            if (isset($allRequest['ua']) && !empty($allRequest['ua'])) {
                if ((new TerminalManager())->isSmartPhone($allRequest['ua'])) {
                    $template_id = "SP_1";
                } else {
                    $template_id = "PC_1";
                }
            } else {
                $template_id = strtoupper((new UtilManager)->getDevice()) . '_1';
            }
            $allRequest['template_id'] = $template_id;

            // 登録処理
            $customerId = (new WoaCustomerManager)->registWoaCustomer($allRequest, true);
            if (!$customerId) {
                throw new Exception("DB登録に失敗しました");
            }

            // WoaCustomerTrackingMappingを作成し、Tracking用のCookieを作成
            (new WoaCustomerTrackingMapping)->createWithCookie($customerId);

            if (count($message) > 0) {
                // 異常終了
                $result['code'] = self::CODE_ERROR;
                $result['message'] = $message;

                // 入力エラー時は、エラーログ出力＆エラーメール送信
                Log::error("API実行エラー customer_id={$customerId} 【エラー内容】→ " . implode(', ', $message));
                $this->sendMail(['customer_id' => $customerId, 'error_message' => '・' . implode("\r\n・", $message)]);
            } else {
                // 正常終了
                $result['code'] = self::CODE_SUCCESS;
                $result['message'] = [];

                // 求職者SF連携(非同期)
                SfImportCustomer::startId($customerId);
            }
        } catch (Exception $e) {
            // 異常終了
            $result['code'] = self::CODE_ERROR;
            $result['message'] = $message;

            // 例外発生時は、エラーメール送信＆エラーログ出力
            if (!empty($e->getMessage())) {
                array_push($message, $e->getMessage());
            }
            $this->sendMail(['error_message' => '・' . implode("\r\n・", $message)]);
            Log::error("API実行エラー（ログをご確認ください）\n【エラー内容】→ " . implode(', ', $message) . "\n" . $e->getTraceAsString());
        } catch (Throwable $e) {
            // 異常終了
            $result['code'] = self::CODE_ERROR;
            $result['message'] = $message;

            // 例外発生時は、エラーメール送信＆エラーログ出力
            if (!empty($e->getMessage())) {
                array_push($message, $e->getMessage());
            }
            $this->sendMail(['error_message' => '・' . implode("\r\n・", $message)]);
            Log::error("API実行エラー（ログをご確認ください）\n【エラー内容】→ " . implode(', ', $message) . "\n" . $e->getTraceAsString());
        }

        $result_value = json_encode($result);
        header('Content-Type: application/json; charset=utf-8');
        echo $result_value;
        exit;
    }

    /**
     * 選択値を文字列に変換
     *
     */
    private function convertValue2Text($value, $column, $delimiter = "")
    {
        if (empty($column) || empty($value)) {
            return null;
        }
        $res = null;
        // データ取得
        switch ($column) {
            // 都道府県
            case "addr1":
                $res = (new AreaManager)->getListAddr1ById($value);
                break;
            // 市区町村
            case "addr2":
                $res = (new AreaManager)->getListAddr2ById($value);
                break;
            // 保有資格（複数選択）
            case "license":
                $res = (new MasterManager)->getListLicenseByIds($value);
                break;
            // 雇用形態
            case "emp_type":
                if (is_array($value)) {
                    $value = array_shift($value);
                }
                $res = (new MasterManager)->getListEmpTypeById($value);
                break;
            // 希望転職時期
            case "req_date":
                $res = (new MasterManager)->getListReqDateById($value);
                break;
            // 退職意向
            case "retirement_intention":
                $idx = 0;
                foreach (config('ini.RETIREMENT_INTENTIONS') as $key => $val) {
                    if ($idx == ($value - 1)) {
                        $res = $val;
                        break;
                    }
                    $idx++;
                }

                return $res;
            default:
                break;
        }

        // 検索結果を文字列に変換
        $result = '';
        if (!empty($res) && is_array($res)) {
            foreach ($res as $key => $val) {
                if (isset($val->{$column})) {
                    if (!empty($result)) {
                        $result .= $delimiter;
                    }
                    $result .= $val->{$column};
                }
            }
        }

        return $result;
    }

    /**
     * メール送信処理
     * @return void
     */
    private function sendMail($data): void
    {
        // メール通知
        $to = config('mail.backlog_mail');
        $options = [
            'from'     => config('mail.admin_mail'),
            'from_jp'  => 'API処理通知',
            'to'       => $to,
            'subject'  => 'WOA API実行エラー',
            'template' => 'mails.api',
        ];

        Mail::to($to)->send(new SendMail($data, $options));

        return;
    }
}
