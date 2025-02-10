<?php
namespace App\Http\Controllers;

use App\Managers\SelectBoxManager;
use App\Model\Blog;
use App\Model\ParameterMaster;
use Jenssegers\Agent\Agent;

class KnowhowController extends Controller
{
    public function __construct(ParameterMaster $parameter, Blog $blog)
    {
        $this->parameter = $parameter;
        $this->blog = $blog;
    }

    public function index()
    {
        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "就職活動ノウハウ";

        $data['list'] = $this->parameter->where('del_flg', 0)->where('genre_id', config('const.genre_knowhow'))->orderby('key_value')->get();

        $list = [];
        if (!empty($data['list'])) {
            foreach ($data['list'] as $key => $value) {
                $list[$value->key_value] = $value;
            }
        }
        $data['list'] = $list;

        $data['headtitle'] = "就職・転職活動ノウハウ|柔道整復師・柔整師の求人【ウィルワン】";

        $data['headdescription'] = "柔道整復師・鍼灸師・マッサージ師・セラピストの就職・転職活動ノウハウは【Willone(ウィルワン)】業界専門の就職支援のプロフェッショナルが完全無料でサポート!希望条件に沿って今以上の高収入や好待遇を叶えるため非公開求人を含め厳選します。理想のお仕事を一緒に探しましょう。";

        $defaultData = $this->setViewDefault();
        $data = array_merge($data, $defaultData);
        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.knowhow.index', $data);
        } else {
            return view('pc.knowhow.index', $data);
        }
    }

    public function list($knowhow)
    {
        $data['breadcrump_num'] = 2;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrumpurl'][] = "/";
        $data['breadcrump'][] = "就職活動ノウハウ";
        $data['breadcrumpurl'][] = route('Knowhow');

        $knowhow_cate = $this->parameter->where('value3', $knowhow)->where('genre_id', config('const.genre_knowhow'))->where('del_flg', 0)->first();
        if (!$knowhow_cate) {
            abort(404);
        }

        $data['breadcrump'][] = $knowhow_cate->value1;
        $data['knowhow_value'] = $knowhow_cate->value1;
        $data['knowhow_cate'] = $knowhow;

        $data['list'] = $this->blog->where('type', 0)->where('category_id', $knowhow_cate->key_value)->select('blog.*')->where('open_flg', 1)->where('del_flg', 0)->get();

        $data['headtitle'] = $knowhow_cate->value1 . "｜柔道整復師・鍼灸師・あん摩マッサージ指圧師の就職/転職サポート【ウィルワン】";

        $data['headdescription'] = $knowhow_cate->value4 . "業界専門の就職支援のプロフェッショナルが完全無料でサポート!希望条件に沿って今以上の高収入や好待遇を叶えるため非公開求人を含め厳選します。理想のお仕事を一緒に探しましょう。";

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.knowhow.list', $data);
        } else {
            return view('pc.knowhow.list', $data);
        }
    }

    public function detail($knowhow, $id)
    {
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrumpurl'][] = "/";
        $data['breadcrump'][] = "就職活動ノウハウ";
        $data['breadcrumpurl'][] = route('Knowhow');

        $data['blog_data'] = $this->blog->where('id', $id)->where('open_flg', 1)->where('del_flg', 0)->first();
        if (!$data['blog_data']) {
            abort(404);
        }

        $links = [
            'prev'   => '',
            'parent' => '/woa/knowhow/' . $knowhow,
            'next'   => '',
        ];
        $prev = $this->blog->where('id', '<', $data['blog_data']->id)->where('category_id', $data['blog_data']->category_id)->where('open_flg', 1)->where('del_flg', 0)->orderBy('id', 'desc')->first();
        $next = $this->blog->where('id', '>', $data['blog_data']->id)->where('category_id', $data['blog_data']->category_id)->where('open_flg', 1)->where('del_flg', 0)->orderBy('id', 'asc')->first();
        if ($prev) {
            $links['prev'] = '/woa/knowhow/' . $knowhow . '/' . $prev->id;
        }
        if ($next) {
            $links['next'] = '/woa/knowhow/' . $knowhow . '/' . $next->id;
        }
        $data['links'] = $links;

        $category_name = $this->parameter->where('genre_id', 15)->where('key_value', $data['blog_data']->category_id)->where('del_flg', 0)->first();
        if ($category_name) {
            $data['breadcrump'][] = $category_name->value1;
            $data['category_url'] = route('KnowhowList', ['knowhow' => $category_name->value3]);
        } else {
            $data['breadcrump'][] = "";
            $data['breadcrumpurl'][] = "";
        }

        $data['breadcrump'][] = $data['blog_data']->title;

        $data['headtitle'] = $data['blog_data']->title . "｜【ウィルワン】";
        $data['headdescription'] = $data['blog_data']->title . "を【Willone(ウィルワン)】がご紹介。業界専門の就職支援のプロフェッショナルが完全無料でサポート!希望条件に沿って今以上の高収入や好待遇を叶えるため非公開求人を含め厳選します。理想のお仕事を一緒に探しましょう。";

        $defaultData = $this->setViewDefault();
        $data = array_merge($data, $defaultData);
        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.blog.detail', $data);
        } else {
            return view('pc.blog.detail', $data);
        }
    }

    /**
     * 表示用設定値
     */
    private function setViewDefault()
    {
        $selectBoxMgr = new SelectBoxManager;
        // 保有資格
        $licenseList = $selectBoxMgr->sysLicenseMstSbNew();
        $data['licenseList'] = $licenseList;
        // 就業形態
        $data['reqEmpTypeList'] = $selectBoxMgr->sysEmpTypeMstSb();
        // 転職時期
        $data['reqDataList'] = $selectBoxMgr->sysReqdateMstSb();

        return $data;
    }
}
