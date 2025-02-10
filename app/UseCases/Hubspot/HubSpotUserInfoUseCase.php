<?php

namespace App\UseCases\Hubspot;

use App\Managers\SelectBoxManager;
use App\Services\HubSpotUserService;
use Carbon\Carbon;

class HubSpotUserInfoUseCase
{
    protected $hubSpotUserService;
    protected $studentList = [/* 学生リストのID配列 */];

    public function __construct()
    {
        $this->hubSpotUserService = new HubSpotUserService;
    }

    /**
     * ユースケースのエントリーポイント
     * @param $request
     * @return array
     */
    final public function __invoke($request): array
    {
        $clientId = $request->input('client_id');
        $hubspotUser = $this->hubSpotUserService->getUserData($clientId);

        return $this->setUserData($hubspotUser);
    }

    /**
     * ユーザー情報設定
     * @param array|null $userData
     * @return array
     */
    private function setUserData(?array $userData): array
    {
        $viewData = [
            'name_kan'         => '',
            'zip'              => '',
            'user_addr1'       => '',
            'user_addr2'       => '',
            'addr3'            => '',
            'mob_phone'        => '',
            'mail'             => '',
            'graduation_year'  => '',
            'shikaku'          => [],
            'name_cana'        => '',
            'input_birth_year' => '',
            'age'              => '',
        ];

        if (empty($userData)) {
            return $viewData;
        } else {
            $viewData['mob_mail'] = $userData['email'];
            $viewData['name_kan'] = $userData['lastname'] . $userData['firstname'];
            $viewData['user_addr1'] = $userData['state2'];
            $viewData['addr1'] = !empty($viewData['user_addr1']) ? $viewData['user_addr1'] : (!empty($viewData['addr1']) ? $viewData['addr1'] : '');
            $viewData['user_addr2'] = $userData['state'];
            $viewData['name_cana'] = $userData['last_name2'] . $userData['first_name2'];
            if ($viewData['name_cana'] == '') {
                $viewData['name_cana'] = $userData['furigana'];
            }
            $viewData['mob_phone'] = $userData['mobilephone'] ?? str_replace('-', '', $userData['phone'] ?? '');
            $viewData['input_birth_year'] = substr($userData['date_of_birth'] ?? $userData['birthdate'] ?? '', 0, 4);
            $viewData['age'] = !empty($userData['date_of_birth']) ? Carbon::parse($userData['birthdate'])->age : (!empty($userData['birthdate']) ? Carbon::parse($userData['birthdate'])->age : null);
            $viewData['zip'] = isset($userData['zip']) ? str_replace('-', '', $userData['zip']) : '';
            $selectBoxMgr = new SelectBoxManager;
            if ($userData['zip']) {
                $zipResult = $selectBoxMgr->getPrefCityListByZipCode($viewData['zip']);
                if (!empty($zipResult)) {
                    $viewData['user_addr2'] = $zipResult->addr2;
                    $viewData['addr3'] = $zipResult->addr3;
                }
            }
            $viewData['graduation_year'] = (strpos($userData['graduation_year'], '令和') !== false) ? convertToGregorian($userData['graduation_year']) . '年卒' : $userData['graduation_year'];
            $shikaku = explode(';', $userData['shikaku']);
            if (!empty($shikaku)) {
                $switchShikaku = [
                    '柔道整復師'       => '柔道整復師（学生）',
                    '鍼灸師'         => '鍼灸師（学生）',
                    'あん摩マッサージ指圧師' => 'あん摩マッサージ指圧師（学生）',
                ];
                $tmpShikaku = [];
                foreach ($shikaku as $one) {
                    if (!empty($switchShikaku[$one])) {
                        $tmpShikaku[] = $switchShikaku[$one];
                    }
                }
                if (!empty($tmpShikaku)) {
                    $shikaku = $tmpShikaku;
                }
            }

            $viewData['shikaku'] = $shikaku;
            // 保有資格
            $licenseList = $selectBoxMgr->sysLicenseMstSbNew();
            $studentList = config('ini.studentList');
            foreach ($licenseList as $value) {
                if (in_array($value->license, $shikaku) && in_array($value->id, $studentList)) {
                    $viewData['student'] = true;
                    break;
                }
            }
        }

        return $viewData;
    }
}
