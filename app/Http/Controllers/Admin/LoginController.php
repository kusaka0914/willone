<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        return view('admin.login.login');
    }

    public function register()
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        return view('admin.login.register');
    }

    public function registerinsert(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        $data_cnt = $this->user->where('email', $email)->count();

        if ($data_cnt > 0 or $name == "" or $email == "" or $password == "") {
            return view('admin.login.registererror');
        }

        $user_data = new User;
        $user_data->name = $name;
        $user_data->email = $email;
        $user_data->password = bcrypt($password);
        $user_data->save();

        return view('admin.login.registercomp');
    }

    public function logincheck(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return redirect('/woa/admin');
        } else {
            return view('admin.login.error');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return view('admin.login.login');
    }
}
