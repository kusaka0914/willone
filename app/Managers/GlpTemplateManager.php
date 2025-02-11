<?php
/**
 *  PublicGlpManager
 */

namespace App\Managers;

use App\Managers\UtilManager;
use Illuminate\Support\Facades\DB;

class GlpTemplateManager
{

    /**
     * master_glp_template検索
     * @access private
     * @return オブジェクトを返す
     */
    private function getDataByLpId($lpid)
    {
        $sql = <<<SQL
SELECT
    *
FROM
    master_glp_template
WHERE
    lp_id = :lp_id
    AND site_type = :site_type
    AND delete_flag = 0
ORDER BY
    id ASC
SQL;

        $param = [
            ':lp_id'     => $lpid,
            ':site_type' => strtoupper(config('ini.SITE_NAME')),
        ];
        // データ取得
        $result = DB::select($sql, $param);

        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * master_glp_template検索しA/B判定実施
     * @access public
     * @param string $lpid
     * @param boolean $isSmartPhone
     * @param string $scr A/B固定値
     * @return mixed string テンプレート名 / boolean false
     */
    public function getTemplateName($lpid, $isSmartPhone, $scr = '')
    {
        $result = '';
        try {
            // GLPの設定取得
            $glp = $this->getDataByLpId($lpid);
            if (!$glp) {
                return $result;
            }

            if ($scr != 'A' && $scr != 'B') {
                // AB取得
                $scr = (new UtilManager())->getScreen();
            }
            if ($isSmartPhone) {
                // SP
                if ($scr == 'A') {
                    $result = $glp->sp_template;
                } else {
                    $result = $glp->sp_template_b;
                }
            } else {
                // PC
                if ($scr == 'A') {
                    $result = $glp->pc_template;
                } else {
                    $result = $glp->pc_template_b;
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        return $result;
    }
}
