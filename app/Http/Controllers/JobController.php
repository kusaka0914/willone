<?php
namespace App\Http\Controllers;

use App\Managers\AreaManager;
use App\Managers\WoaOpportunityManager;
use App\Model\MasterAddr1Mst;
use App\Model\MasterAddr2Mst;
use App\Model\ParameterMaster;
use App\Model\WoaAreaConditionAggregate;
use App\Model\WoaOpportunity;
use App\UseCases\Breadcrumbs\MakeBreadcrumbsTextWithPageUseCase;
use App\UseCases\Searches\MakeJobAreaConditionAggregateUseCase;
use App\UseCases\Searches\MakeJobSearchLinksUseCase;
use App\UseCases\Sidebars\MakePopularSearchUrlUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Jenssegers\Agent\Agent;

class JobController extends Controller
{
    // jobsearch()
    private const NEW_LIST = 'NewList';
    private const BLANK_LIST = 'BlankList';
    private const JOB_SEARCH = 'JobSearch';

    // areanewlist()
    private const AREA_NEW_LIST = 'AreaNewList';
    private const AREA_BLANK_LIST = 'AreaBlankList';

    // jobnewlist()
    private const JOB_NEW_LIST = 'JobNewList';
    private const JOB_BLANK_LIST = 'JobBlankList';

    // areastateselect()
    private const AREA_STATE_SELECT = 'AreaStateSelect';
    private const AREA_STATE_SELECT_EKICHIKA5 = 'AreaStateSelectEkichika5';

    // jobareaselect()
    private const JOB_AREA_SELECT = 'JobAreaSelect';
    private const JOB_AREA_SELECT_EKICHIKA5 = 'JobAreaSelectEkichika5';

    // jobareastateselect()
    private const JOB_AREA_STATE_SELECT = 'JobAreaStateSelect';
    private const JOB_AREA_STATE_SELECT_EKICHIKA5 = 'JobAreaStateSelectEkichika5';

    // 駅から徒歩5分以内 テキスト
    private const EKICHIKA5_TEXT = '駅から徒歩5分以内';

    // パンくず用
    private $breadCrumb = [];

