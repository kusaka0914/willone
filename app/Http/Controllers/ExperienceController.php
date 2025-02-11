<?php
namespace App\Http\Controllers;

use App\Model\Blog;
use App\Model\ParameterMaster;
use App\Model\Staff;
use App\Model\StaffExample;
use App\UseCases\Breadcrumbs\MakeBreadcrumbsTextWithPageUseCase;
use Jenssegers\Agent\Agent;

class ExperienceController extends Controller
{
    // S3のimageBacket /woa/images/example/にアップしている画像枚数
    private const EXAMPLE_IMAGE_MAX_COUNT = 12;

    /**
     * @var Blog
     */
    private $blog;

    /**
     * @var ParameterMaster
     */
    private $parameter;

    /**
     * @var String
     */
    private $device;

    /**
     * @param Blog $blog
     * @param ParameterMaster $parameter
     */
    public function __construct(
        Blog $blog,
        ParameterMaster $parameter
    ) {
        $this->blog = $blog;
        $this->parameter = $parameter;
        $this->device = (new Agent)->isMobile() ? 'sp' : 'pc';
    }

    public function index()
    {
        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "ウィルワンを利用された就職・転職者の方々";
        $data['noindex'] = 1;

        $data['blogdata'] = $this->blog->where('type', 2)->where('open_flg', 1)->where('del_flg', 0)->orderby('post_date', 'DESC')->take(6)->get();
        if (!$data['blogdata']) {
            abort(404);
        }

        $data['category'] = $this->parameter->where('genre_id', config('const.genre_taiken_cate'))->where('del_flg', 0)->get();

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.experience.index', $data);
        } else {
            return view('pc.experience.index', $data);
        }
    }

    /**
     * @param int $page
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function list(int $page = 1)
    {
        $data['breadcrump_num'] = 2;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "ウィルワンを利用された就職・転職者の方々";
        $data['breadcrump'][] = (new MakeBreadcrumbsTextWithPageUseCase())("就職・転職者体験談一覧", $page);

        $data['breadcrumpurl'][] = "/";
        $data['breadcrumpurl'][] = route('Tensyoku');
        $offset = ($page - 1) * 30;

        $params = [
            'type'     => 2,
            'open_flg' => 1,
            'offset'   => $offset,
        ];
        $data['blogdata'] = $this->blog->getExperienceData($params);

        $data['blogdatacnt'] = $this->blog->getBlogDataCount($params);
        $data['page'] = $page;
        $data['maxpage'] = ceil($data['blogdatacnt'] / 30);

        $params = [
            'genre_id' => config('const.genre_taiken_cate'),
        ];
        $data['category'] = $this->parameter->getExperienceParameterData($params);

        if ($data['blogdata']->isEmpty()) {
            $data['noindex'] = 1;
        }

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.experience.list', $data);
        } else {
            return view('pc.experience.list', $data);
        }
    }

    /**
     * マッチング事例（顧客の声）を集めたページ
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function cpList()
    {
        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = config('ini.BASE_BREAD_CRUMB')[0]['label'];
        $data['breadcrump'][] = "就職・転職者体験談一覧";

        $data['staffList'] = (new Staff)->list();
        $data['examplesData'] = StaffExample::query()->get();
        $data['category'] = $this->parameter->getExperienceParameterData(['genre_id' => config('const.genre_taiken_cate')]);
        $data['exampleImgMaxCount'] = self::EXAMPLE_IMAGE_MAX_COUNT;

        return view("{$this->device}.experience.cpList", $data);
    }

    /**
     * @param string $category
     * @param int $page
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function category(string $category, int $page = 1)
    {
        $data['breadcrump_num'] = 2;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "ウィルワンを利用された就職・転職者の方々";
        $data['breadcrump'][] = (new MakeBreadcrumbsTextWithPageUseCase())("就職・転職者体験談一覧", $page);

        $data['breadcrumpurl'][] = "/";
        $data['breadcrumpurl'][] = route('Tensyoku');
        $offset = ($page - 1) * 30;

        $params = [
            'genre_id' => config('const.genre_taiken_cate'),
            'value2'   => $category,
        ];
        $category_d = $this->parameter->getCategory($params);
        if (!$category_d) {
            abort(404);
        }
        $data['route_category'] = $category;

        $category_id = $category_d->key_value;
        $params = [
            'type'        => 2,
            'open_flg'    => 1,
            'category_id' => $category_id,
            'offset'      => $offset,
        ];
        $returnData = $this->blog->getExperienceCategoryData($params);
        $data['blogdata'] = $returnData['data'];
        $data['blogdatacnt'] = $returnData['count'];
        $data['page'] = $page;
        $data['maxpage'] = ceil($data['blogdatacnt'] / 30);

        $params = [
            'genre_id' => config('const.genre_taiken_cate'),
        ];
        $data['category'] = $this->parameter->getExperienceParameterData($params);
        if ($data['blogdata']->isEmpty()) {
            $data['noindex'] = 1;
        }
        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.experience.list', $data);
        } else {
            return view('pc.experience.list', $data);
        }
    }

    public function detail($id)
    {
        $data['breadcrump_num'] = 2;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "ウィルワンを利用された就職・転職者の方々";
        $data['breadcrump'][] = "就職・転職者体験談一覧";
        $data['breadcrumpurl'][] = "/";
        $data['breadcrumpurl'][] = route('Tensyoku');
        $data['breadcrumpurl'][] = route('TensyokuList');

        $data['blog_data'] = $this->blog->where('type', 2)->where('open_flg', 1)->where('del_flg', 0)->where('id', $id)->first();
        if (!$data['blog_data']) {
            abort(404);
        }
        $data['blogdatacnt'] = $this->blog->where('type', 2)->where('open_flg', 1)->where('del_flg', 0)->count();

        $data['category'] = $this->parameter->where('genre_id', config('const.genre_taiken_cate'))->where('del_flg', 0)->orderby('value3')->orderby('key_value')->get();

        $data['breadcrump'][] = $data['blog_data']->title;

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.experience.detail', $data);
        } else {
            return view('pc.experience.detail', $data);
        }
    }
}
