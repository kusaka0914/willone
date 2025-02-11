<?php

namespace App\Http\Controllers;

use Jenssegers\Agent\Agent;

class ServiceController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function service()
    {
        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "治療家向けのお仕事紹介して20年！ウィルワンエージェント";

        $data['headtitle'] = "キャリア支援サービス紹介｜柔道整復師・柔整師の求人【ウィルワン】";

        $data['headdescription'] = "就職支援サービスは【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.service.top', $data);
        } else {
            return view('pc.service.top', $data);
        }
    }

    public function servicefree()
    {
        $data['breadcrump_num'] = 2;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrumpurl'][] = "/";
        $data['breadcrump'][] = "治療家向けのお仕事紹介して20年！ウィルワンエージェント";
        $data['breadcrumpurl'][] = route('Service');
        $data['breadcrump'][] = "完全無料で、しっかりサポート！";

        $data['headtitle'] = "手厚いサポートの就職・転職支援サービス｜柔道整復師の求人【ウィルワン】";

        $data['headdescription'] = "柔道整復師・柔整師の求人情報や就職サービスは完全無料の【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.service.servicefree', $data);
        } else {
            return view('pc.service.servicefree', $data);
        }
    }

    public function servicefeature()
    {
        $data['breadcrump_num'] = 2;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrumpurl'][] = "/";
        $data['breadcrump'][] = "治療家向けのお仕事紹介して20年！ウィルワンエージェント";
        $data['breadcrumpurl'][] = route('Service');
        $data['breadcrump'][] = "希望にあわせたピンポイント！面接対策";

        $data['headtitle'] = "希望就職先にあわせたピンポイント！面接対策｜柔道整復師の求人【ウィルワン】";

        $data['headdescription'] = "柔道整復師・柔整師の求人情報や就職サービス・面接対策は【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.service.servicefeature', $data);
        } else {
            return view('pc.service.servicefeature', $data);
        }
    }

    public function servicefind()
    {
        $data['breadcrump_num'] = 2;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrumpurl'][] = "/";
        $data['breadcrump'][] = "治療家向けのお仕事紹介して20年！ウィルワンエージェント";
        $data['breadcrumpurl'][] = route('Service');
        $data['breadcrump'][] = "登録後は待っているだけでOK！学業や仕事と就職・転職活動が両立できます！";

        $data['headtitle'] = "働きながら転職先を探せる就職支援｜柔道整復師の求人【ウィルワン】";

        $data['headdescription'] = "柔道整復師・柔整師の求人情報や就職サービスを働きながら探せる【Willone(ウィルワン)】" . view()->shared('countActiveWoaOpportunity') . "件の求人数！柔道整復師や柔整師に特化した条件で今よりも高収入や好待遇な職場を非公開求人からも厳選してご紹介！職場見学から履歴書や面接のアドバイスまで、就職支援のプロフェッショナルが完全無料でサポートします。";

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.service.servicefind', $data);
        } else {
            return view('pc.service.servicefind', $data);
        }
    }
}
