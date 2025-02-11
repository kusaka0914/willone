<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Managers\S3Manager;
use App\Model\Kaitou;
use App\Model\ParameterMaster;
use App\Model\Staff;
use App\Model\StaffExample;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user, ParameterMaster $parameter, Staff $staff, Kaitou $kaitou)
    {
        $this->user = $user;
        $this->parameter = $parameter;
        $this->staff = $staff;
        $this->kaitou = $kaitou;

        $this->s3Manager = new S3Manager('s3_co_image');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        return view('admin.top');
    }

    public function user()
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $data['user'] = $this->user->get();

        return view('admin.user', $data);
    }

    public function userdelete($id)
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $this->user->where('id', $id)->delete();

        $data['user'] = $this->user->get();

        return view('admin.user', $data);
    }

    public function stafflist()
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $data['staff'] = $this->staff->get();

        return view('admin.stafflist', $data);
    }

    public function staffupdate($id)
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $data['staff'] = $this->staff->where('id', $id)->first();
        $data['staffExamples'] = StaffExample::where('staff_id', $id)->get();
        // /Admin/StaffController#exampleSelect から更新対象を取得してここにredirectしてくる
        $data['exampleUpdateData'] = session()->get('exampleUpdateData');

        return view('admin.staffdetail', $data);
    }

    public function staffnew()
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }
        $data['staff'] = new Staff;

        return view('admin.staffdetail', $data);
    }

    /**
     * 画像登録、更新処理
     * @access public
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function staffpost(Request $request)
    {
        $message = "";
        try {
            if (!Auth::check()) {
                return redirect()->route('Login');
            }

            // validate処理
            // 新規登録の必須チェック
            $validator = \Validator::make($request->all(), [
                'file' => 'image|required_if:id,!=,null',
            ], [
                'file.required_if' => '新規画像ファイルは必須項目です。',
                'file.image'       => 'ファイルは画像形式である必要があります。',
            ]);

            $staffData = !empty($request->input('id')) ? Staff::find($request->input('id')) : new Staff;

            if ($request->file('file')) {
                $uploadedFile = $request->file('file');
                // ハッシュ名を生成ファイル名をユニークにする
                $fileName = $uploadedFile->hashName();
                $staffImage = config('const.staff_image_path') . "/" . $fileName;
                // S3の格納パス
                $staffimagePath = config('ini.SITE_NAME') . $staffData->staff_image_path;
                // IDが存在、S3にファイルが存在した場合は既存削除後、更新処理
                if (!empty($staffData->id) && $this->s3Manager->checkFileExists($staffimagePath)) {
                    if ($this->s3Manager->deleteFile($staffimagePath)) {
                        $this->s3Manager->uploadImg(config('ini.SITE_NAME') . config('const.staff_image_path') . "/", $uploadedFile, $fileName);
                        $message = "更新しました。";
                    } else {
                        // 既存登録イメージがある場合は既存、ない場合は空の値を更新
                        $staffImage = $request->input('h_image_path') ?? '';
                        $message = "既存画像の削除に失敗しました。";
                    }
                } else {
                    // 新規またはIDが存在、S3にファイルが存在しない場合
                    $this->s3Manager->uploadImg(config('ini.SITE_NAME') . config('const.staff_image_path') . "/", $uploadedFile, $fileName);
                    $message = "保存しました。";
                }
            } else {
                $staffImage = $request->input('h_image_path') ?? '';
                $message = "更新しました。";
            }

            $staffData->name = $request->input('name');
            $staffData->staff_image_path = $staffImage;
            $staffData->type = $request->input('type');
            $staffData->from_place = $request->input('from_place');
            $staffData->zayuu = $request->input('zayuu');
            $staffData->sonkei = $request->input('sonkei');
            $staffData->caption = $request->input('caption');
            $staffData->catchcopy = $request->input('catchcopy');
            $staffData->question1 = $request->input('question1');
            $staffData->question2 = $request->input('question2');
            $staffData->question3 = $request->input('question3');

            // validateエラーの場合メッセージを返す
            if ($validator->fails()) {
                return view('admin.staffdetail', [
                    'message' => $validator->errors()->first('file'),
                    'staff'   => $staffData,
                ]);
            }

            // バリデーションエラーがない場合、保存
            $staffData->save();

            return view('admin.staffdetail', [
                'message'       => $message,
                'staff'         => $this->staff->where('id', $staffData->id)->first(),
                'staffExamples' => StaffExample::where('staff_id', $staffData->id)->get(),
            ]);
        } catch (\Exception $e) {
            Log::error($e->getFile() . '(' . $e->getLine() . ") " . $e->getMessage() . "\n" . $e->getTraceAsString());

            return view('admin.staffdetail', [
                'message' => "画像のアップロード中にエラーが発生しました。",
                'staff'   => $staffData,
            ]);
        }
    }

    /**
     * 画像削除処理
     * @access public
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function staffdelete(int $id)
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        try {
            $staffData = Staff::find($id);

            if ($staffData) {
                // 画像が存在している場合は削除
                if (!empty($staffData->staff_image_path) && $this->s3Manager->checkFileExists(config('ini.SITE_NAME') . $staffData->staff_image_path)) {
                    // 画像の削除
                    if ($this->s3Manager->deleteFile(config('ini.SITE_NAME') . $staffData->staff_image_path)) {
                        // データベースの削除
                        $this->staff->where('id', $staffData->id)->delete();
                        $data['message'] = "スタッフデータを削除しました。";
                    }
                } else {
                    // S3にファイルがないのでDBデータは削除する
                    $this->staff->where('id', $staffData->id)->delete();
                    $data['message'] = "スタッフデータを削除しました。";
                }
            } else {
                $data['message'] = "スタッフデータを削除しました。";
            }
        } catch (\Exception $e) {
            Log::error($e->getFile() . '(' . $e->getLine() . ") " . $e->getMessage() . "\n" . $e->getTraceAsString());
            // 例外が発生した場合
            $data['message'] = "画像の削除中にエラーが発生しました。";
        }

        $data['staff'] = $this->staff->get();

        return view('admin.stafflist', $data);
    }

    // 解答速報ページ一覧表示
    public function kaitou()
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('Login');
            }

            return $this->kaitoulist("");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $data['message'] = "エラーが発生しました。";
            $data['kaitoulist'] = [];

            return view('admin.kaitou', $data);
        }
    }

    // 解答速報ページ詳細（編集）画面表示
    public function kaitoudetail($id)
    {
        return $this->kaitoudisplay($id);
    }

    // 解答速報ページ新規作成画面表示
    public function kaitounew()
    {
        return $this->kaitoudisplay(null);
    }

    // 解答速報ページ保存処理
    public function kaitouupdate(Request $request)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('Login');
            }

            // 入力チェック
            $request->validate([
                'title'         => 'required|max:200',
                'shiken_date'   => 'required|max:200',
                'kaitou_image1' => 'required|max:200',
                'kaitou_image2' => 'max:200',
                'kaitou_image3' => 'max:200',
                'kaitouurl'     => 'required|max:45',
            ]);

            $id = $request->input('id');
            $title = $request->input('title');
            $shiken_date = $request->input('shiken_date');
            $kaitou_image1 = $request->input('kaitou_image1');
            $kaitou_image2 = $request->input('kaitou_image2');
            $kaitou_image3 = $request->input('kaitou_image3');
            $kaitouurl = $request->input('kaitouurl');

            if (isset($id) && !empty($id)) {
                $kaitou_data = $this->kaitou->get($id);
            } else {
                $kaitou_data = new Kaitou;
            }
            $kaitou_data->title = $title;
            $kaitou_data->shiken_date = $shiken_date;
            $kaitou_data->kaitou_image1 = $kaitou_image1;
            $kaitou_data->kaitou_image2 = $kaitou_image2;
            $kaitou_data->kaitou_image3 = $kaitou_image3;
            $kaitou_data->kaitouurl = $kaitouurl;

            $kaitou_data->save();

            return $this->kaitoulist("保存しました。");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $data['message'] = "エラーが発生しました。";
            $data['kaitou'] = [];

            return view('admin.kaitoudetail', $data);
        }
    }

    // 解答速報ページ削除処理
    public function kaitoudel(Request $request, $id)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('Login');
            }
            $this->kaitou->remove($id);

            return $this->kaitoulist("削除しました。");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $data['message'] = "エラーが発生しました。";
            $data['kaitoulist'] = [];

            return view('admin.kaitou', $data);
        }
    }

    private function kaitoulist($message)
    {
        if (isset($message) && !empty($message)) {
            $data['message'] = $message;
        }
        $data['kaitoulist'] = $this->kaitou->getList();

        return view('admin.kaitou', $data);
    }

    private function kaitoudisplay($id)
    {
        $data = [];
        try {
            if (!Auth::check()) {
                return redirect()->route('Login');
            }
            if (isset($id) && !empty($id)) {
                $data['kaitou'] = $this->kaitou->get($id);
            } else {
                $data['kaitou'] = new Kaitou;
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $data['message'] = "エラーが発生しました。";
            $data['kaitou'] = [];
        }

        return view('admin.kaitoudetail', $data);
    }

    // 解答速報画像一覧表示
    public function image()
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('Login');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return $this->imagelist("");
    }

    /**
     * 解答速報画像アップロード処理
     *
     * @param Request $request
     * @return srting $message
     */
    public function imageupload(Request $request): string
    {
        $message = "";
        try {
            if (!Auth::check()) {
                return redirect()->route('Login');
            }

            // validate処理
            // 新規登録の必須チェック
            $validator = \Validator::make($request->all(), [
                'file' => 'image|required',
            ], [
                'file.required' => '新規画像ファイルは必須項目です。',
                'file.image'    => 'ファイルは画像形式である必要があります。',
            ]);

            if ($validator->fails()) {
                $message = $validator->errors()->first('file');
            }

            if ($request->file('file') && !$validator->fails()) {
                $uploadedFile = $request->file('file');
                $fileName = $uploadedFile->getClientOriginalName(); // ファイル名を取得
                $this->s3Manager->uploadImg(config('ini.SITE_NAME') . config('const.kaitou_image_path_s3') . "/", $uploadedFile, $fileName);

                $message = str_replace(config('const.kaitou_image_path_s3'), '', "{$fileName}保存しました。");
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $message = "エラーが発生しました。";
        }

        return $this->imagelist($message);
    }

    // 解答速報変換ページ
    public function answer()
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }
        $data = [];
        return view('admin.answer', $data);
    }


    /**
     * 解答速報リスト表示
     *
     * @param string|null $message
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function imagelist(?string $message)
    {
        if (!empty($message)) {
            $imageFiles['message'] = $message;
        }

        // S3バケットからオブジェクトリストを取得
        $kaitouObjects = $this->s3Manager->listObjects(env('S3_CO_IMAGE_BUCKET'), config('ini.SITE_NAME') . config('const.kaitou_image_path_s3') . "/");
        $imageFiles['file'] = [];
        if (!empty($kaitouObjects['Contents'])) {
            // 'LastModified'フィールドを使って最新順にソート
            usort($kaitouObjects['Contents'], function ($a, $b) {
                return strcmp($b['LastModified'], $a['LastModified']);
            });

            $imageFiles['file'] = collect($kaitouObjects['Contents'])
                ->filter(function ($kaitouObjects) {
                    // キーが特定の画像拡張子で終わるかを確認する条件を追加
                    return preg_match('/\.(jpg|jpeg|png|gif)$/i', $kaitouObjects['Key']);
                })
                ->map(function ($kaitouObjects) {
                    // 'LastModified'フィールドを含めた新しいデータ構造を作成
                    return [
                        'filename'   => $kaitouObjects['Key'],
                        'updatedate' => Carbon::parse($kaitouObjects['LastModified'])->format('Y/m/d H:i:s'),
                    ];
                })
                ->toArray();
        }

        return view('admin.kaitouimage', $imageFiles);
    }
}
