<?php

namespace App\Managers;

use App\Logging\BatchLogger;
use App\Model\Company;
use App\Model\WoaOpportunity;
use Illuminate\Support\Arr;

class UpdateCompanyIdManager
{
    const SUCCESS = 1;

    /**
     * @var BatchLogger
     */
    private $logger;

    /**
     * @var SfManager
     */
    private $sfMgr;

    public function __construct(BatchLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * company と突合し、company_idを更新する
     * @access public
     * @param array $orders
     * @return bool
     */
    public function updateCompanyId(array $orders): bool
    {
        $result = false;
        // 会社一覧取得
        $company = new Company();
        $companyList = $company->getCompanyList();

        // オーダーレコード毎の処理
        foreach ($orders as $order) {
            $companyId = null;

            if (count($companyList) == 0) {
                break;
            }
            // Account__r.Id と company.sf_office_idとの突合
            foreach ($companyList as &$item) {
                if ($order['Account__c'] == $item['sf_office_id']) {
                    // 会社マスタ（company）にsf_office_idが存在する
                    $companyId = $item['id'];
                    //SFの最新の値で会社マスタを更新
                    $data = $this->makeCompanyData($order);
                    $response = $company->updateSfOfficeId($companyId, $data);
                    if ($response == self::SUCCESS) {
                        $this->logger->info("{$order['Id']}: [SUCCESS] UPDATE company.id = {$companyId}");
                        $item['company_name'] = $order['Field1__c'];
                        $result = true;
                    } else {
                        $this->logger->info("{$order['Id']}: [FAILED] UPDATE company.id = {$companyId}");
                    }
                    break;
                } elseif ($order['Field1__c'] == $item['company_name']) {
                    // 会社マスタ（company）に同じ会社名が存在する
                    $companyId = $item['id'];
                    break;
                }
            }

            // 会社マスタに sf_office_idが存在しない
            if (empty($companyId)) {
                // 会社名 Account__r.Field1__cと company.company_name を突合し、存在しない場合
                // 新規にINSERT
                $data = $this->makeCompanyData($order);
                $companyId = $company->insertSfOfficeId($data);
                if (!empty($companyId)) {
                    // companyの配列にも追加
                    $companyList[] = [
                        'id'           => $companyId,
                        'company_name' => $order['Field1__c'],
                        'sf_office_id' => $order['Account__c'],
                    ];
                    $this->logger->info("{$order['Id']}: [SUCCESS] INSERT company {$companyId}");
                    $result = true;
                } else {
                    $this->logger->info("{$order['Id']}: [FAILED] INSERT company");
                }
            }

            // 求人テーブル更新処理
            if (!empty($companyId)) {
                $this->updateWoaOpportunity($order['Id'], $companyId);
            }
        }

        return $result;
    }

    /**
     * company と突合し、woa_opportunity.company_id等を正しくする
     * @access public
     * @param array $orders
     * @return bool
     */
    public function cleanData(array $orders): bool
    {
        $result = false;

        $company = new Company();
        $companyList = $company->getCompanyList();

        // オーダーレコード毎の処理
        foreach ($orders as $order) {
            // 存在確認用
            $existFlag = false;
            $companyId = null;

            if (count($companyList) == 0) {
                break;
            }

            // Account__r.Field1__c と company.company_name との突合
            foreach ($companyList as $item) {
                // 突合して合致する場合、かつ「sf_office_id（事業所セールスフォースID）」がNULLの場合
                if ($order['Field1__c'] == $item['company_name']) {
                    // Account__c を company.sf_office_id に格納
                    $companyId = $item['id'];
                    if (!empty($item['sf_office_id'])) {
                        break;
                    }
                    $data = [
                        'sf_office_id' => $order['Account__c'],
                    ];
                    $response = $company->updateSfOfficeId($companyId, $data);
                    if ($response == self::SUCCESS) {
                        $this->logger->info("{$order['Id']}: [SUCCESS] UPDATE company.id = {$companyId}");
                        $result = true;
                    } else {
                        $this->logger->info("{$order['Id']}: [FAILED] UPDATE company.id = {$companyId}");
                    }
                    break;
                }
            }

            // 求人テーブル更新処理
            if (!empty($companyId)) {
                $this->updateWoaOpportunity($order['Id'], $companyId);
            }
        }

        return $result;
    }

    /**
     * 会社名が重複しているデータの論理削除（一番idの小さいものは残す）
     * @access public
     * @return boolean
     */
    public function deleteDuplicateCompany(): bool
    {
        $result = true;

        // 重複しているデータの取得
        $company = new Company();
        $list = $company->getDuplicateList();
        if (count($list) == 0) {
            $this->logger->info("重複している company データなし");

            return $result;
        }

        foreach ($list as $item) {
            $id = Arr::get($item, 'min_id');
            $name = Arr::get($item, 'company_name');
            if (empty($id) || empty($name)) {
                continue;
            }
            // 重複データの削除 ※一番小さい company.id は残す
            $response = $company->deleteDuplicateName($name, $id);
            if ($response >= self::SUCCESS) {
                $this->logger->info("[SUCCESS] REMOVE COMPANY {$name} without {$id} ({$response})");
            } else {
                $this->logger->info("[FAILED] REMOVE COMPANY {$name} without {$id}");
            }
        }

        return $result;
    }

    /**
     * woa_opportunity.company_idの更新(共通処理)
     * @access private
     * @param string $sfOrderid
     * @param integer $companyId
     */
    private function updateWoaOpportunity(string $sfOrderId, int $companyId)
    {
        $woaOpportunity = new WoaOpportunity();
        $response = $woaOpportunity->updateCompanyId($sfOrderId, $companyId);
        if ($response == self::SUCCESS) {
            $this->logger->info("{$sfOrderId}: [SUCCESS] UPDATE woa_opportunity.company_id {$companyId}");
        } else {
            $this->logger->info("{$sfOrderId}: [INFO] NO DATA in woa_opportunity({$companyId})");
        }
    }

    /**
     * INSERT/UPDATEする配列を作成
     * @access private
     * @param array $order
     * @return array
     */
    private function makeCompanyData(array $order): array
    {
        $data = [];

        $address = $order['BillingState'] . $order['BillingCity'] . $order['BillingStreet'];
        $data = [
            'company_name' => $order['Field1__c'],
            'address'      => $address,
            'sf_office_id' => $order['Account__c'],
        ];

        return $data;
    }
}
