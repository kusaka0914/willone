<?php

namespace App\Http\Controllers\Entry;

use App\Http\Controllers\Controller;
use App\Managers\GlpTemplateManager;
use Illuminate\Http\Request;

class GlpController extends Controller
{
    private $view_data = [];

    public function index(Request $request, $lpId)
    {
        if (empty($lpId)) {
            abort(404);
        }

        // A/Bの固定値確認
        $scr = '';
        if ($request->has('scr')) {
            $scr = strtoupper($request->input('scr'));
        }

        // IDからテンプレート名取得
        $templateName = (new GlpTemplateManager())->getTemplateName($lpId, config('app.isSmartPhone'), $scr);
        if (!$templateName) {
            abort(404);
        }

        return $this->make($request, $templateName);
    }

    /**
     * Controller class make & call
     * @access private
     * @param Request $request
     * @param string $templateName
     * @return view
     */
    private function make(Request $request, $templateName)
    {
        // EntryController
        $called = app()->make('App\Http\Controllers\Entry\EntryController');

        list($type, $id) = explode("_", $templateName);

        return $called->index($request, $type, $id);
    }
}
