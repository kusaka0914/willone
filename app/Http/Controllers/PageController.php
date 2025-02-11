<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{
    private $view_data = [];

    public function ctIndex(Request $request, $label)
    {
        $device = config('app.device');
        $form_path = "{$device}.contents.include.ct." . $label;
        $path = str_replace("/", ".", $form_path);
        $view_path = trim($path, '.');
        if (!View::exists($view_path)) {
            abort(404);
        }

        return view($view_path, $this->view_data);
    }
}
