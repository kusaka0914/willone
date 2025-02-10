<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MasterEntryCourseMst extends Model
{
    protected $table = 'master_entry_course_mst';

    /**
     * 登録カテゴリ取得
     *
     * @param string $action アクションパラメータ
     * @return string $entryCategory 登録カテゴリ
     */
    public function getEntryCategory($action)
    {
        $entryCategory = '不明';

        if (empty($action)) {
            return $entryCategory;
        }

        $actionLength = strlen($action);
        $actionBeginSt = mb_substr($action, 0, 2);

        $result = $this->select('query', 'entry_category')
            ->where('delete_flag', 0)
            ->where('query', 'LIKE', "{$actionBeginSt}%")
            ->whereRaw('LENGTH(query) <= ?', [$actionLength])
            ->orderByRaw('LENGTH(query) desc')
            ->get();

        foreach ($result as $val) {
            $actionSub = substr($action, 0, strlen($val->query));
            if ($actionSub == $val->query) {
                $entryCategory = $val->entry_category;
                break;
            }
        }

        return $entryCategory;
    }
}
