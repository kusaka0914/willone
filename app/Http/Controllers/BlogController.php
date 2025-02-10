<?php
namespace App\Http\Controllers;

use App\Managers\SelectBoxManager;
use App\Model\Blog;
use App\Model\ParameterMaster;
use Jenssegers\Agent\Agent;

class BlogController extends Controller
{
    public function __construct(Blog $blog, ParameterMaster $parameter)
    {
        $this->blog = $blog;
        $this->parameter = $parameter;
    }

    public function detail($id)
    {

        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrumpurl'][] = "/";
        $data['breadcrump'][] = "就職活動ノウハウ";
        $data['breadcrumpurl'][] = route('Knowhow');

        $data['blog_data'] = $this->blog->where('id', $id)->where('open_flg', 1)->where('del_flg', 0)->first();
        if (!$data['blog_data']) {
            abort(404);
        }

        $category_name = $this->parameter->where('genre_id', 15)->where('key_value', $data['blog_data']->category_id)->where('del_flg', 0)->first();
        if ($category_name) {
            $data['breadcrump'][] = $category_name->value1;
            $data['category_url'] = route('KnowhowList', ['knowhow' => $category_name->value3]);
        } else {
            $data['breadcrump'][] = '';
            $data['category_url'] = route('Knowhow');
        }

        $data['breadcrump'][] = $data['blog_data']->title;

        $selectBoxMgr = new SelectBoxManager;
        // 保有資格
        $licenseList = $selectBoxMgr->sysLicenseMstSbNew();
        $data['licenseList'] = $licenseList;
        // 就業形態
        $data['reqEmpTypeList'] = $selectBoxMgr->sysEmpTypeMstSb();
        // 転職時期
        $data['reqDataList'] = $selectBoxMgr->sysReqdateMstSb();

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.blog.detail', $data);
        } else {
            return view('pc.blog.detail', $data);
        }
    }
}
