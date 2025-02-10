<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{

    /**
     * 黒本関連操作
     *
     * @return view
     */
    public function kurohonOperation()
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }
        $data = [];
        return view('admin.kurohonOperation', $data);
    }
}
