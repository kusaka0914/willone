<?php
namespace App\Http\Controllers;

use App\Model\Inquiry;
use App\Model\Kaitou;
use Jenssegers\Agent\Agent;

class KaitouController extends Controller
{
    public function __construct(Kaitou $kaitou, Inquiry $inquiry)
    {
        $this->kaitou = $kaitou;
        $this->inquiry = $inquiry;
    }

    public function index()
    {

        $data['noindex'] = 0;

        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "解答速報";

        $data['kaitou_data'] = $this->kaitou->where('del_flg', 0)->orderby('shiken_date', 'desc')->limit(3)->get();

        $data['headtitle'] = "柔道整復師・鍼灸師・あん摩マッサージ指圧師 国家試験 解答速報 ｜柔道整復師・柔整師の求人【ウィルワン】";

        $data['headdescription'] = "柔道整復師・鍼灸師・あん摩マッサージ指圧師 国家試験 解答速報は【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.kaitou.index', $data);
        } else {
            return view('pc.kaitou.index', $data);
        }
    }

    public function kako()
    {

        $data['noindex'] = 0;

        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "過去の解答速報";

        $data['kaitou_data'] = $this->kaitou->where('del_flg', 0)->orderby('shiken_date', 'desc')->offset(3)->limit(100)->get();

        $data['headtitle'] = "柔道整復師・鍼灸師・あん摩マッサージ指圧師 過去国家試験 解答速報 ｜柔道整復師・柔整師の求人【ウィルワン】";

        $data['headdescription'] = "柔道整復師・鍼灸師・あん摩マッサージ指圧師 過去国家試験 解答速報は【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.kaitou.indexkako', $data);
        } else {
            return view('pc.kaitou.indexkako', $data);
        }
    }

    public function detail($id)
    {

        $data['noindex'] = 0;

        $kaitou_data = $this->kaitou->where('del_flg', 0)->where('kaitouurl', $id)->first();
        if (!$kaitou_data) {
            abort(404);
        }

        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = $kaitou_data->title;

        $data['kaitou'] = $kaitou_data;

        $data['headtitle'] = $kaitou_data->title . " 解答速報｜ウィルワン";
        $data['headdescription'] = $kaitou_data->title . " 解答速報は【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.kaitou.detail', $data);
        } else {
            return view('pc.kaitou.detail', $data);
        }
    }
}
