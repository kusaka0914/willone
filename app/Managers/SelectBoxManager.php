<?php
/**
 *  SelectboxManager
 */

namespace App\Managers;

use Illuminate\Support\Facades\DB;

class SelectBoxManager
{
    private $site_id;
    private $site_name;

    const FLAG_ON = 1; // フラグ：ON
    const FLAG_OFF = 0; // フラグ：OFF

    public function __construct()
    {
        $this->site_id = config('ini.SITE_ID');
        $this->site_name = config('ini.SITE_NAME');
    }

    /**
     * 都道府県セレクタ
     * @return リストを返す
     */
    public function sysPrefectureSb()
    {
        $sql = <<<SQL
SELECT
    *
FROM
    master_addr1_mst
WHERE
    delete_flag = 0
ORDER BY
    id
SQL;
        // データ取得
        $result = DB::select($sql);

        return $result;
    }

    /**
     * 市区町村セレクタ
     * @return リストを返す
     */
    public function sysCitySb($addr1)
    {
        $sql = <<<SQL
SELECT
    *
FROM
    master_addr2_mst
WHERE
    addr1_id = :addr1
    AND delete_flag = 0
    AND id NOT LIKE '%999'
ORDER BY
    sort
SQL;
        $param = [
            ':addr1' => $addr1,
        ];
        // データ取得
        $result = DB::select($sql, $param);

        return $result;
    }

    /**
     * 郵便番号検索
     *
     * @return 住所の配列を返す
     */
    public function getPrefCityListByZipCode($zip)
    {
        $zip1 = substr($zip, 0, 3);
        $zip2 = substr($zip, 3, 4);

        $sql = <<<SQL
SELECT
      a1.id AS addr1_id
    , a1.addr1
    , a2.id AS addr2_id
    , a2.addr2
    , zip.addr3
    , zip.zip1
    , zip.zip2
FROM
    master_addr1_mst a1
    JOIN master_addr2_mst a2 ON a2.addr1_id = a1.id
    JOIN master_zip_mst zip ON a1.addr1 = zip.addr1 AND a2.addr2 = zip.addr2
WHERE zip1 = :zip1 AND zip2 = :zip2
SQL;

        $param = [
            ':zip1' => $zip1,
            ':zip2' => $zip2,
        ];
        // データ取得
        $result = DB::select($sql, $param);
        if (empty($result)) {
            return [];
        }
        return $result[0];
    }

    /**
     * 郵便番号検索
     *
     * @return リストを返す
     */
    public function getPrefCityListAllByZipCode($zip)
    {
        $zip1 = substr($zip, 0, 3);
        $zip2 = substr($zip, 3) . '%';

        $sql = <<<SQL
SELECT
  a1.id AS addr1_id
, a1.addr1
, a2.id AS addr2_id
, a2.addr2
, zip.addr3
, zip.zip1
, zip.zip2
FROM
    master_addr1_mst a1
    JOIN master_addr2_mst a2 ON a2.addr1_id = a1.id
    JOIN master_zip_mst zip ON a1.addr1 = zip.addr1 AND a2.addr2 = zip.addr2
WHERE zip1 = :zip1 AND zip2 LIKE :zip2
SQL;

        $param = [
            ':zip1' => $zip1,
            ':zip2' => $zip2,
        ];
        // データ取得
        $result = DB::select($sql, $param);

        return $result;
    }

    /**
     * 保有資格リスト
     * @return array
     */
    public function sysLicenseMstSbNew()
    {
        $sql = <<<SQL
SELECT
  *
FROM
  master_license_mst
WHERE
  delete_flag = 0
  AND {$this->site_name}_sort > 0
ORDER BY
  {$this->site_name}_sort
SQL;

        $result = DB::select($sql);

        return $result;
    }

    /**
     * 雇用形態リスト
     * @return array
     */
    public function sysEmpTypeMstSb()
    {
        $sql = <<<SQL
SELECT
  *
FROM
  master_emp_type_mst
WHERE
  delete_flag = 0
  AND {$this->site_name}_sort > 0
ORDER BY
  {$this->site_name}_sort
SQL;

        // データ取得
        $result = DB::select($sql);

        return $result;
    }

    /** 雇用形態リスト
     * 選択肢 常勤・非常勤(週30時間以上)・こだわらない
     * @return リストを返す
     */
    public function sysEmpTypeMstSbNew()
    {
        $sql = <<<SQL
SELECT
  *
FROM
  master_emp_type_mst
WHERE
  delete_flag = 0
  AND id in (1, 8, 6)
ORDER BY
  {$this->site_name}_sort
SQL;

        // データ取得
        $result = DB::select($sql);

        return $result;
    }


    /**
     * 入職希望時期リスト
     * 選択肢 3・6・9・12ヶ月以内・いつでも
     * @return array
     */
    public function sysReqdateMstSb()
    {
        $sql = <<<SQL
SELECT
  *
FROM
  master_req_date_mst
WHERE
  delete_flag = 0
  AND {$this->site_name}_sort > 0
ORDER BY {$this->site_name}_sort
SQL;
        $result = DB::select($sql);

        return $result;
    }

    /**
     * 卒業年度リスト
     * @return リストを返す
     */
    public function sysBirthYearSb()
    {
        // 生まれた年
        $arrYear = [];
        for ($i = 18; $i <= 80; $i++) {
            $_YEAR = date('Y') - $i;
            if ($_YEAR <= 1988) {
                $arrYear[$_YEAR] = $_YEAR . '/昭和' . ($_YEAR - 1925);
            }

            if ($_YEAR == 1989) {
                $arrYear[$_YEAR] = '1989/昭和64･平成1';
            }

            if ($_YEAR >= 1990) {
                $arrYear[$_YEAR] = $_YEAR . '/平成' . ($_YEAR - 1988);
            }
        }

        return $arrYear;
    }

    /**
     * 卒業年度リスト
     * @return リストを返す
     */
    public function sysGraduationYearSb()
    {
        $month = date('m');
        if ($month < 4) {
            $year = date('Y') - 1;
        } else {
            $year = date('Y');
        }
        $result = [];
        for ($i = $year; $i < $year + 4; $i++) {
            // 翌年からの4年分に変更
            $result[$i + 1 . '年卒'] = $i + 1 . '年卒予定';
        }
        $result['不明'] = '不明';

        return $result;
    }

    /**
     * 卒業年度リスト
     * @return array
     */
    public function sysGraduationYearKurohonStudentSb() :array
    {
        $year = date('Y');
        $result = [];
        for ($i = $year; $i < $year + 4; $i++) {
            $result[$i . '年卒'] =  $i . '年卒';
        }

        $result['既卒'] = '既卒';
        $result['その他'] = 'その他';

        return $result;
    }

    /**
     * 募集職種マスタ検索
     *
     * 募集職種マスタより、指定されたIDの募集職種名を返却
     *
     * @param String $id 募集職種ID
     * @return 募集職種名
     */
    public function getJobNameById($id)
    {
        $result = DB::table('master_job_type_mst')
            ->select('job_type')
            ->where('id', '=', $id)
            ->where('delete_flag', '=', self::FLAG_OFF)
            ->get();

        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }
}