    // agent
    private $agent;
    // woa_opportunity
    private $woaOpportunity;
    // master_addr1_mst
    private $masterAddr1Mst;
    // master_addr2_mst
    private $masterAddr2Mst;
    // WoaOpportunityManager
    private $woaOpportunityMgr;
    //parameter_master
    private $parameter;
    // WoaAreaConditionAggregate
    private $woaAreaConditionAggregate;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Agent $agent, ParameterMaster $parameter, WoaOpportunity $woaOpportunity, MasterAddr1Mst $masterAddr1Mst, MasterAddr2Mst $masterAddr2Mst, WoaOpportunityManager $woaOpportunityMgr, WoaAreaConditionAggregate $woaAreaConditionAggregate)
    {
        $this->agent = $agent;
        $this->woaOpportunity = $woaOpportunity;
        $this->masterAddr1Mst = $masterAddr1Mst;
        $this->masterAddr2Mst = $masterAddr2Mst;
        $this->woaOpportunityMgr = $woaOpportunityMgr;
        $this->woaAreaConditionAggregate = $woaAreaConditionAggregate;

        $this->parameter = $parameter;

        // パンくず共通
        $this->breadCrumb = config('ini.BASE_BREAD_CRUMB');
    }

    /**
     * Show the application dashboard.
     * @access public
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function area(Request $request)
    {
        $data['headtitle'] = "エリアから柔道整復師、鍼灸師、マッサージ師の求人を探す｜ウィルワン";

        $data['headdescription'] = "柔道整復師・柔整師の求人情報をエリアから探すなら【Willone(ウィルワン)】業界専門の就職支援のプロフェッショナルが完全無料でサポート!希望条件に沿って今以上の高収入や好待遇を叶えるため非公開求人を含め厳選します。理想のお仕事を一緒に探しましょう。";

        // パンくず
        $this->breadCrumb[] = ['label' => 'エリアから探す', 'url' => ''];
        $data['bread_crumb'] = $this->breadCrumb;

        // 関東エリア
        $data['kantou'] = $this->masterAddr1Mst->getArea(config('ini.AREA_KANTO'));
        // その他の地域
        $data['other_chiiki'] = $this->masterAddr1Mst->getListPrefecture(config('ini.AREA_OTHER'));
        // 人気エリア
        $data['popCities'] = config('ini.AREA_POP');
        // new_job, blank_ok用データ
        $data['new_job'] = $this->woaOpportunityMgr->newjob();

        $data['syokusyu_text'] = $this->parameter->getSyokusyuText();

        if ($this->agent->isMobile()) {
            return view('sp.job.areatop', $data);
        } else {
            return view('pc.job.areatop', $data);
        }
    }

    /**
     * 職種から探すTOPページ (/woa/job)
     * @access public
     * @return \Illuminate\View\View
     */
    public function type()
    {
        $data['headtitle'] = "職種から柔道整復師、鍼灸師、マッサージ師の求人を探す｜ウィルワン";
        $data['headdescription'] = "柔道整復師・柔整師の求人情報を職種から探すなら【Willone(ウィルワン)】業界専門の就職支援のプロフェッショナルが完全無料でサポート!希望条件に沿って今以上の高収入や好待遇を叶えるため非公開求人を含め厳選します。理想のお仕事を一緒に探しましょう。";

        // パンくず
        $this->breadCrumb[] = ['label' => '職種から探す', 'url' => ""];
        $data['bread_crumb'] = $this->breadCrumb;

        $data['syokugyou'] = config('ini.JOB_TYPE_GROUP');

        // new_job, blank_ok用データ
        $data['new_job'] = $this->woaOpportunityMgr->newjob();

        $data['syokusyu_text'] = $this->parameter->getSyokusyuText();

        if ($this->agent->isMobile()) {
            return view('sp.job.jobtypetop', $data);
        } else {
            return view('pc.job.jobtypetop', $data);
        }
    }

    /**
     * 職種から探す (/woa/job/xxxxx)
     * @access public
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function typeselect(string $id)
    {
        $jobType = $this->getJobTypeGroup($id);
        if (!$jobType) {
            abort(404);
        }
        $data['type'] = $jobType['syokusyu_text'];
        $data['type_roma'] = $id;
        $data['title'] = $jobType['name'];
        // データ検索
        $conditions = [
            'job_type_group' => $id,
        ];
        $data['job_count'] = $this->woaOpportunity->jobSearchCount($conditions);

        // エリア毎の都道府県リストを取得
        if ($this->agent->isMobile()) {
            $data['spPrefecturesList'] = (new AreaManager)->getListPrefecture();
        } else {
            $data['prefecturesList'] = (new AreaManager)->getAreaPrefList();
        }

        // パンくず
        $baseUrl = $this->breadCrumb[0]['url'];
        $this->breadCrumb[] = ['label' => '職種から探す', 'url' => "{$baseUrl}/job/"];
        $this->breadCrumb[] = ['label' => "{$data['title']}トップ", 'url' => ""];
        $data['bread_crumb'] = $this->breadCrumb;

        // 人気エリア
        $data['popCities'] = config('ini.AREA_POP');
        // new_job, blank_ok用データ
        $data['new_job'] = $this->woaOpportunityMgr->newjob(null, null, $data['type_roma']);

        $data['syokusyu_text'] = $this->parameter->getSyokusyuText([$data['type']]);

        $data['headtitle'] = "{$data['title']}の求人・転職｜ウィルワン";
        $data['headdescription'] = "{$data['title']}の求人情報や就職サービスは【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";

        if ($this->agent->isMobile()) {
            return view('sp.job.typeselect', $data);
        } else {
            return view('pc.job.typeselect', $data);
        }
    }

    /**
     * 新着求人一覧(woa/job/xxxx/new)
     * @access public
     * @param Request $request
     * @param string $id
     * @param int|null $page
     * @return \Illuminate\View\View
     */
    public function jobnewlist(Request $request, string $id, int $page = null)
    {
        $jobType = $this->getJobTypeGroup($id);
        if (!$jobType) {
            abort(404);
        }
        $data['type_roma'] = $id;
        $data['type_name'] = $jobType['name'];

        $routeName = \Route::currentRouteName();
        $data['url_name'] = $routeName;

        $noindex = true;
        if ($routeName == self::JOB_NEW_LIST) {
            $label = "新着";
            $noindex = false;
        } elseif ($routeName == self::JOB_BLANK_LIST) {
            $label = "ブランクOK";
        }

        list($data['page'], $offset) = $this->getOffset($page);

        // データ検索
        $conditions = [
            'job_type_group' => $id,
        ];
        $count = $this->woaOpportunity->jobSearchCount($conditions);
        $jobData = collect();
        $prJobData = collect();
        if ($count > 0) {
            $conditions['limit'] = config('ini.DEFAULT_OFFSET');
            $conditions['offset'] = $offset;
            $jobData = $this->woaOpportunityMgr->JobSearch($conditions);
            $prJobData = $this->woaOpportunityMgr->prJobSearch($conditions);
        }
        $noindexData = [
            'id'       => $id,
            'job_data' => $jobData,
            'noindex'  => $noindex,
        ];
        $data['noindex'] = setNoindexJob($noindexData);
        $data['job_data'] = $jobData;
        $data['job_count'] = $count;
        $data['pr_job_data'] = $prJobData;

        $data['title'] = "{$data['type_name']}の{$label}";

        // パンくず
        $baseUrl = $this->breadCrumb[0]['url'];
        $this->breadCrumb[] = ['label' => '職種から探す', 'url' => "{$baseUrl}/job/"];
        $this->breadCrumb[] = ['label' => "{$data['type_name']}トップ", 'url' => "{$baseUrl}/job/{$data['type_roma']}"];
        $this->breadCrumb[] = ['label' => (new MakeBreadcrumbsTextWithPageUseCase())("{$data['type_name']}の{$label}求人", $page), 'url' => ""];
        $data['bread_crumb'] = $this->breadCrumb;

        // その他表示
        $data['headtitle'] = "{$data['title']}求人";
        if ($data['page'] > 1) {
            $data['headtitle'] .= "({$data['page']}ページ目)";
        }
        $data['headtitle'] .= "｜ウィルワン";

        if ($this->agent->isMobile()) {
            return view('sp.job.list', $data);
        } else {
            return view('pc.job.list', $data);
        }
    }

    /**
     * 都道府県の求人
     * @access public
     * @param string $addr1Roma
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function areaselect(string $addr1Roma)
    {
        // 都道府県データの取得
        $addr1 = $this->masterAddr1Mst->getAddr1ByRoma($addr1Roma);
        if (empty($addr1)) {
            abort(404);
        }
        $data['pref_name'] = $addr1->addr1;
        $data['pref_roma'] = $addr1Roma;
        $data['pref_id'] = $addr1->id;

        // フリーワードを取得し、必要なパラメータを作成する
        $retFreewordParam = $this->makeFreewordParam(null);
        // フリーワード用
        $data['freeword'] = $retFreewordParam['freeword'];
        // ページネーション用
        $data['query_string'] = $retFreewordParam['queryString'];
        // キーワードの空白を半角に統一し、半角スペースでexplodeした値を作成する
        $arrFreeword = $this->convertFreeword($data['freeword']);

        // new_job, blank_ok用データ
        $data['new_job'] = $this->woaOpportunityMgr->newjob($addr1->id, null, null, $arrFreeword);

        // 市区町村のリスト（プルダウン用）
        $data['state_data'] = $this->masterAddr2Mst->getAddr2ListByAddr1Id($addr1->id);

        // パンくず
        $baseUrl = $this->breadCrumb[0]['url'];
        $this->breadCrumb[] = ['label' => 'エリアから探す', 'url' => "{$baseUrl}/area"];
        $this->breadCrumb[] = ['label' => $data['pref_name'] . "の治療家求人", 'url' => ''];
        $data['bread_crumb'] = $this->breadCrumb;

        // その他表示
        $data['title'] = $data['pref_name'];
        $data['syokusyu_text'] = $this->parameter->getSyokusyuText();
        $data['headtitle'] = $data['pref_name'] . "の柔道整復師・鍼灸師・マッサージ師求人・転職を検索｜ウィルワン";
        $data['headdescription'] = $data['pref_name'] . "の柔道整復師・柔整師の求人情報や就職サービスは【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";
        $data['noindex'] = 0;

        // 最寄りの市区町村データ取得
        $data['search_near_cities'] = $this->getJobSearchLinks(null, $addr1->id, null, $arrFreeword);

        if ($this->agent->isMobile()) {
            return view('sp.job.areaselect', $data);
        } else {
            return view('pc.job.areaselect', $data);
        }
    }

    /**
     * 都道府県新着求人一覧
     * @access public
     * @param Request $request
     * @param string $addr1Roma
     * @param int|null $page
     * @return \Illuminate\View\View
     */
    public function areanewlist(Request $request, string $addr1Roma, int $page = null)
    {
        $data['pref_roma'] = $addr1Roma;

        // 都道府県データの取得
        $addr1 = $this->masterAddr1Mst->getAddr1ByRoma($addr1Roma);
        if (empty($addr1)) {
            abort(404);
        }
        $data['pref_name'] = $addr1->addr1;

        list($data['page'], $offset) = $this->getOffset($page);

        $conditions = [
            'addr1_id' => $addr1->id,
        ];
        $count = $this->woaOpportunity->jobSearchCount($conditions);
        $jobData = collect();
        $prJobData = collect();
        if ($count > 0) {
            $conditions['limit'] = config('ini.DEFAULT_OFFSET');
            $conditions['offset'] = $offset;
            $jobData = $this->woaOpportunityMgr->JobSearch($conditions);
            $prJobData = $this->woaOpportunityMgr->prJobSearch($conditions);
        }
        $routeName = \Route::currentRouteName();
        $data['url_name'] = $routeName;
        $noindex = false;
        if ($routeName == self::AREA_NEW_LIST) {
            $label = "新着";
        } elseif ($routeName == self::AREA_BLANK_LIST) {
            $label = "ブランクOK";
            $noindex = true;
        }
        $noindexData = [
            'id'       => '',
            'job_data' => $jobData,
            'noindex'  => $noindex,
        ];
        $data['noindex'] = setNoindexJob($noindexData);

        $data['job_data'] = $jobData;
        $data['job_count'] = $count;
        $data['pr_job_data'] = $prJobData;

        $data['pagenation_flg'] = 1;

        // パンくず
        $baseUrl = $this->breadCrumb[0]['url'];
        $this->breadCrumb[] = ['label' => 'エリアから探す', 'url' => "{$baseUrl}/area"];
        $this->breadCrumb[] = ['label' => $data['pref_name'] . "の治療家求人", 'url' => "{$baseUrl}/area/{$data['pref_roma']}"];
        $this->breadCrumb[] = ['label' => (new MakeBreadcrumbsTextWithPageUseCase())($data['pref_name'] . "の{$label}求人一覧", $page), 'url' => ''];
        $data['bread_crumb'] = $this->breadCrumb;

        // その他表示
        $data['title'] = $data['pref_name'] . $label;
        $data['headtitle'] = "{$data['pref_name']} {$label} 柔道整復師、鍼灸師、マッサージ師の求人";
        if ($data['page'] > 1) {
            $data['headtitle'] .= "({$data['page']}ページ目)";
        }
        $data['headtitle'] .= "｜ウィルワン";
        $data['privateJobsPrefLabel'] = $this->getPrivateJobsPrefLabel($addr1->id, $data['pref_name']);

        if ($this->agent->isMobile()) {
            return view('sp.job.list', $data);
        } else {
            return view('pc.job.list', $data);
        }
    }

    /**
     * 市区町村求人一覧 or 市区町村求人一覧+駅から徒歩5分以内
     * @access public
     * @param Request $request
     * @param string $addr1Roma
     * @param string $addr2Roma
     * @param int|null $page
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function areastateselect(Request $request, string $addr1Roma, string $addr2Roma, int $page = null)
    {
        $addr2 = $this->masterAddr2Mst->getAddr2ByRoma($addr1Roma, $addr2Roma);
        if (empty($addr2)) {
            abort(404);
        }
        $data['state_data'] = $this->masterAddr2Mst->getAddr2ListByAddr1Id($addr2->addr1_id);

        $data['pref'] = $addr2->addr1_id;
        $data['state'] = $addr2->addr2_id;
        $data['pref_roma'] = $addr1Roma;
        $data['state_roma'] = $addr2Roma;

        // フリーワードを取得し、必要なパラメータを作成する
        $retFreewordParam = $this->makeFreewordParam($request->input('freeword'));
        // フリーワード用
        $data['freeword'] = $retFreewordParam['freeword'];
        // ページネーション用
        $data['query_string'] = $retFreewordParam['queryString'];

        // キーワードの空白を半角に統一し、半角スペースでexplodeした値を作成する
        $arrFreeword = $this->convertFreeword($data['freeword']);

        list($data['page'], $offset) = $this->getOffset($page);

        // 検索条件: 駅から徒歩5分以内フラグ
        $isEkichika5 = $this->isEkichika5();

        $conditions = [
            'addr1_id'     => $addr2->addr1_id,
            'addr2_id'     => $addr2->addr2_id,
            'is_ekichika5' => $isEkichika5,
            'freeword'     => $arrFreeword,
        ];

        $count = $this->woaOpportunity->jobSearchCount($conditions);
        $jobData = collect();
        $prJobData = collect();
        if ($count > 0) {
            $conditions['limit'] = config('ini.DEFAULT_OFFSET');
            $conditions['offset'] = $offset;
            $jobData = $this->woaOpportunityMgr->JobSearch($conditions);
            $prJobData = $this->woaOpportunityMgr->prJobSearch($conditions);
        }

        $noindexData = [
            'id'       => '',
            'job_data' => $jobData,
            'noindex'  => false,
        ];
        $data['noindex'] = setNoindexJob($noindexData);

        $data['job_data'] = $jobData;
        $data['job_count'] = $count;
        $data['pr_job_data'] = $prJobData;

        $data['pref_name'] = $addr2->addr1;
        $data['state_name'] = $addr2->addr2;

        // 市区町村リスト
        $data['jobstatelist'] = $this->masterAddr2Mst->getJobCountList($addr2->addr1_id);

        // 職種リスト
        $data['jobtypelist'] = $this->woaOpportunityMgr->getJobTypeList($addr2->addr1_id);

        $data['url_name'] = $isEkichika5 ? self::AREA_STATE_SELECT_EKICHIKA5 : self::AREA_STATE_SELECT;

        // パンくず
        $baseUrl = $this->breadCrumb[0]['url'];
        $this->breadCrumb[] = ['label' => 'エリアから探す', 'url' => "{$baseUrl}/area"];
        $this->breadCrumb[] = ['label' => $data['pref_name'] . "の治療家求人", 'url' => "{$baseUrl}/area/{$data['pref_roma']}"];
        if ($isEkichika5) {
            $this->breadCrumb[] = [
                'label' => $data['state_name'] . 'の治療家求人',
                'url'   => route(self::AREA_STATE_SELECT, ['pref' => $data['pref_roma'], 'state' => $data['state_roma']]),
            ];
            $this->breadCrumb[] = [
                'label' => (new MakeBreadcrumbsTextWithPageUseCase())(self::EKICHIKA5_TEXT, $page),
                'url'   => '',
            ];
        } else {
            $this->breadCrumb[] = [
                'label' => (new MakeBreadcrumbsTextWithPageUseCase())($data['state_name'] . "の治療家求人", $page),
                'url'   => '',
            ];
        }
        $data['bread_crumb'] = $this->breadCrumb;

        // その他表示
        $ekichika5TitleText = $this->getEkichika5TitleText($isEkichika5);
        $data['title'] = $data['pref_name'] . $data['state_name'] . "の" . $ekichika5TitleText;
        $data['pagenation_flg'] = 1;
        $headTitle = $data['headtitle'] = "{$data['state_name']}({$data['pref_name']})の{$ekichika5TitleText}柔道整復師・鍼灸師・マッサージ師の求人";
        if ($data['page'] > 1) {
            $data['headtitle'] .= "({$data['page']}ページ目)";
        }
        $data['headtitle'] .= "｜ウィルワン";
        if ($isEkichika5) {
            $data['headdescription'] = $this->getHeadDescriptionDate() . "{$headTitle}{$data['job_count']}件を掲載中｜ウィルワン";
        } else {
            $data['headdescription'] = $data['pref_name'] . $data['state_name'] . "の柔道整復師・柔整師の求人情報や就職サービスは【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";
        }
        $data['privateJobsPrefLabel'] = $this->getPrivateJobsPrefLabel($data['pref'], $data['pref_name'], $data['state_name']);

        // sideberリンク
        if (!$isEkichika5) {
            $data['sidebar_popular_search_ekichika5_url'] = (new MakePopularSearchUrlUseCase())(self::AREA_STATE_SELECT_EKICHIKA5, null, $data['pref_roma'], $data['state_roma']);
        }
        $data['ekichika'] = $isEkichika5;

        // 最寄りの市区町村データ取得
        $data['search_near_cities'] = $this->getJobSearchLinks(null, $addr2->addr1_id, $addr2->addr2_id, $arrFreeword);

        if ($this->agent->isMobile()) {
            return view('sp.job.list', $data);
        } else {
            return view('pc.job.list', $data);
        }
    }

    /**
     * パラメーターからSQLで使用するデータに変換
     * @param array $queryData
     * @return array
     */
    private function setQueryData(array $queryData): array
    {
        $employ = null;
        $business = null;
        $searchKey = null;
        $valueKey = null;
        // 検索条件: 駅から徒歩5分以内フラグ
        $isEkichika5 = $this->isEkichika5();
        // 検索条件: 雇用形態
        $employParam = !empty($queryData) && $queryData['key'] == 'employ' ? $queryData['type'] : null;
        if (!empty($employParam) && isset(config('ini.EMPLOY_TYPE')[$employParam]['query_key'])) {
            $employ = config('ini.EMPLOY_TYPE')[$employParam]['query_key'];
            $searchKey = config('ini.EMPLOY_TYPE')[$employParam]['search_key'];
            $valueKey = config('ini.EMPLOY_TYPE')[$employParam]['value'];
        }
        // 検索条件: 施設形態
        $businessParam = !empty($queryData) && $queryData['key'] == 'business' ? $queryData['type'] : null;
        if ($businessParam == 'others' && isset(config('ini.BUSINESS_TYPE')[$businessParam]['query_key'])) {
            $business = config('ini.BUSINESS_TYPE')[$businessParam]['query_key'];
            $business = explode(',', $business);
        } elseif (!empty($businessParam) && isset(config('ini.BUSINESS_TYPE')[$businessParam]['query_key'])) {
            $business = config('ini.BUSINESS_TYPE')[$businessParam]['query_key'];
        }
        if (!empty($businessParam)) {
            $searchKey = config('ini.BUSINESS_TYPE')[$businessParam]['search_key'];
            $valueKey = config('ini.BUSINESS_TYPE')[$businessParam]['value'];
        }
        if ($isEkichika5) {
            $searchKey = 'ekichika5';
            $valueKey = '駅ちか5分';
        }

        return [
            'isEkichika5' => $isEkichika5,
            'employ'      => $employ,
            'business'    => $business,
            'searchKey'   => $searchKey,
            'valueKey'    => $valueKey,
        ];
    }

    /**
     * 都道府県＋職種求人一覧 or 都道府県＋職種求人一覧+駅から徒歩5分以内、、勤務形態、施設形態
     * @access public
     * @param Request $request
     * @param MakeJobAreaConditionAggregateUseCase $makeJobAreaConditionAggregateUseCase
     * @param string $id
     * @param string $addr1Roma
     * @param int|null $page
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function byoption(Request $request, MakeJobAreaConditionAggregateUseCase $makeJobAreaConditionAggregateUseCase, string $id, string $addr1Roma, string $key, string $type, int $page = null)
    {
        $queryData = [
            'key'  => $key,
            'type' => $type,
        ];

        return $this->jobareaselect($request, $makeJobAreaConditionAggregateUseCase, $id, $addr1Roma, $page, $queryData);
    }

    /**
     * 都道府県＋市区町村＋職種求人一覧 or 都道府県＋市区町村＋職種求人一覧+駅から徒歩5分以内、勤務形態、施設形態
     * @access public
     * @param Request $request
     * @param MakeJobAreaConditionAggregateUseCase $makeJobAreaConditionAggregateUseCase
     * @param string $id
     * @param string $addr1Roma
     * @param string $addr2Roma
     * @param int|null $page
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function byoptionstate(Request $request, MakeJobAreaConditionAggregateUseCase $makeJobAreaConditionAggregateUseCase, string $id, string $addr1Roma, string $addr2Roma, string $key, string $type, int $page = null)
    {
        $queryData = [
            'key'  => $key,
            'type' => $type,
        ];

        return $this->jobareastateselect($request, $makeJobAreaConditionAggregateUseCase, $id, $addr1Roma, $addr2Roma, $page, $queryData);
    }

    /**
     * 都道府県＋職種求人一覧 or 都道府県＋職種求人一覧+駅から徒歩5分以内、勤務形態、施設形態
     * @access public
     * @param Request $request
     * @param MakeJobAreaConditionAggregateUseCase $makeJobAreaConditionAggregateUseCase
     * @param string $id
     * @param string $addr1Roma
     * @param int|null $page
     * @param array $queryData
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function jobareaselect(Request $request, MakeJobAreaConditionAggregateUseCase $makeJobAreaConditionAggregateUseCase, string $id, string $addr1Roma, int $page = null, array $queryData = [])
    {
        $where = $this->setQueryData($queryData);
        extract($where);
        $data['url_name'] = $isEkichika5 ? self::JOB_AREA_SELECT_EKICHIKA5 : self::JOB_AREA_SELECT;
        $jobType = $this->getJobTypeGroup($id);
        $addr1 = $this->masterAddr1Mst->getAddr1ByRoma($addr1Roma);
        if (!$jobType || !$addr1) {
            abort(404);
        }

        $data['type_roma'] = $id;
        $data['type_name'] = $jobType['name'];
        $data['pref_name'] = $addr1->addr1;
        $data['pref_roma'] = $addr1Roma;
        $data['pref'] = $addr1->id;

        // フリーワードを取得し、必要なパラメータを作成する
        $retFreewordParam = $this->makeFreewordParam($request->input('freeword'));
        // フリーワード用
        $data['freeword'] = $retFreewordParam['freeword'];
        // ページネーション用
        $data['query_string'] = $retFreewordParam['queryString'];

        // キーワードの空白を半角に統一し、半角スペースでexplodeした値を作成する
        $arrFreeword = $this->convertFreeword($data['freeword']);

        list($data['page'], $offset) = $this->getOffset($page);

        $data['state_data'] = $this->masterAddr2Mst->getAddr2ListByAddr1Id($addr1->id);

        // データ検索
        $conditions = [
            'addr1_id'       => $addr1->id,
            'job_type_group' => $id,
            'is_ekichika5'   => $isEkichika5,
            'freeword'       => $arrFreeword,
            'employ'         => $employ,
            'business'       => $business,
        ];

        $count = $this->woaOpportunity->jobSearchCount($conditions);
        $jobData = collect();
        $prJobData = collect();
        if ($count > 0) {
            $conditions['limit'] = config('ini.DEFAULT_OFFSET');
            $conditions['offset'] = $offset;
            $jobData = $this->woaOpportunityMgr->JobSearch($conditions);
            $prJobData = $this->woaOpportunityMgr->prJobSearch($conditions);
        }

        $noindexData = [
            'id'       => $id,
            'job_data' => $jobData,
            'noindex'  => false,
        ];
        $data['noindex'] = setNoindexJob($noindexData);

        $data['job_data'] = $jobData;
        $data['job_count'] = $count;
        $data['pr_job_data'] = $prJobData;

        $data['state'] = "";

        // パンくず設定
        $ekichika5TitleText = $this->getEkichika5TitleText($isEkichika5);
        $employTitleText = $this->getEmployTitleText($employ, $queryData);
        $businessTitleText = $this->getBusinessTitleText($business, $queryData);
        $breadCrumbTitle = "{$addr1->addr1}の{$data['type_name']}求人";
        $data['title'] = "{$addr1->addr1}の{$ekichika5TitleText}{$employTitleText}{$businessTitleText}{$data['type_name']}";

        // パンくず
        $baseUrl = $this->breadCrumb[0]['url'];
        $this->breadCrumb[] = ['label' => '職種から探す', 'url' => "{$baseUrl}/job/"];
        $this->breadCrumb[] = ['label' => "{$data['type_name']}トップ", 'url' => "{$baseUrl}/job/{$id}"];
        if ($employ || $business) {
            $this->breadCrumb[] = [
                'label' => $breadCrumbTitle,
                'url'   => route(self::JOB_AREA_SELECT, ['id' => $data['type_roma'], 'pref' => $data['pref_roma']]),
            ];
            if ($employ) {
                $this->breadCrumb[] = [
                    'label' => (new MakeBreadcrumbsTextWithPageUseCase())(config('ini.EMPLOY_TYPE')[$queryData['type']]['value'], $page) . 'の求人一覧',
                    'url'   => '',
                ];
            } else {
                $this->breadCrumb[] = [
                    'label' => (new MakeBreadcrumbsTextWithPageUseCase())(config('ini.BUSINESS_TYPE')[$queryData['type']]['value'], $page) . 'の求人一覧',
                    'url'   => '',
                ];
            }
        } elseif ($isEkichika5) {
            $this->breadCrumb[] = [
                'label' => $breadCrumbTitle,
                'url'   => route(self::JOB_AREA_SELECT, ['id' => $data['type_roma'], 'pref' => $data['pref_roma']]),
            ];
            $this->breadCrumb[] = [
                'label' => (new MakeBreadcrumbsTextWithPageUseCase())(self::EKICHIKA5_TEXT, $page),
                'url'   => '',
            ];
        } else {
            $this->breadCrumb[] = [
                'label' => (new MakeBreadcrumbsTextWithPageUseCase())($breadCrumbTitle, $page),
                'url'   => '',
            ];
        }
        $data['bread_crumb'] = $this->breadCrumb;

        // <link rel="prev/next">の表示
        $data['pagenation_flg'] = 1;

        $data['headtitle'] = "";
        if ($page > 1) {
            $data['headtitle'] = "【{$page}ページ目】";
        }
        $data['headtitle'] .= "{$data['title']} 求人・転職｜ウィルワン";
        if ($isEkichika5) {
            $data['headdescription'] = $this->getHeadDescriptionDate() . "{$data['title']} 求人・転職{$data['job_count']}件を掲載中｜ウィルワン";
        } else {
            $data['headdescription'] = "{$data['title']}の求人情報や就職サービスは【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";
        }

        // 市区町村リスト
        $data['jobstatelist'] = $this->masterAddr2Mst->getJobCountList($addr1->id, $id);

        // 職種リスト
        $data['jobtypelist'] = $this->woaOpportunityMgr->getJobTypeList($addr1->id);
        $data['privateJobsPrefLabel'] = $this->getPrivateJobsPrefLabel($data['pref'], $data['pref_name']);

        // sideberリンク
        if (!$isEkichika5) {
            $data['sidebar_popular_search_ekichika5_url'] = (new MakePopularSearchUrlUseCase())(self::JOB_AREA_SELECT_EKICHIKA5, $data['type_roma'], $data['pref_roma'], null);
        }

        // 最寄りの市区町村データ取得
        $data['search_near_cities'] = $this->getJobSearchLinks($id, $addr1->id, null, $arrFreeword);

        // 市区町村の絞り込み結果データ取得
        $params = ['addr1' => $addr1->id, 'job_type' => $id];
        $data['aggregate_data'] = $makeJobAreaConditionAggregateUseCase($params);

        // 注力エリアかどうか
        $areaData = [];
        $focusArea = config('ini.FOCUS_AREA');
        $focusAreaFlag = false;
        if (!empty($searchKey) && !empty($focusArea[$id]) && in_array($addr1->id, $focusArea[$id])) {
            $focusAreaFlag = true;
            // 注力エリアのデータを作成
            $params = ['addr0_id' => $addr1->addr0_id, 'job_type' => $id, 'search_key' => $searchKey];
            $areaData = $this->focusAreaData($params);
            // ラベルに使うタイトル文字
            $data['area_data_title_key'] = $valueKey;
            // 近隣のリンク
            $data['area_data_href'] = $queryData;
        } else {
            $params = ['addr0_id' => $addr1->addr0_id, 'job_type' => $id, 'conditions' => 'pref'];
            $areaData = $this->woaAreaConditionAggregate->getAreaData($params);
        }
        $data['focus_area_flag'] = $focusAreaFlag;
        $data['area_data'] = $areaData;
        $data['ekichika'] = $isEkichika5;

        if ($this->agent->isMobile()) {
            return view('sp.job.list', $data);
        } else {
            return view('pc.job.list', $data);
        }
    }

    /**
     * 注力エリアの場合の件数を取得
     * @param array $params
     * @return object
     */
    private function focusAreaData(array $params) :object
    {
        $areaData = [];
        $tmp = $this->woaAreaConditionAggregate->getAreaData($params);
        // 一つの配列要素に都道府県データを格納
        $grouped = $tmp->groupBy('addr1');
        foreach ($grouped as $pref) {
            $sum = 0;
            // 都道府県単位でまとめる
            foreach ($pref as $one) {
                // addr2 = nullは都道府県単位の合計データなので除外する
                if (!empty($one->addr2)) {
                    $sum += $one->sum;
                }
            }
            $one->sum = $sum;
            $areaData[] = $one;
        }
        $areaData = collect($areaData);
        return $areaData;
    }

    /**
     * 都道府県+市区町村＋職種求人一覧 or 都道府県+市区町村＋職種求人一覧+駅から徒歩5分以内、勤務形態、施設形態
     * @access public
     * @param Request $request
     * @param MakeJobAreaConditionAggregateUseCase $makeJobAreaConditionAggregateUseCase
     * @param string $id
     * @param string $addr1Roma
     * @param string $addr2Roma
     * @param int|null $page
     * @param array $queryData
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function jobareastateselect(Request $request, MakeJobAreaConditionAggregateUseCase $makeJobAreaConditionAggregateUseCase, string $id, string $addr1Roma, string $addr2Roma, int $page = null, array $queryData = [])
    {
        $where = $this->setQueryData($queryData);
        extract($where);

        $data['url_name'] = $isEkichika5 ? self::JOB_AREA_STATE_SELECT_EKICHIKA5 : self::JOB_AREA_STATE_SELECT;

        $jobType = $this->getJobTypeGroup($id);
        $addr2 = $this->masterAddr2Mst->getAddr2ByRoma($addr1Roma, $addr2Roma);
        if (!$jobType || empty($addr2)) {
            abort(404);
        }
        $data['type_roma'] = $id;
        $data['type_name'] = $jobType['name'];
        $data['pref_name'] = $addr2->addr1;
        $data['pref_roma'] = $addr1Roma;
        $data['state_name'] = $addr2->addr2;
        $data['state_roma'] = $addr2Roma;
        $data['pref'] = $addr2->addr1_id;
        $data['state'] = $addr2->addr2_id;

        // フリーワードを取得し、必要なパラメータを作成する
        $retFreewordParam = $this->makeFreewordParam($request->input('freeword'));
        // フリーワード用
        $data['freeword'] = $retFreewordParam['freeword'];
        // ページネーション用
        $data['query_string'] = $retFreewordParam['queryString'];

        // キーワードの空白を半角に統一し、半角スペースでexplodeした値を作成する
        $arrFreeword = $this->convertFreeword($data['freeword']);

        list($data['page'], $offset) = $this->getOffset($page);

        $data['state_data'] = $this->masterAddr2Mst->getAddr2ListByAddr1Id($addr2->addr1_id);

        // データ検索
        $conditions = [
            'addr1_id'       => $data['pref'],
            'addr2_id'       => $data['state'],
            'job_type_group' => $id,
            'is_ekichika5'   => $isEkichika5,
            'freeword'       => $arrFreeword,
            'employ'         => $employ,
            'business'       => $business,
        ];
        $count = $this->woaOpportunity->jobSearchCount($conditions);
        $jobData = collect();
        $prJobData = collect();
        if ($count > 0) {
            $conditions['limit'] = config('ini.DEFAULT_OFFSET');
            $conditions['offset'] = $offset;
            $jobData = $this->woaOpportunityMgr->JobSearch($conditions);
            $prJobData = $this->woaOpportunityMgr->prJobSearch($conditions);
        }
        $noindexData = [
            'id'       => $id,
            'job_data' => $jobData,
            'noindex'  => false,
        ];
        $data['noindex'] = setNoindexJob($noindexData);
        $data['job_data'] = $jobData;
        $data['job_count'] = $count;
        $data['pr_job_data'] = $prJobData;

        $ekichika5TitleText = $this->getEkichika5TitleText($isEkichika5);
        $breadCrumbTitle = "{$data['pref_name']}{$data['state_name']}の{$data['type_name']}";
        $data['title'] = "{$data['pref_name']}{$data['state_name']}の{$ekichika5TitleText}{$data['type_name']}";

        // パンくず
        $baseUrl = $this->breadCrumb[0]['url'];
        $this->breadCrumb[] = ['label' => '職種から探す', 'url' => "{$baseUrl}/job/"];
        $this->breadCrumb[] = ['label' => "{$data['type_name']}トップ", 'url' => "{$baseUrl}/job/{$id}"];
        $this->breadCrumb[] = ['label' => "{$data['pref_name']}の{$data['type_name']}求人", 'url' => "{$baseUrl}/job/{$id}/{$data['pref_roma']}"];
        if ($isEkichika5) {
            $this->breadCrumb[] = [
                'label' => $breadCrumbTitle,
                'url'   => route(self::JOB_AREA_STATE_SELECT, ['id' => $data['type_roma'], 'pref' => $data['pref_roma'], 'state' => $data['state_roma']]),
            ];
            $this->breadCrumb[] = [
                'label' => (new MakeBreadcrumbsTextWithPageUseCase())(self::EKICHIKA5_TEXT, $page),
                'url'   => '',
            ];
        } else {
            $this->breadCrumb[] = [
                'label' => (new MakeBreadcrumbsTextWithPageUseCase())($breadCrumbTitle, $page),
                'url'   => '',
            ];
        }

        $data['bread_crumb'] = $this->breadCrumb;

        // <link rel="prev/next">の表示
        $data['pagenation_flg'] = 1;

        $data['headtitle'] = "";
        if ($page > 1) {
            $data['headtitle'] = "【{$page}ページ目】";
        }
        $baseHeadTitle = "{$data['state_name']}({$data['pref_name']})の{$ekichika5TitleText}{$data['type_name']}";
        $data['headtitle'] .= "{$baseHeadTitle} 求人・転職｜ウィルワン";
        if ($isEkichika5) {
            $data['headdescription'] = $this->getHeadDescriptionDate() . "{$baseHeadTitle} 求人・転職{$data['job_count']}件を掲載中｜ウィルワン";
        } else {
            $data['headdescription'] = "{$data['title']}の求人情報や就職サービスは【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";
        }

        // 市区町村リスト
        $data['jobstatelist'] = $this->masterAddr2Mst->getJobCountList($data['pref'], $id);

        // 職種リスト(グループ待ち)
        $data['jobtypelist'] = $this->woaOpportunityMgr->getJobTypeList($data['pref']);
        $data['privateJobsPrefLabel'] = $this->getPrivateJobsPrefLabel($data['pref'], $data['pref_name'], $data['state_name']);

        // sideberリンク
        if (!$isEkichika5) {
            $data['sidebar_popular_search_ekichika5_url'] = (new MakePopularSearchUrlUseCase())(self::JOB_AREA_STATE_SELECT_EKICHIKA5, $data['type_roma'], $data['pref_roma'], $data['state_roma']);
        }

        // 最寄りの市区町村データ取得
        $data['search_near_cities'] = $this->getJobSearchLinks($id, $addr2->addr1_id, $addr2->addr2_id, $arrFreeword);

        // 市区町村の絞り込み結果データ取得
        $params = ['addr1' => $data['pref'], 'job_type' => $id];
        $data['aggregate_data'] = $makeJobAreaConditionAggregateUseCase($params);

        if ($this->agent->isMobile()) {
            return view('sp.job.list', $data);
        } else {
            return view('pc.job.list', $data);
        }
    }

    /**
     * 求人検索一覧
     * @access public
     * @param Request $request
     * @param MakeJobAreaConditionAggregateUseCase $makeJobAreaConditionAggregateUseCase
     * @param int|null $page
     * @return \Illuminate\View\View
     */
    public function jobsearch(Request $request, MakeJobAreaConditionAggregateUseCase $makeJobAreaConditionAggregateUseCase, int $page = null)
    {
        $routeName = \Route::currentRouteName();
        $data['url_name'] = $routeName;
        $data['pref'] = $request->input('pref');
        $data['state'] = $request->input('state');
        $data['type'] = $request->input('type');
        $data['freeword'] = $request->input('freeword');
        $data['state_data'] = [];
        $data['pref_name'] = "";
        $data['pref_roma'] = "";
        $data['state_name'] = "";
        $data['state_roma'] = "";

        // 都道府県情報の取得
        if (!empty($data['pref'])) {
            $addr1 = $this->masterAddr1Mst->getAddr1ById($data['pref']);
            if (!empty($addr1)) {
                $data['pref_roma'] = $addr1->addr1_roma;
                $data['pref_name'] = $addr1->addr1;
            }
            $data['state_data'] = $this->masterAddr2Mst->getAddr2ListByAddr1Id($data['pref']);
        }
        // 市区町村情報の取得
        if (!empty($data['state'])) {
            $addr2 = $this->masterAddr2Mst->getAddr2ById($data['state']);
            if (!empty($addr2)) {
                $data['state_roma'] = $addr2->addr2_roma;
                $data['state_name'] = $addr2->addr2;
            }
        }

        $noindex = true;
        // 文言のセット
        $breadcrumb = '';
        if ($routeName == self::NEW_LIST) {
            $routeTitle = "新着 ";
            $breadcrumb = "新着求人一覧";
            $title = "新着";
            $noindex = false;
        } elseif ($routeName == self::BLANK_LIST) {
            $routeTitle = "ブランクOK ";
            $breadcrumb = "ブランクOKの求人一覧";
            $title = "ブランクOKの";
        } elseif ($routeName == self::JOB_SEARCH) {
            $routeTitle = "条件から";
            $breadcrumb = "検索結果求人一覧";
            $title = "検索結果";
        } else {
            abort(404);
        }

        list($data['page'], $offset) = $this->getOffset($page);

        // タイトル：<title></title>
        $data['headtitle'] = "{$routeTitle} 柔道整復師、鍼灸師、マッサージ師の求人";
        if ($page > 1) {
            $data['headtitle'] .= "({$page}ページ目)";
        }
        $data['headtitle'] .= "｜ウィルワン";

        // h1文言
        $data['title'] = "{$data['pref_name']}{$data['state_name']}";
        if (!empty($data['title'])) {
            $data['title'] .= "の";
        }
        $data['title'] .= $title;

        // パンくず
        $this->breadCrumb[] = ['label' => (new MakeBreadcrumbsTextWithPageUseCase())($breadcrumb, $page), 'url' => ""];
        $data['bread_crumb'] = $this->breadCrumb;

        // 検索開始
        $map = [
            'pref'     => 'addr1_id',
            'state'    => 'addr2_id',
            'type'     => 'job_type_group',
            'freeword' => 'freeword',
        ];
        $conditions = [];
        $queryString = "";
        foreach ($map as $key => $column) {
            if (!empty($data[$key])) {
                if ($key == 'freeword') {
                    // キーワードの空白を半角に統一し、半角スペースでexplodeした値を作成する
                    $conditions[$column] = $this->convertFreeword($data['freeword']);
                } else {
                    $conditions[$column] = $data[$key];
                }
                $queryString .= "&{$key}={$data[$key]}";
            }
        }
        if (!empty($queryString)) {
            $queryString = preg_replace("/^&/", "?", $queryString);
        }

        // ページネーション用
        $data['query_string'] = $queryString;

        $count = $this->woaOpportunity->jobSearchCount($conditions);
        $jobData = collect();
        $prJobData = collect();
        if ($count > 0) {
            $conditions['limit'] = config('ini.DEFAULT_OFFSET');
            $conditions['offset'] = $offset;
            $jobData = $this->woaOpportunityMgr->JobSearch($conditions);
            $prJobData = $this->woaOpportunityMgr->prJobSearch($conditions);
        }
        $data['job_data'] = $jobData;
        $data['job_count'] = $count;
        $data['pr_job_data'] = $prJobData;
        $data['privateJobsPrefLabel'] = $this->getPrivateJobsPrefLabel($data['pref'], $data['pref_name'], $data['state_name']);
        $noindexData = [
            'id'       => '',
            'job_data' => $jobData,
            'noindex'  => $noindex,
        ];
        $data['noindex'] = setNoindexJob($noindexData);

        // 市区町村の絞り込み結果データ取得
        if (!empty($data['pref']) && !empty($data['type']) && !empty($data['pref_roma'])) {
            $params = ['addr1' => $data['pref'], 'job_type' => $data['type']];
            $data['aggregate_data'] = $makeJobAreaConditionAggregateUseCase($params);

            // 近隣都道府県の件数取得
            $addr1 = $this->masterAddr1Mst->getAddr1ByRoma($data['pref_roma']);
            $params = ['addr0_id' => $addr1->addr0_id, 'job_type' => $data['type'], 'conditions' => 'pref'];
            if (empty($data['state'])) {
                $data['area_data'] = $this->woaAreaConditionAggregate->getAreaData($params);
            }
            $data['type_roma'] = $data['type'];
            $jobType= $this->getJobTypeGroup($data['type']);
            $data['type_name'] = $jobType['name'];
            $data['pref_name'] = $addr1->addr1;
        }

        if ($this->agent->isMobile()) {
            return view('sp.job.list', $data);
        } else {
            return view('pc.job.list', $data);
        }
    }

    /**
     * 旧 求人詳細ページ、新ページにリダイレクトさせる
     * @access public
     * @param Request $request
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function jobdetail(Request $request, $id)
    {
        if (empty($id)) {
            abort(404);
        }
        $linkData = config('redirect.WO_LINK_DATA');
        if (isset($linkData[$id])) {
            $redirectPath = route('OpportunityDetail', ['id' => $linkData[$id]]);

            return redirect($redirectPath);
        }
        $result = $this->woaOpportunity->wolinkingData($id);
        if (empty($result) || $result->isEmpty()) {
            abort(404);
        } elseif ($result->count() > 1) {
            // 2件以上ある場合
            abort(404);
        }
        $redirectPath = route('OpportunityDetail', ['id' => $result[0]->job_id]);

        return redirect($redirectPath);
    }

    public function redirect(Request $request)
    {
        $data['pref'] = $request->input('pref');
        $data['state'] = $request->input('state');
        $type = $request->input('type');
        $link_type = $request->input('link_type');
        $freeword = $request->input('freeword');

        // この処理を通る場合は都道府県は存在するため
        // 市区町村の存在チェックのみ
        if ($link_type == 1) {
            if (!empty($data['state'])) {
                $redirectPath = route('AreaStateSelect', ['pref' => $data['pref'], 'state' => $data['state']]);
            } else {
                $redirectPath = route('AreaSelect', ['id' => $data['pref']]);
            }
        } elseif ($link_type == 2) {
            // link_typeが2の場合は職種も存在する
            // TOPからカンタン検索する際に職種の有無の確認も必要
            if (!empty($data['state']) && empty($type)) {
                $redirectPath = route('AreaStateSelect', ['pref' => $data['pref'], 'state' => $data['state']]);
            } elseif (empty($data['state']) && empty($type)) {
                $redirectPath = route('AreaSelect', ['id' => $data['pref']]);
            } else {
                $redirectPath = route('JobAreaStateSelect', ['id' => $type, 'pref' => $data['pref'], 'state' => $data['state']]);
            }
        }
        if (!isset($redirectPath)) {
            abort(404);
        }

        return redirect($redirectPath)->with(['freeword' => $freeword]);
    }

    /**
     * offsetの計算
     * @access private
     * @param int $page
     * @return array [$page, $offset]
     */
    private function getOffset($page): ?array
    {
        if (empty($page)) {
            $page = 1;
            $offset = 0;
        } else {
            $offset = ($page - 1) * config('ini.DEFAULT_OFFSET');
        }

        return [$page, $offset];
    }

    /**
     * job_type情報の取得
     * @access private
     * @param string $id
     * @return array
     */
    private function getJobTypeGroup(string $id): ?array
    {
        $result = [];

        $jobTypeGroup = config('ini.JOB_TYPE_GROUP');

        if (Arr::exists($jobTypeGroup, $id)) {
            $result = $jobTypeGroup[$id];
        }

        return $result;
    }

    /**
     * 求人一覧(job.list)で非公開求人部分の都道府県表示
     * @access private
     * @param int $addr1Id
     * @param string $addr1Name
     * @param string $addr2Name
     * @return string
     */
    private function getPrivateJobsPrefLabel(?int $addr1Id, ?string $addr1Name, ?string $addr2Name = null): string
    {
        $displayName = !empty($addr1Name) ? $addr1Name : '東京都';

        if (!empty(config('ini.STATE_DISPLAY_OF_PRIVATE_JOBS')[$addr1Id]) && !empty($addr2Name)) {
            $displayName .= $addr2Name;
        }

        return $displayName;
    }

    /**
     * 駅から徒歩5分以内 判定フラグ
     *
     * @return bool
     */
    private function isEkichika5(): bool
    {
        return (strpos(\Route::currentRouteName(), 'Ekichika5') !== false);
    }

    /**
     * 駅から徒歩5分以内 タイトル用テキスト取得
     *
     * @param bool $isEkichika5
     * @return string
     */
    private function getEkichika5TitleText(bool $isEkichika5): string
    {
        return $isEkichika5 ? (self::EKICHIKA5_TEXT . 'の') : '';
    }

    /**
     * 勤務形態 タイトル用テキスト取得
     *
     * @param bool $isEkichika5
     * @return string
     */
    private function getEmployTitleText($employ, $queryData): string
    {
        return $employ ? (config('ini.EMPLOY_TYPE')[$queryData['type']]['value'] . 'の') : '';
    }

    /**
     * 施設 タイトル用テキスト取得
     *
     * @param bool $isEkichika5
     * @return string
     */
    private function getBusinessTitleText($business, $queryData): string
    {
        return $business ? (config('ini.BUSINESS_TYPE')[$queryData['type']]['value'] . 'の') : '';
    }

    /**
     * meta.description の【20xx年xx月更新】部分のテキスト生成
     *
     * @return string
     */
    private function getHeadDescriptionDate(): string
    {
        return '【' . date('Y年n月') . '更新】';
    }

    /**
     * @param string|null $jobTypeRoma
     * @param int $prefId
     * @param int|null $stateId
     * @param array $freeword
     * @return array
     * @throws \Exception
     */
    private function getJobSearchLinks(
        ?string $jobTypeRoma,
        int $prefId,
        ?int $stateId,
        array $arrFreeword
    ): array {
        try {
            return (new MakeJobSearchLinksUseCase())($jobTypeRoma, $prefId, $stateId, $arrFreeword);
        } catch (\Exception $e) {
            // リンク取得ロジックはページの一部コンテンツなのでdebug時以外はエラーログのみ出力
            \Log::error(errorLogCommonOutput($e));
            if (config('app.debug')) {
                throw $e;
            }

            return [];
        }
    }

    /**
     * キーワードの空白を半角に統一し、半角スペースでexplode()する
     *
     * @param string|null $str
     * @return array
     */
    private function convertFreeword(?string $str): array
    {
        if ($str === null) {
            return [];
        }
        // 全角スペースを半角に置換
        $str = mb_convert_kana($str, "s");
        // 空白1文字を半角スペースに置換
        $str = preg_replace('/[\s]/', ' ', $str);
        // 複数空白を半角スペース1文字に置換
        $str = preg_replace('/\s\s+/', ' ', $str);
        // 左右余白を削除
        $str = trim($str);

        // 半角スペースで分割

        return explode(' ', $str);
    }

    /**
     * フリーワード、クエリストリングの作成
     *
     * @param string|null $reqFreeword
     * @return array
     */
    private function makeFreewordParam(?string $reqFreeword): array
    {
        $freeword = null;
        $queryString = null;
        if (session()->has('freeword')) {
            $freeword = session()->get('freeword');
            $queryString .= "&freeword={$freeword}";
        } elseif (!empty($reqFreeword)) {
            $freeword = $reqFreeword;
            $queryString .= "&freeword={$freeword}";
        }
        if (!empty($queryString)) {
            $queryString = preg_replace("/^&/", "?", $queryString);
        }

        return [
            'queryString' => $queryString,
            'freeword'    => $freeword,
        ];
    }
}
