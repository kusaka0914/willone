<?php
namespace App\Http\Controllers\Api;

use App\Console\Commands\SfImportCustomerDigs;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetAreaOrderCountRequest;
use App\Managers\ApiManager;
use App\Managers\AreaManager;
use App\Managers\MasterManager;
use App\Managers\SelectBoxManager;
use App\Managers\SfCustomerManager;
use App\Managers\SlackServiceManager;
use App\Model\ParameterMaster;
use App\Model\WoaOpportunity;
use App\UseCases\Reentry\InquiryDataUseCase;
use App\UseCases\Reentry\ModalDigsRegistUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    /** 戻り値 */
    const RETURN_CODE_SUCCES = 1;
    const RETURN_CODE_ERROR = 2;

    public function post(Request $request, $type)
    {
        $result = [];
        if (!empty($type)) {
            if ($type == 'getCity') {
                // 登録フォームの郵便番号、都道府県から市区町村検索
                $addr1_id = $request->input('addr1', null);
                $zip_id = $request->input('zip', null);
                if (!empty($addr1_id)) {
                    //都道府県ID⇒市区町村
                    $result = $this->_getAreaCities($addr1_id);
                } elseif (!empty($zip_id)) {
                    // 郵便番号=>市区町村
                    $result = $this->_getAddress($zip_id);
                }
            } elseif ($type == 'getCityAll') {
                // 登録フォームの郵便番号予測検索、都道府県から市区町村検索
                $addr1_id = $request->input('addr1', null);
                $zip_id = $request->input('zip', null);
                if (strlen($addr1_id) > 0) {
                    //都道府県ID⇒市区町村
                    $result = $this->_getAreaCities($addr1_id);
                } elseif (strlen($zip_id) > 0) {
                    // 郵便番号の予測検索
                    $result = $this->_getAddressAll($zip_id);
                }
            } elseif ($type == 'checkDns') {
                // メールアドレスのドメイン存在チェック
                $mail = $request->input('mail');
                if (strlen($mail) > 0) {
                    $result = $this->chkDnsByMail($mail);
                }
            }
        }
        $result_value = json_encode($result);
        header('Content-Type: application/json; charset=utf-8');
        echo $result_value;
        exit;
    }

    /**
     * 都道府県を元に市区町村を設定
     */
    private function _getAreaCities($addr1)
    {
        $selectBoxMgr = new SelectBoxManager;
        $result = $selectBoxMgr->sysCitySb($addr1);

        // to json
        $arr = [];
        $i = 0;
        if (!empty($result) && is_array($result)) {
            foreach ($result as $item) {
                $arr[$i]['id'] = $item->id;
                $arr[$i]['addr2'] = $item->addr2;
                $i++;
            }
        }

        return $arr;
    }

    /**
     * 郵便番号を元に住所を設定
     */
    private function _getAddress($zip_id)
    {
        $arr = [];
        // 全角文字を半角に変換
        $hankaku_zip_code = mb_convert_kana($zip_id, "a", "UTF-8");
        // ハイフンを省き7文字でなければ終了
        $zip_code = preg_replace('/-/', '', $hankaku_zip_code);
        if (strlen($zip_code) !== 7) {
            return $arr;
        }

        // 郵便番号をもとに住所を取得
        $selectBoxMgr = new SelectBoxManager;
        $result = $selectBoxMgr->getPrefCityListByZipCode($zip_code);
        if (empty($result)) {
            return $arr;
        }
        // 都道府県を取得
        $cityList = $selectBoxMgr->sysCitySb($result->addr1_id);

        // json用戻り値作成
        $i = 1;
        $city_order = [];
        $j = 0;
        $arr_cities = [];
        if (!empty($cityList) && is_array($cityList)) {
            foreach ($cityList as $item) {
                $city_order[$item->id] = $i;
                ++$i;
                $arr_cities[$j]['id'] = $item->id;
                $arr_cities[$j]['addr2'] = $item->addr2;
                ++$j;
            }
        }

        $arr[0]['city_order'] = $city_order[$result->addr2_id];
        $arr[0]['city_id'] = $result->addr2_id;
        $arr[0]['city_name'] = $result->addr2;
        $arr[0]['city_pref'] = $result->addr1_id;
        $arr[0]['city_detail'] = $result->addr3;
        $arr[0]['zip_code'] = $zip_id;
        $arr[0]['arr_cities'] = $arr_cities;

        return $arr;
    }

    /**
     * 郵便番号を元に住所を設定(あいまい検索)
     */
    private function _getAddressAll($zip_id)
    {
        $selectBoxMgr = new SelectBoxManager;
        $arr = [];
        // 全角文字を半角に変換
        $zip_id_hankaku = mb_convert_kana($zip_id, "a", "UTF-8");
        $zip_code = preg_replace('/-/', '', $zip_id_hankaku);
        // 数字以外の場合は処理を実行しない（3～7桁）
        if (!preg_match("/^[0-9]{3,7}$/", $zip_code)) {
            return $arr;
        }
        // 郵便番号を元に住所のリストを取得
        $zipCityList = $selectBoxMgr->getPrefCityListAllByZipCode($zip_code);
        if (!isset($zipCityList[0]->addr1_id)) {
            return $arr;
        } else {
            $cityList = $selectBoxMgr->sysCitySb($zipCityList[0]->addr1_id);

            if (count($cityList) == 0) {
                return $arr;
            }
            $i = 1;
            $city_order = [];
            foreach ($cityList as $key => $value) {
                $city_order[$value->id] = $i;
                $i++;
            }
            foreach ($zipCityList as $key => $zipCity) {
                if (!isset($city_order[$zipCity->addr2_id])) {
                    continue;
                }
                $arr[$key]['city_order'] = $city_order[$zipCity->addr2_id];
                $arr[$key]['city_id'] = $zipCity->addr2_id;
                $arr[$key]['city_pref_name'] = $zipCity->addr1;
                $arr[$key]['city_name'] = $zipCity->addr2;
                $arr[$key]['city_pref'] = $zipCity->addr1_id;
                $arr[$key]['city_detail'] = $zipCity->addr3;
                $arr[$key]['zip_code'] = $zipCity->zip1 . $zipCity->zip2;
            }
            $arr['city_list'] = $cityList;
        }

        return $arr;
    }

    /**
     * メールアドレスのドメイン存在チェック
     */
    private function chkDnsByMail($mail)
    {
        $apiMgr = new ApiManager;
        $result = $apiMgr->isDnsByMail($mail);

        return ['result' => $result];
    }

    /**
     * エリア毎の求人数取得
     *
     * @param GetAreaOrderCountRequest $request
     * @return JsonResponse
     */
    public function getAreaOrderCount(GetAreaOrderCountRequest $request): JsonResponse
    {
        $result = [
            'code'      => $this::RETURN_CODE_SUCCES,
            'job_count' => 0,
        ];

        $allRequest = $request->all();

        try {
            // 都道府県リスト取得
            $pref_id_list = (new AreaManager)->getListAddr1ById($allRequest["addr1_id"]);
            // ライセンスidから職種名取得
            $licenses = (new MasterManager)->getListLicenseByIds($allRequest["license"]);
            $licenses = array_map(
                function ($lisense) {
                    if ($lisense->license === '整体師') {
                        $lisense->license = '整体師・セラピスト';
                    }

                    return $lisense->license;
                },
                $licenses
            );

            // 職種タイプのidを取得して、１次元配列に変換
            $syokusyu_type_key_value = (new ParameterMaster)->getSyokusyuType($licenses);

            // 都道府県の求人数取得
            $result['job_count'] = (new WoaOpportunity)->getAreaJobCount($pref_id_list[0]->id, $syokusyu_type_key_value->toArray());
        } catch (\Throwable $e) {
            $result['code'] = $this::RETURN_CODE_ERROR;
        }

        return response()->json($result);
    }

    /**
     * 掘起しの連絡希望時間帯の登録
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reRegistReContactTime(Request $request): JsonResponse
    {

        try {
            $sessionReCompleteData = session()->pull('reCompleteData'); // 2度表示しない様にセッションは消す
            $sfCustomerInfo = $sessionReCompleteData['sfCustomerInfo'] ?? null;
            $requestIp = $request->ip();
            if (!$sfCustomerInfo) {
                throw new \InvalidArgumentException("掘起しの連絡希望時間帯の登録でsessionからsfCustomerInfoが取得できませんでした。様子見。\nip:[{$requestIp}]");
            }
            $t = $sessionReCompleteData['t'] ?? null;
            if (!$t) {
                throw new \InvalidArgumentException("掘起しの連絡希望時間帯の登録でsessionからtemplateIDが取得できませんでした。様子見。\nip:[{$requestIp}]");
            }
            $validate = \Validator::make($request->all(), [
                'reentry_contact_time' => 'array',
                'toiawase'             => 'ctrl_emoji_char|max:1000', // 問い合わせ内容
            ])->errors()->all();
            if ($validate) {
                throw new \InvalidArgumentException('掘起しの連絡希望時間帯の登録でRequestの値が不正です。message[' . implode(',', $validate) . "]様子見。\nIP:[{$requestIp}]");
            }
            $request->merge(['t' => $t]);
            new InquiryDataUseCase($sfCustomerInfo, $request->all());
        } catch (\InvalidArgumentException $e) { // ExceptionはHandlerでBacklog通知
            (new SlackServiceManager)->channel('reentry_regist_contact_time_api_error')->send($e->getMessage());

            return response()->json(['code' => self::RETURN_CODE_ERROR]);
        }

        return response()->json(['code' => self::RETURN_CODE_SUCCES]);
    }

    /**
     * モーダルで表示される掘起し登録API
     *
     * @param Request
     * @param ModalDigsRegistUseCase
     * @return JsonResponse
     */
    public function modalDigsRegist(Request $request, ModalDigsRegistUseCase $modalDigsRegistUseCase): JsonResponse
    {

        // 不正な値をDBに登録されないようにRequestチェック
        $selectBoxManager = new SelectBoxManager();
        $reqEmpTypeRecentCheck = $request->input('req_emp_type_recent');
        $reqEmpTypeRecentCheck = array_filter($selectBoxManager->sysEmpTypeMstSb(), function ($item) use ($reqEmpTypeRecentCheck) {
            return $item->emp_type === $reqEmpTypeRecentCheck;
        });
        $reqDateRecentCheck = $request->input('req_date_recent');
        $reqDateRecentCheck = array_filter($selectBoxManager->sysReqdateMstSb(), function ($item) use ($reqDateRecentCheck) {
            return $item->req_date === $reqDateRecentCheck;
        });

        if (empty($reqEmpTypeRecentCheck) ||
            empty($reqDateRecentCheck) ||
            !isset(config('ini.RETIREMENT_INTENTIONS')[$request->input('retirement_intention')]) ||
            $request->input('t') != config('ini.MODAL_DIGS_TEMPLATE_NO') ||
            empty(sfIdCheckAndExtract($request->input('user')))) {
            return response()->json(['code' => self::RETURN_CODE_ERROR]);
        }

        $digsSfCUstomer = (new SfCustomerManager)->getSfCustomer(sfIdCheckAndExtract($request->input('user')));
        if (!$digsSfCUstomer) {
            return response()->json(['code' => self::RETURN_CODE_ERROR]);
        }
        try {
            \DB::beginTransaction();
            $result = $modalDigsRegistUseCase('regist', $request, $digsSfCUstomer);
            \DB::commit();

            // 掘起しSF連携(非同期)
            SfImportCustomerDigs::startId($result['id']);

            // アンケート回答サンクスメール送信(DB登録後にメールでエラーになっても折角回答してもらったので登録は戻さない)
            $modalDigsRegistUseCase('mail', $request, $digsSfCUstomer);
        } catch (\Exception $e) {
            \DB::rollBack();
            (new SlackServiceManager)->channel('job_detail_digs_api_error')->send(implode("\n", [
                '```',
                'モーダル掘起しのエラーでエラーでもモーダルは閉じるので基本対応の必要はないですが、エラーが怪しかったり頻発した場合は調査が必要',
                $e->getMessage(),
                implode("\n", array_slice(explode("\n", $e->getTraceAsString()), 0, 15)),
                '```',
            ]));

            return response()->json(['code' => self::RETURN_CODE_ERROR]);
        }

        return response()->json(['code' => self::RETURN_CODE_SUCCES]);
    }
}
