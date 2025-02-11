<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Managers\S3Manager;
use App\Model\Blog;
use App\Model\ParameterMaster;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{

    private $s3Manager;
    private $blogImageDir;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user, Blog $blog, ParameterMaster $parameter)
    {
        $this->user = $user;
        $this->blog = $blog;
        $this->parameter = $parameter;
        $this->s3Manager = new S3Manager('s3_co_image');
        $this->blogImageDir = config('ini.SITE_NAME') . config('const.blog_image_path') . '/';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function syusyokulist()
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $data['blog'] = $this->blog->where('type', 0)->where('del_flg', 0)->orderby('id', 'DESC')->get();

        $data['category'] = $this->parameter->where('genre_id', 15)->orderby('key_value')->get();

        return view('admin.syusyokulist', $data);
    }

    public function syusyokudetail($id)
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $data['blog'] = $this->blog->where('type', 0)->where('del_flg', 0)->where('id', $id)->first();

        $data['category'] = $this->parameter->where('genre_id', 15)->orderby('key_value')->get();

        return view('admin.syusyokudetail', $data);
    }

    public function syusyokuupdate(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }
        if ($request->hasFile('file')) {
            $this->validate($request, [
                'file' => ['required', 'image'],
            ]);
            $uploadedFile = $request->file('file');
            if ($uploadedFile->isValid()) {
                $fileName = $uploadedFile->hashName();
                $this->s3Manager->uploadImg($this->blogImageDir, $uploadedFile, $fileName);
                $list_image = '/' . $fileName;
            } else {
                if (!empty($request->input('blog_image_check'))) {
                    $list_image = $request->input('blog_image_check');
                } else {
                    $list_image = "";
                }
            }
        } else {
            if (!empty($request->input('blog_image_check'))) {
                $list_image = $request->input('blog_image_check');
            } else {
                $list_image = "";
            }
        }

        $id = $request->input('id');
        $title = $request->input('title');
        $open_flg = $request->input('open_flg');
        $category_id = $request->input('category_id');
        $type = 0;
        $post_date = date('Y-m-d');
        $post_data = $request->input('editor1');

        if ($id == "") {
            $blog_data = new Blog;
        } else {
            $blog_data = Blog::firstOrNew(['id' => $id]);
        }

        $blog_data->type = $type;
        $blog_data->post_date = $post_date;
        $blog_data->title = $title;
        $blog_data->post_data = $post_data;
        $blog_data->list_image = $list_image;
        $blog_data->category_id = $category_id;
        $blog_data->open_flg = $open_flg;

        $blog_data->save();

        $id = $blog_data->id;

        $data['message'] = "保存しました。";
        $data['blog'] = $blog_data;

        $data['category'] = $this->parameter->where('genre_id', 15)->orderby('key_value')->get();

        return view('admin.syusyokudetail', $data);
    }

    public function syusyokunew()
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $data['blog'] = new Blog;
        $data['category'] = $this->parameter->where('genre_id', 15)->orderby('key_value')->get();

        return view('admin.syusyokudetail', $data);
    }

    public function syusyokudel($id)
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }
        $this->blog->where('id', $id)->delete();
        $data['message'] = "削除しました。";

        $data['blog'] = $this->blog->where('type', 0)->where('del_flg', 0)->orderby('id', 'DESC')->get();

        $data['category'] = $this->parameter->where('genre_id', 15)->orderby('key_value')->get();

        return view('admin.syusyokulist', $data);
    }

    public function blogimage()
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        return view('admin.blogimage', $this->imageList());
    }

    public function blogimageupload(Request $request)
    {

        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        if ($request->hasFile('file')) {
            $this->validate($request, [
                'file' => ['required', 'image'],
            ]);
            $uploadedFile = $request->file('file');
            if ($uploadedFile->isValid()) {
                $this->s3Manager->uploadImg($this->blogImageDir, $uploadedFile, $uploadedFile->hashName());
                $data['message'] = '保存しました';
            } else {
                $data['message'] = 'ファイルが選択されていません';
            }
        } else {
            $data['message'] = 'ファイルが選択されていません';
        }

        $data = $this->imageList($data);

        return view('admin.blogimage', $data);
    }

    /**
     * 画像アップロード画面のファイル一覧表示
     *
     * @param array $data = []
     * @return array
     */
    private function imageList(array $data = []): array
    {

        $data['file'] = [];
        $listObjects = $this->s3Manager->listObjects(env('S3_CO_IMAGE_BUCKET'), $this->blogImageDir, '/');
        if (empty($listObjects['Contents'])) {
            return $data;
        }

        // 'LastModified'フィールドを使って最新順にソート
        usort($listObjects['Contents'], function ($a, $b) {
            return strcmp($b['LastModified'], $a['LastModified']);
        });

        $data['file'] = collect($listObjects['Contents'])
            ->filter(function ($listObjects) {
                // ディレクトリも取得しちゃうのでドットが入っているパスにする
                return preg_match('/\./', $listObjects['Key']);
            })
            ->map(function ($listObjects) {
                // 'LastModified'フィールドを含めた新しいデータ構造を作成
                return [
                    'filename'   => getS3ImageUrl($listObjects['Key'], '/'),
                    'updatedate' => Carbon::parse($listObjects['LastModified'])->format('Y/m/d H:i:s'),
                ];
            })
            ->toArray();

        return $data;
    }
}
