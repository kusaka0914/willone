<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ParameterMaster extends Model
{
    protected $table = "parameter_master";

    /**
     * @param array $syokusyuTextIds
     * @return array
     */
    public function getSyokusyuText(array $syokusyuTextIds = []): array
    {
        if (empty($syokusyuTextIds)) {
            $syokusyuTextIds = config('ini.DEFAULT_DISPLAY_SYOKUSYU_TEXT_LIST');
        }

        $collect = $this->whereIn('key_value', $syokusyuTextIds)
            ->whereIn('genre_id', [config('const.genre_syokusyu'), config('const.genre_syokusyu_text')])
            ->where('del_flg', 0)
            ->get();

        if ($collect->isEmpty()) {
            return [];
        }

        $result = [];
        foreach ($syokusyuTextIds as $syokusyuTextId) {
            $searchCollect = $collect->where('key_value', $syokusyuTextId);
            if ($searchCollect->isEmpty()) {
                continue;
            }
            $title = optional($searchCollect->where('genre_id', config('const.genre_syokusyu'))->first())->value1;
            $text = optional($searchCollect->where('genre_id', config('const.genre_syokusyu_text'))->first())->value4;
            if (empty($title) || empty($text)) {
                continue;
            }

            // title と text が両方存在したら値セット
            $result[$syokusyuTextId] = [
                'title' => $title,
                'text'  => $text,
            ];
        }

        return $result;
    }

    /**
     * 職種のkey_valueを取得する
     *
     * @param array $licenses
     * @return Collection
     */
    public function getSyokusyuType(array $licenses): Collection
    {
        $syokusyu_type_key_value = $this
            ->select('key_value')
            ->where('del_flg', 0)
            ->where('genre_id', config('const.genre_syokusyu'))
            ->whereIn('value1', $licenses)
            ->get();
        $syokusyu_type_key_value = $syokusyu_type_key_value->map(
            function ($v) {
                return $v->key_value;
            }
        );

        return $syokusyu_type_key_value;
    }

    /**
     * 一覧取得
     * @param array $condition 検索条件
     * @return collection
     */
    public function getExperienceParameterData(array $condition)
    {
        $query = $this->searchQuery($condition);
        $data = $query->orderby('value3')->orderby('key_value')->get();

        return $data;
    }

    /**
     * カテゴリ取得
     * @param array $condition 検索条件
     * @return object
     */
    public function getCategory(array $condition)
    {
        $query = $this->searchQuery($condition);
        $data = $query->first();

        return $data;
    }

    /**
     * 検索クエリ作成
     * @param array $condition 検索条件
     * @return Builder
     */
    private function searchQuery(array $condition)
    {
        $query = $this->where('del_flg', 0);
        if (!empty($condition['genre_id'])) {
            $query->where('genre_id', $condition['genre_id']);
        }
        if (!empty($condition['value2'])) {
            $query->where('value2', $condition['value2']);
        }

        return $query;
    }
}
