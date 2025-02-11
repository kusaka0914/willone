<?php
namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Company extends Model
{
    protected $table = "company";

    protected $guarded = ['id'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const FLAG_ON = 1; // フラグ：ON
    const FLAG_OFF = 0; // フラグ：OFF

    /**
     * 会社データの取得
     * @access public
     * @param int $id
     * @return Collection
     */
    public function getCompanyById(int $id = null):  ? object
    {
        if (empty($id)) {
            return null;
        }

        $result = $this->where('del_flg', self::FLAG_OFF)
            ->where('id', $id)
            ->first();

        return $result;
    }

    /**
     * エリア毎かつ、職種タイプ毎で、求人数を取得するメソッド
     * @access public
     * @return array
     */
    public function getCompanyList() : array
    {
        $columns = [
            'id',
            'company_name',
            'sf_office_id',
        ];

        $builder = $this->where('del_flg', '=', self::FLAG_OFF);
        $result = $builder->get($columns)->toArray();

        if (count($result) == 0) {
            return [];
        }

        return $result;
    }

    /**
     * company.sf_office_idの更新
     * @access public
     * @param int $id ID
     * @param array $data
     * @return string $result 更新結果
     */
    public function updateSfOfficeId(int $id, array $data): int
    {
        // $id, $data['sf_office_id'] は必須
        if (empty($id) || empty($data['sf_office_id'])) {
            return 0;
        }

        $result = $this
            ->where('id', $id)
            ->update($data);

        return $result;
    }

    /**
     * company.sf_office_id の新規追加
     * @access public
     * @param array $data SF連携フラグ
     * @return int $id 更新結果
     */
    public function insertSfOfficeId(array $data):  ? int
    {
        // $data['sf_office_id'] は必須
        if (empty($data['sf_office_id'])) {
            return 0;
        }
        $id = null;
        $company = $this->create($data);
        if (isset($company->id)) {
            $id = $company->id;
        }

        return $id;
    }

    /**
     * 重複している事業所のリストを取得
     * @access public
     * @return array
     */
    public function getDuplicateList() : array
    {
        $query = $this->select(DB::raw("binary `company_name` as company_name"), DB::raw('MIN(id) as min_id'))
            ->where('del_flg', 0)
            ->where('company_name', '<>', '非公開')
            ->whereNotNull('company_name')
            ->groupBy(DB::raw('binary `company_name`'))
            ->havingRaw('COUNT(id) > ?', [1]);

        $result = $query->get()->toArray();
        if (empty($result)) {
            $result = [];
        }

        return $result;
    }

    /**
     * 重複している事業所の一括削除（会社名をキー）
     * @access public
     * @param string $name 会社名
     * @param int $exludedId 除外するID
     * @return int
     */
    public function deleteDuplicateName(string $name, int $excludedId): int
    {
        $columns = [
            'del_flg' => 1,
        ];
        $result = $this
            ->where('del_flg', 0)
            ->where('id', '<>', $excludedId)
            ->whereRaw("binary `company_name` = '{$name}'")
            ->update($columns);

        return $result;
    }

    /**
     * 求人のある会社一覧を取得
     * @access public
     * @return Collection
     */
    public function getJobCountList():  ? object
    {
        $query = $this->leftJoin("woa_opportunity", "{$this->table}.id", "=", "woa_opportunity.company_id")
            ->select(
                "{$this->table}.id",
                DB::raw("COUNT(woa_opportunity.id) AS order_count")
            )
            ->where("{$this->table}.del_flg", self::FLAG_OFF)
            ->where("woa_opportunity.webpublicly_flag", self::FLAG_ON)
            ->where("woa_opportunity.publicly_flag", self::FLAG_ON)
            ->where("woa_opportunity.exist_order_flag", self::FLAG_ON)
            ->where("woa_opportunity.delete_flag", self::FLAG_OFF)
            ->groupBy("{$this->table}.id");

        return $query->get();
    }
}
