<?php
/**
 *  JobPostingManager
 */

namespace App\Managers;

use DateTime;

class JobPostingManager
{
    const IGNORE_TEXT = 'お問合せください';

    /**
     * 構造化データマークアップ用パラメタ取得
     * jobpostingに設定するために加工が必要な項目はここで行う
     * @access public
     * @param object $jobData
     * @param object $company
     * @return object
     */
    public function getJobposting(object $jobData, object $company = null): object
    {
        $jobposting = new \stdClass;

        // 募集職種リスト
        $jobposting->jobList = '';
        if (!empty($jobData->job_type_name)) {
            $jobposting->jobList = str_replace('/', ',', $jobData->job_type_name);
        }

        // 説明文
        $jobposting->description = $this->makeDescriptionText($jobData);

        // 仕事内容
        $jobposting->responsibilities = $this->replaceBlankIfIgnoreTarget($jobData->detail);

        // 識別子
        $jobposting->identifier_name = $this->replaceBlankIfIgnoreTarget($jobData->office_name);
        $jobposting->identifier_id = $jobData->job_id;

        // 勤務時間
        $jobposting->working_time = $this->replaceBlankIfIgnoreTarget($jobData->worktime);

        // 求人情報が公開された日
        $jobposting->date_posted = $this->getDatePosted($jobData->last_confirmed_datetime);

        // 働く場所
        $jobposting->job_location_street_address = $this->getJobLocationStreetAddress($jobData);
        $jobposting->job_location_address_locality = $this->replaceBlankIfIgnoreTarget($jobData->addr2_name);
        $jobposting->job_location_address_region = $this->replaceBlankIfIgnoreTarget($jobData->addr1_name);

        // 企業情報
        $jobposting->hiring_organization_name = $this->setHiringOrganizationName($jobData, $company);

        // 給与
        $jobposting->baseSalaryValue = $jobData->salary;
        $jobposting->baseSalaryUnitText = $this->getBaseSalaryUnitText($jobData->employment_type);

        return $jobposting;
    }

    /**
     * jobpostingを表示するか判定
     * @param object $jobData
     * @param object $company
     * @return boolean
     */
    public function getJobpostingDisplayFlag(object $jobData, object $company = null): bool
    {
        // 事業所名公開フラグがoff、または法人名、事業所名共にない場合は、jobpostingは表示させない
        if ($jobData->publicly_flag == 0 || ($jobData->office_name == '' && empty($company->company_name))) {
            return false;
        }

        return true;
    }

    /**
     * 表示する事業所名をセット
     * @param object $jobData
     * @param object $company
     * @return string
     */
    private function setHiringOrganizationName(object $jobData, object $company = null): string
    {
        // 法人名が優先
        if (!empty($company->company_name)) {
            return $company->company_name;
        }

        return $jobData->office_name;
    }

    /**
     * datePostedを取得する
     * なるべく最近の日付を設定したいため、1か月以内の日付を設定する
     * 全ての求人が同じ日付だとよろしくないので、更新日時の「日」だけ利用する
     * @access private
     * @param string $updateDate
     * @return string
     */
    private function getDatePosted(string $updateDate): string
    {
        // 今日
        $today = (new DateTime())->setTime(0, 0, 0);
        // 1か月前
        $oneMonthAgo = (new DateTime($today->format('Y-m-d')))->modify('-1 month');
        // 更新日付
        $updateDate = (new DateTime($updateDate))->setTime(0, 0, 0);

        // 更新日付が1か月以内の場合
        if ($oneMonthAgo->diff($updateDate)->invert == 0) {
            // 「更新日付」を返却
            return $updateDate->format(DateTime::ATOM);
        } else {
            // 今日(ym) + 更新日時(d)
            $todayYmUpdateDateD = new DateTime($today->format('Ym') . $updateDate->format('d'));
            // 「今日(ym) + 更新日時(d)」が未来日でない場合
            if ($todayYmUpdateDateD->diff($today)->invert == 0) {
                // 「今日(ym) + 更新日時(d)」を返却
                return $todayYmUpdateDateD->format(DateTime::ATOM);
            } else {
                // 「今日(ym) + 更新日時(d)の1か月前」を返却
                return $todayYmUpdateDateD->modify('-1 month')->format(DateTime::ATOM);
            }
        }

        return '';
    }

