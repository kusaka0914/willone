<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = "blog";

    public function bloglist($type = 0)
    {
        $data = $this->where('del_flg', 0)->where('type', $type)->orderby('post_date', 'desc')->where('open_flg', 1)->take(config('const.blog_list_cnt'))->get();

        return $data;
    }

    /**
     * 一覧取得
     * @param array $condition 検索条件
     * @return collection
     */
    public function getExperienceData(array $condition)
    {
        $query = $this->searchQuery($condition);
        if (!empty($condition['offset'])) {
            $data = $query->orderby('post_date', 'DESC')->take(30)->skip($condition['offset'])->get();
        } else {
            $data = $query->get();
        }

        return $data;
    }

    /**
     * 一覧とカウント取得
     * @param array $condition 検索条件
     * @return array
     */
    public function getExperienceCategoryData(array $condition)
    {
        $query = $this->searchQuery($condition);
        $query = $query->where(function ($query) use ($condition) {
            $query->orWhere('category_id', $condition['category_id'])
                ->orWhere('category_2', $condition['category_id'])
                ->orWhere('category_3', $condition['category_id']);
        });
        $data = $query->orderby('post_date', 'DESC')->take(30)->skip($condition['offset'])->get();
        $count = $query->count();
        $return = [
            'data'  => $data,
            'count' => $count,
        ];

        return $return;
    }

    /**
     * カウント取得
     * @param array $condition 検索条件
     * @return Integer
     */
    public function getBlogDataCount(array $condition)
    {
        $query = $this->searchQuery($condition);
        $count = $query->count();

        return $count;
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
        if (!empty($condition['open_flg'])) {
            $query->where('open_flg', $condition['open_flg']);
        }
        if (!empty($condition['type'])) {
            $query->where('type', $condition['type']);
        }

        return $query;
    }

    /**
     * post_dataで旧リンクURLがDBに入っているのでそれを現在のURLになる様に加工
     *
     * @return string
     */
    public function getPostDataImgReplaceAttribute(): string
    {

        return str_replace(['/image_file/blog', '/upload/blog/image', '/uploads'], getS3ImageUrl(config('const.blog_image_path')), $this->post_data);
    }
}
