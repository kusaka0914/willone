<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Managers\JobPostingManager;
use App\Managers\SelectBoxManager;
use App\Managers\SfCustomerManager;
use App\Managers\WoaOpportunityManager;
use App\Model\Company;
use App\Model\MasterAddr1Mst;
use App\Model\MasterAddr2Mst;
use App\Model\WoaAreaConditionAggregate;
use App\UseCases\Searches\MakeJobDetailAreaConditionAggregateUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Jenssegers\Agent\Agent;

class JobDetailController extends Controller
{
    // パンくず用
    private $breadCrumb = [];

    // agent
    private $agent;
    // JobPostingManager
    private $jobPostingMgr;
    // woa_opportunity
    private $woaOpportunityMgr;
    // master_addr1_mst
    private $MasterAddr1Mst;
    // master_addr2_mst
    private $masterAddr2Mst;
    // woa_area_condition_aggregate
    private $woaAreaConditionAggregate;

    private $job; // ← 職種グループの決定後に不要になる

    public function __construct(Agent $agent, WoaOpportunityManager $woaOpportunityMgr, JobPostingManager $jobPostingMgr, MasterAddr2Mst $masterAddr2Mst, MasterAddr1Mst $masterAddr1Mst, Company $company, WoaAreaConditionAggregate $woaAreaConditionAggregate)
    {
        $this->agent = $agent;
        $this->woaOpportunityMgr = $woaOpportunityMgr;
        $this->jobPostingMgr = $jobPostingMgr;
        $this->masterAddr1Mst = $masterAddr1Mst;
        $this->masterAddr2Mst = $masterAddr2Mst;
        $this->company = $company;
        $this->woaAreaConditionAggregate = $woaAreaConditionAggregate;

        // パンくず共通
        $this->breadCrumb = config('ini.BASE_BREAD_CRUMB');
    }

    /**
     * 求人詳細（woa_opportunity）
     * @access public
     * @param Request $request
     * @param int $id
     * @param MakeJobDetailAreaConditionAggregateUseCase $makeJobDetailAreaConditionAggregateUseCase
     * @return \Illuminate\View\View
     */
    public function index(Request $request, int $id, MakeJobDetailAreaConditionAggregateUseCase $makeJobDetailAreaConditionAggregateUseCase)
    {
        $data = [];

        $jobData = $this->woaOpportunityMgr->getJobDetailByJobId($id);
        if (!$jobData) {
            abort(404);
        }
        $data['job_data'] = $jobData;

        // 会社情報取得
        $data['company'] = $this->company->getCompanyById($jobData->company_id);
        if (!empty($data['company']->company_name) && $jobData->publicly_flag == 0) {
            // 非公開求人の場合、会社名を非公開にする
            $data['company']->company_name = $jobData->company_name;
        }

        $data['state_data'] = $this->masterAddr2Mst->getAddr2ListByAddr1Id($jobData->addr1);

        // 画像ファイル名の取得
        $data['job_image'] = $this->woaOpportunityMgr->getJobImage($jobData->job_id, $jobData->company_id);

        $data['pref_name'] = $jobData->addr1_name;
        $data['pref_roma'] = $jobData->addr1_roma;
        $data['state_name'] = $jobData->addr2_name;
        $data['state_roma'] = $jobData->addr2_roma;

        // 市区町村リスト
        $data['jobstatelist'] = $this->masterAddr2Mst->getJobCountList($jobData->addr1);

        // 職種リスト
        $data['jobtypelist'] = $this->woaOpportunityMgr->getJobTypeList($jobData->addr1);

        // JobPostingの作成
        $jobPostingFlag = $this->jobPostingMgr->getJobpostingDisplayFlag($jobData, $data['company']);
        if ($jobPostingFlag) {
            $data['jobposting'] = $this->jobPostingMgr->getJobposting($jobData, $data['company']);
        }

        // パンくず
        $this->breadCrumb[] = ['label' => $jobData->office_name, 'url' => ""];
        $data['bread_crumb'] = $this->breadCrumb;

        // title、description
        $editJobTypeName = getJobTypeGroupNameFromJobType($jobData->job_type);
        $data['headtitle'] = "{$jobData->office_name} {$editJobTypeName} の求人・転職（{$jobData->addr2_name})｜ウィルワン";
        $data['headdescription'] = "{$jobData->office_name}の求人情報や就職サービスは【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";
        $types = explode(',', $editJobTypeName);
        $jobTypeId = '';
        foreach (config('ini.JOB_TYPE_GROUP') as $typeName => $one) {
            if ($one['name'] == $types[0]) {
                $jobTypeId = $typeName;
                $data['type_name'] = $types[0];
                break;
            } elseif (preg_match('/あん摩/', $types[0])) {
                // マッサージが小文字だったり指圧師でなかったりするため
                $jobTypeId = 'ammamassajishiatsushi';
                $data['type_name'] = $types[0];
                break;
            }
        }

        if (!empty($jobTypeId)) {
            // 市区町村の絞り込み結果データ取得
            $params = ['addr1' => $jobData->addr1, 'job_type' => $jobTypeId];
            // usecase
            $data['aggregate_data'] = $makeJobDetailAreaConditionAggregateUseCase($params);

            // 近隣都道府県の件数取得
            $addr1 = $this->masterAddr1Mst->getAddr1ById($jobData->addr1);
            $params = ['addr0_id' => $addr1->addr0_id, 'job_type' => $jobTypeId, 'conditions' => 'pref'];
            $data['area_data'] = $this->woaAreaConditionAggregate->getAreaData($params);
            $data['type_roma'] = $jobTypeId;
        }
        $digsSfCUstomer = (new SfCustomerManager)->getSfCustomer(sfIdCheckAndExtract($request->input('user')));
        if ($digsSfCUstomer) {
            $selectBoxManager = new SelectBoxManager();
            $data['user'] = $request->input('user');
            $data['req_emp_type_list'] = $selectBoxManager->sysEmpTypeMstSb();
            $data['req_date_list'] = $selectBoxManager->sysReqdateMstSb();
            $data['t'] = config('ini.MODAL_DIGS_TEMPLATE_NO');
            $data['digsSfCUstomer'] = $digsSfCUstomer;
            $data['action'] = $request->input('action');
        }

        if ($this->agent->isMobile()) {
            return view('sp.job.detail', $data);
        } else {
            return view('pc.job.detail', $data);
        }
    }
}
