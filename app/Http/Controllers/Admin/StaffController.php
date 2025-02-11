<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\StaffExample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{

    /**
     * 更新対象の事例を選択
     *
     * @param integer $exampleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function exampleSelect(int $exampleId)
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        $staffExampleData = StaffExample::where('id', $exampleId)->first();

        if (!$staffExampleData) {
            return redirect()->back();
        }

        return redirect()->route('AdminStaffUpdate', ['id' => $staffExampleData->staff_id])
            ->with('exampleUpdateData', $staffExampleData);
    }

    /**
     * 事例を論理削除
     *
     * @param integer $exampleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function exampleDelete(int $exampleId)
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }
        $StaffExample = StaffExample::where('id', $exampleId)->first();
        if (!$StaffExample) {
            return redirect()->back();
        }
        $StaffExample->update(['delete_flag' => 1]);

        return redirect()->route('AdminStaffUpdate', ['id' => $StaffExample->staff_id]);
    }

    /**
     * 事例を登録・更新
     *
     * @param Request $request
     * @param integer $id
     * @param integer|null $exampleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function exampleUpsert(Request $request, int $id, int $exampleId = null)
    {
        if (!Auth::check()) {
            return redirect()->route('Login');
        }

        if ($exampleId) {
            $StaffExampleObj = StaffExample::where('id', $exampleId)->first();
        }
        if (!($StaffExampleObj ?? null)) {
            $StaffExampleObj = new StaffExample();
        }
        $StaffExampleObj->staff_id = $id;
        $StaffExampleObj->example_type = $request->input('example_type');
        $StaffExampleObj->age = $request->input('age');
        $StaffExampleObj->gender = $request->input('gender');
        $StaffExampleObj->license = $request->input('license');
        $StaffExampleObj->grade = $request->input('grade');
        $StaffExampleObj->catchphrase = $request->input('catchphrase');
        $StaffExampleObj->worry = $request->input('worry');
        $StaffExampleObj->research = $request->input('research');
        $StaffExampleObj->customer_comment = $request->input('customer_comment');
        $StaffExampleObj->cp_comment = $request->input('cp_comment');
        $StaffExampleObj->save();

        return redirect()->route('AdminStaffUpdate', ['id' => $id]);
    }
}
