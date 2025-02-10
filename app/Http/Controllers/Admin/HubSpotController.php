<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HubSpotService;
use Illuminate\Http\Request;

class HubSpotController extends Controller
{
    private $hubspotService;
    // constructor
    public function __construct()
    {
        $this->middleware('auth');
        $this->hubspotService = new HubSpotService();
    }

    public function auth()
    {
        return $this->hubspotService->redirectToProvider();
    }

    public function callback(Request $request)
    {
        $code = $request->code;
        if (!$code) {
            return view('admin.hubspot', [
                'class' => 'error',
                'message' => 'codeが取得できませんでした'
            ]);
        }

        try {
            $this->hubspotService->getToken($code);
            $viewData = [
                'class' => 'success',
                'message' => 'HubSpotのトークン更新に成功しました'
            ];
        } catch (\Exception $e) {
            $viewData = [
                'class' => 'danger',
                'message' => 'HubSpotのトークン更新に失敗しました '. $e->getMessage()
            ];
        }

        return view('admin.hubspot', $viewData);
    }
}
