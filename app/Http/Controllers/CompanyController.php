<?php
namespace App\Http\Controllers;

use App\Managers\WoaOpportunityManager;
use App\Model\Company;
use Jenssegers\Agent\Agent;

class CompanyController extends Controller
{
    // company
    private $company;
    // woa_opportunity
    private $woaOpportunityMgr;

    private const PRIVATE_COMPANY_NAME = '非公開';

    public function __construct(Company $company, WoaOpportunityManager $woaOpportunityMgr)
    {
        $this->company = $company;
        $this->woaOpportunityMgr = $woaOpportunityMgr;

        // パンくず共通
        $this->breadCrumb = config('ini.BASE_BREAD_CRUMB');
    }

    /**
     * 運営会社の求人掲載一覧
     * @access public
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function detail(int $id)
    {
        $data['company'] = $this->company->getCompanyById($id);
        if (!$data['company']) {
            abort(404);
        }

        $data['company_job'] = $this->woaOpportunityMgr->getJobListByCompanyId($id);
        // 運営会社名が非公開または、事業所がない場合はnoindex
        if ($data['company_job']->isEmpty() || $data['company']->company_name == self::PRIVATE_COMPANY_NAME) {
            $data['noindex'] = true;
        }

        // パンくず
        $this->breadCrumb[] = ['label' => $data['company']->company_name, 'url' => ''];
        $data['bread_crumb'] = $this->breadCrumb;

        $data['headtitle'] = $data['company']->company_name . "の柔道整復師、鍼灸師、マッサージ師の各施設求人一覧｜ウィルワン";

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.company.detail', $data);
        } else {
            return view('pc.company.detail', $data);
        }
    }
}
