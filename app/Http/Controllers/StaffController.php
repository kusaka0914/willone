<?php
namespace App\Http\Controllers;

use App\Model\Staff;
use App\Model\StaffExample;
use Jenssegers\Agent\Agent;

class StaffController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Staff $staff)
    {
        $this->staff = $staff;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $data['breadcrump_num'] = 1;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrump'][] = "スタッフ紹介";

        $data['agent'] = $this->staff->list();

        $agent = new Agent();

        if ($agent->isMobile()) {
            return view('sp.staff.list', $data);
        } else {
            return view('pc.staff.list', $data);
        }
    }

    public function detail($id)
    {
        $data = [];

        $data['breadcrump_num'] = 2;
        $data['breadcrump'][] = "就職支援のウィルワン";
        $data['breadcrumpurl'][] = "/";
        $data['breadcrump'][] = "スタッフ紹介";
        $data['breadcrumpurl'][] = route('StaffList');

        $data['detail'] = $this->staff->where('del_flg', 0)->where('id', $id)->where('type', 1)->first();

        if (empty($data['detail'])) {
            return redirect()->route('StaffList');
        }

        $data['breadcrump'][] = $data['detail']->name;

        $data['agent'] = $this->staff->list();
        $data['examples'] = StaffExample::where('staff_id', $data['detail']->id)->get();

        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('sp.staff.detail', $data);
        } else {
            return view('pc.staff.detail', $data);
        }
    }

    public function example($staffId, $caseNo)
    {

        $staffExampleList = StaffExample::where('staff_id', $staffId)->get();
        // caseNoはレコードの取得順になる。caseNoの開始が1でレコードのindexは0開始なのでcaseNo - 1で取得する
        $staffExample = $staffExampleList->get(($caseNo - 1));
        if (!$staffExample) {
            abort(404);
        }
        $staff = (new Staff)->list()->where('id', $staffId)->first();
        if (!$staff) {
            abort(404);
        }

        $device = (new Agent())->isMobile() ? 'sp' : 'pc';

        return view("{$device}.staff.example", [
            'breadcrump'       => [
                ['label' => 'スタッフ紹介', 'url' => route('StaffList')],
                ['label' => $staff->name, 'url' => route('StaffDetail', ['id' => $staffId])],
                ['label' => "事例{$caseNo}"],
            ],
            'staff'            => $staff,
            'staffExample'     => $staffExample,
            'staffExampleList' => $staffExampleList,
            'caseNo'           => $caseNo,
        ]);
    }
}
