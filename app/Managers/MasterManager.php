<?php

namespace App\Managers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MasterManager
{
    /**
     * 希望転職時期レコードを取得
     * @access public
     * @param integer $addr1
     * @return array / null
     * @throw \SQLException
     */
    public function getListReqDateById($id)
    {
        if (empty($id)) {
            return null;
        }

        $sql = "
            SELECT *
            FROM master_req_date_mst
            WHERE id = ? AND delete_flag = 0 AND woa_sort > 0
            ORDER BY woa_sort
        ";
        $param = [$id];

        $rst = DB::select($sql, $param);

        return $rst;
    }

    /**
     * 雇用形態レコードを取得
     * @access public
     * @param integer $addr1
     * @return array / null
     * @throw \SQLException
     */
    public function getListEmpTypeById($id)
    {
        if (empty($id)) {
            return null;
        }

        $sql = "
            SELECT *
            FROM master_emp_type_mst
            WHERE id = ? AND delete_flag = 0 AND woa_sort > 0
            ORDER BY woa_sort
        ";
        $param = [$id];

        $rst = DB::select($sql, $param);

        return $rst;
    }


    /**
     * 保有資格レコードを取得
     * @access public
     * @param array $addr1
     * @return array / null
     * @throw \SQLException
     */
    public function getListLicenseByIds($ids)
    {
        if (!is_array($ids)) {
            return null;
        } else {
            // 数字以外は処理しない
            foreach($ids as $id) {
                if (!ctype_digit($id)) {
                    return null;
                }
            }
        }

        $ids = implode(",", $ids);

        $sql = "
            SELECT *
            FROM master_license_mst
            WHERE id in ({$ids}) AND delete_flag = 0 AND woa_sort > 0
            ORDER BY woa_sort
        ";

        $rst = DB::select($sql,[]);

        return $rst;
    }

    /**
     * 指定テーブルデータ追加
     * @access public
     * @param string $table
     * @param array $data
     * @param boolean $sys_date_set_flg
     * @return mixed
     */
    public function insertMasterMst($table, $data, $sys_date_set_flg = true)
    {
        if (!$table || is_null($table)) {
            return false;
        }

        $result = null;
        // 日付がセットされていないとき
        if ($sys_date_set_flg) {
            $now = date('Y-m-d H:i:s');
            if (!isset($data['regist_date']) || !$data['regist_date']) {
                $data['regist_date'] = $now;
            }

            if (!isset($data['update_date']) || !$data['update_date']) {
                $data['update_date'] = $now;
            }
        }
        // delete_date に空文字を入れると"0000-00-00 00:00:00"になってPentahoに影響出るのでnull化
        if (isset($data['delete_date']) && !$data['delete_date']) {
            $data['delete_date'] = null;
        }

        try {
            $result = DB::table($table)->insert($data);
        } catch (\Exception $e) {
            Log::error("{$e->getMessage()}: {$e->getFile()}:{$e->getLine()}\n{$e->getTraceAsString()}");
            return false;
        }

        return $result;
    }

    /**
     * Drop Table If Exists
     * ※ALTER権限で実行
     * @access public
     * @param string $table_name
     * @return mixed
     */
    public function dropTableIfExists($table_name)
    {
        try {
            $result = DB::connection('mysql_batch')->statement('DROP TABLE IF EXISTS ' . $table_name);
        } catch (\Exception $e) {
            Log::error("{$e->getMessage()}: {$e->getFile()}:{$e->getLine()}\n{$e->getTraceAsString()}");
            return false;
        }

        return $result;
    }

    /**
     * statement実行
     * ※ALTER権限で実行
     * @access public
     * @param string $statement
     * @return mixed
     */
    public function execStatement($statement)
    {
        try {
            $result = DB::connection('mysql_batch')->statement($statement);
        } catch (\Exception $e) {
            Log::error("{$e->getMessage()}: {$e->getFile()}:{$e->getLine()}\n{$e->getTraceAsString()}");
            return false;
        }

        return $result;
    }

    /**
     * Rename Table
     * ※ALTER権限で実行
     * @access public
     * @param string $from_table_name
     * @param string $to_table_name
     * @return mixed
     */
    public function renameTable($from_table_name, $to_table_name)
    {
        try {
            $result = DB::connection('mysql_batch')->statement('ALTER TABLE ' . $from_table_name . ' RENAME TO ' . $to_table_name);
        } catch (\Exception $e) {
            Log::error("{$e->getMessage()}: {$e->getFile()}:{$e->getLine()}\n{$e->getTraceAsString()}");
            return false;
        }

        return $result;
    }

    /**
     * 募集職種名から募集職種IDを取得
     * @access public
     * @param string $jobTypeName 募集職種名
     * @return 募集職種ID
     */
    public function getJobtypeId($jobTypeName)
    {
        $result = DB::table('master_job_type_mst')
            ->where('woa_sort', '>', 0)
            ->where('delete_flag', 0)
            ->where('job_type', $jobTypeName)
            ->first();

        return $result->id;
    }
}
