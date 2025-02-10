<?php
namespace App\Http\Controllers;

use App\Managers\AreaManager;
use App\Managers\WoaOpportunityManager;
use App\Model\MasterAddr1Mst;
use App\Model\MasterAddr2Mst;
use App\Model\ParameterMaster;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class HomeController extends Controller
{

    // WoaOpportunityManager
    private $woaOpportunityMgr;

    // MasterAddr1Mst
    private $masterAddr1Mst;

    // MasterAddr2Mst
    private $masterAddr2Mst;

    // AreaManager
    private $areaManager;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ParameterMaster $parameter, WoaOpportunityManager $woaOpportunityMgr, MasterAddr1Mst $masterAddr1Mst, MasterAddr2Mst $masterAddr2Mst, AreaManager $areaManager)
    {
        $this->parameter = $parameter;
        $this->woaOpportunityMgr = $woaOpportunityMgr;
        $this->masterAddr1Mst = $masterAddr1Mst;
        $this->masterAddr2Mst = $masterAddr2Mst;
        $this->areaManager = $areaManager;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data['headtitle'] = "【ウィルワン】柔道整復師・鍼灸師・マッサージ師の求人・転職";

        $data['headdescription'] = "柔道整復師・柔整師の求人情報や就職サービスは【Willone(ウィルワン)】業界専門の就職支援のプロフェッショナルが完全無料でサポート!希望条件に沿って今以上の高収入や好待遇を叶えるため非公開求人を含め厳選します。理想のお仕事を一緒に探しましょう。";

        $data['noindex'] = 0;

        // 職業プルダウン（３STEPカンタン検索 部分）
        $data['syokugyou'] = config('ini.JOB_TYPE_GROUP');

        // 勤務地プルダウン（３STEPカンタン検索 部分）
        $data['kinmuchi'] = $this->masterAddr1Mst->getListPrefecture(config('ini.AREA_PULLDOWN'));

        // エリア毎の都道府県リストを取得
        $data['prefecturesList'] = $this->areaManager->getAreaPrefList();

        // 人気エリアの取得
        $data['popCities'] = config('ini.AREA_POP');

        // 新着求人
        $data['new_job'] = $this->woaOpportunityMgr->newjob();

        $data['syokusyu_text'] = $this->parameter->getSyokusyuText();

        // NOTE: 同様の処理が複数箇所発生したら、Middleware/CommonController.php へのソース移動も検討
        // forceNoindexリストに一致した場合、強制的にnoindex,nofollowを設定
        if (in_array(request()->getRequestUri(), config('forceNoindex'), true)) {
            $data['noindex'] = 1;
        }

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.home.top', $data);
        } else {
            return view('pc.home.top', $data);
        }
    }

    /**
     * 市区町村の取得（<option value=xxx>yyyy</option>）Ajaxで取得用
     * @access public
     * @param request
     * @return \Illuminate\View\View
     */
    public function stateget(Request $request)
    {
        $pref_id = $request->input('pref');

        $data = [
            'state_data' => [],
        ];

        if (!empty($pref_id) && preg_match("/^[0-9]{2}$/", $pref_id)) {
            $data['state_data'] = $this->masterAddr2Mst->getAddr2ListByAddr1Id($pref_id);
        }

        return view('pc.home.statedata', $data);
    }
}