    /**
     * description
     * 求人詳細情報を元にdescriptionを作成
     * @access private
     * @param object
     * @return string
     */
    private function makeDescriptionText(object $jobData): string
    {
        $description = '';

        $columns = [
            'order_pr_title' => ['label' => null, 'new_line' => false],
            'detail'         => ['label' => '▼仕事内容', 'new_line' => false],
            'salary'         => ['label' => '▼給料', 'new_line' => true],
            'dayoff'         => ['label' => '▼休日・休暇', 'new_line' => true],
            'station1'       => ['label' => '▼最寄駅', 'new_line' => true],
            'station2'       => ['label' => '▼最寄駅', 'new_line' => false],
            'station3'       => ['label' => '▼最寄駅', 'new_line' => false],
        ];

        $label = '';
        foreach ($columns as $column => $value) {
            $string = $this->replaceBlankIfIgnoreTarget($jobData->{$column});
            if (empty($string)) {
                continue;
            }
            if (!empty($value['label']) && $label != $value['label']) {
                if ($value['new_line']) {
                    // 見出しの前に改行を入れる
                    $description .= PHP_EOL;
                }
                // 見出しの重複を防ぐ判定用
                $label = $value['label'];
                // 見出しを入れる
                $description .= $label . PHP_EOL;
            }
            $description .= $string . PHP_EOL;
        }

        // 改行コードの統一
        $description = str_replace(["\r\n", "\r", "\n"], "\n", $description);

        return $description;
    }

    /**
     * streetAddress(市区町村以下)を取得する
     * addr(所在地)(フル住所)から都道府県(addr1_name)と市区町村(addr2_name)を除いた文字列を市区町村以下とする
     * @access private
     * @param object $jobData
     * @return string
     */
    private function getJobLocationStreetAddress(object $jobData): string
    {
        $streetAddress = '';
        $addr1 = $this->replaceBlankIfIgnoreTarget($jobData->addr1_name);
        $addr2 = $this->replaceBlankIfIgnoreTarget($jobData->addr2_name);
        $fullAddr = $this->replaceBlankIfIgnoreTarget($jobData->addr);

        $addr3 = str_replace($addr1 . $addr2, '', $fullAddr);
        if ($addr3) {
            $streetAddress = $addr3;
        }

        return $streetAddress;
    }

    /**
     * 項目に設定する値が設定対象外である場合に空文字に置き換える。
     * 項目が設定対象である場合にはrtrim関数を適応した値を返却する。
     * @access private
     * @param string $replaceTarget
     * @return string
     */
    private function replaceBlankIfIgnoreTarget(string $replaceTarget): string
    {
        if (empty($replaceTarget) || $replaceTarget === self::IGNORE_TEXT) {
            return '';
        }

        return rtrim($replaceTarget);
    }

    /**
     * baseSalary - unitTextを取得する
     * 常勤のみ："MONTH"
     * 常勤/非常勤："MONTH" ※常勤を優先
     * 非常勤のみ："HOUR"
     * @param string|null $employ
     * @return string
     */
    private function getBaseSalaryUnitText(?string $employ): string
    {
        $unitText = '-';
        $employList = explode("/", $employ);
        foreach ($employList as $val) {
            if ($val == '常勤') {
                // 常勤
                $unitText = 'MONTH';
                break;
            } elseif ($val == '非常勤') {
                // 非常勤
                $unitText = 'HOUR';
            }
        }

        return $unitText;
    }
}
