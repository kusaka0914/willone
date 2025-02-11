<?php

namespace App\Managers;

use Forrest;

/**
 * SF接続関連処理
 */
class SfManager
{
    /**
     * SF情報取得
     *
     * @param string $query SOQLクエリ
     * @return array 取得結果 最大2000件まで
     */
    public function select($query)
    {
        try {
            return $this->selectProcessDetail($query);
        } catch (\Exception $e) {
            // エラーの場合、認証を実施
            $this->sfAuthentication();
            return $this->selectProcessDetail($query);
        }
    }

    /**
     * SF情報取得処理詳細
     *
     * @param string $query SOQLクエリ
     * @return array 取得結果 最大2000件まで
     */
    private function selectProcessDetail($query)
    {
        return Forrest::query($query);
    }

    /**
     * 取得結果が2000件を超えている場合の次の2000件取得
     *
     * Forrest::queryの結果が2000件を超えている場合、
     * レスポンスに'nextRecordsUrl'というURLが返ってくる
     * それをこのメソッドに渡し、次の2000件を取得する事
     *
     * 全ての取得が完了したかは、レスポンスの'done'が「true」かで判定可能
     *
     * @param string $nextRecordsUrl Forrest::queryの結果に含まれるURL
     * @return array 取得結果 最大2000件まで
     */
    public function selectNextRecords($nextRecordsUrl)
    {
        try {
            return $this->selectNextRecordsProcessDetail($nextRecordsUrl);
        } catch (\Exception $e) {
            // エラーの場合、認証を実施
            $this->sfAuthentication();
            return $this->selectNextRecordsProcessDetail($nextRecordsUrl);
        }
    }

    /**
     * 取得結果が2000件を超えている場合の次の2000件取得処理詳細
     *
     * @param string $nextRecordsUrl Forrest::queryの結果に含まれるURL
     * @return array 取得結果 最大2000件まで
     */
    private function selectNextRecordsProcessDetail($nextRecordsUrl)
    {
        return Forrest::next($nextRecordsUrl);
    }

    /**
     * SF情報登録
     *
     * @param string $object SFオブジェクト名
     * @param array $insert 登録情報
     * @return array 登録結果
     */
    public function insert($object, $insert)
    {
        try {
            return $this->insertProcessDetail($object, $insert);
        } catch (\Exception $e) {
            // エラーの場合、認証を実施
            $this->sfAuthentication();
            return $this->insertProcessDetail($object, $insert);
        }
    }

    /**
     * SF情報登録処理詳細
     *
     * @param string $object SFオブジェクト名
     * @param array $insert 登録情報
     * @return array 登録結果
     */
    private function insertProcessDetail($object, $insert)
    {
        return Forrest::sobjects($object, [
            'method' => 'post',
            'body' => $insert,
        ]);
    }

    /**
     * SF情報更新
     *
     * @param string $object SFオブジェクト名
     * @param string $id SFID
     * @param array $update 更新情報
     * @return 更新結果 (Forrestが結果を返さない為、基本nullなハズ)
     */
    public function update($object, $id, $update)
    {
        try {
            return $this->updateProcessDetail($object, $id, $update);
        } catch (\Exception $e) {
            // エラーの場合、認証を実施
            $this->sfAuthentication();
            return $this->updateProcessDetail($object, $id, $update);
        }
    }

    /**
     * SF情報更新処理詳細
     *
     * @param string $object SFオブジェクト名
     * @param string $id SFID
     * @param array $update 更新情報
     * @return 更新結果 (Forrestが結果を返さない為、基本nullなハズ)
     */
    private function updateProcessDetail($object, $id, $update)
    {
        return Forrest::sobjects("{$object}/{$id}", [
            'method' => 'patch',
            'body' => $update,
        ]);
    }

    /**
     * SF情報削除
     *
     * @param string $object SFオブジェクト名
     * @param string $id SFID
     * @return 削除結果 (Forrestが結果を返さない為、基本nullなハズ)
     */
    public function delete($object, $id)
    {
        try {
            return $this->deleteProcessDetail($object, $id);
        } catch (\Exception $e) {
            // エラーの場合、認証を実施
            $this->sfAuthentication();
            return $this->deleteProcessDetail($object, $id);
        }
    }

    /**
     * SF情報削除処理詳細
     *
     * @param string $object SFオブジェクト名
     * @param string $id SFID
     * @return 削除結果 (Forrestが結果を返さない為、基本nullなハズ)
     */
    private function deleteProcessDetail($object, $id)
    {
        return Forrest::sobjects("{$object}/{$id}", [
            'method' => 'delete',
        ]);
    }

    /**
     * クエリビルダをSQL形式に変換
     *
     * @param Builder $builder Builderオブジェクト
     * @return string $sql
     */
    public function convertToSql($builder)
    {
        $sql = preg_replace_array('/\?/', $builder->getBindings(), $builder->toSql());
        $sql = str_replace("`", '', $sql);

        return $sql;
    }

    /**
     * 文字列をシングルクォートで括る
     *
     * @param string 文字列
     * @return string シングルクォートで括った文字列
     */
    public function quote($str)
    {
        return "'" . $str . "'";
    }

    /**
     * 先頭と末尾の半角＆全角スペースを除去
     *
     * @param string $str 文字列
     * @return string スペース除去文字列
     */
    public function kanaTrim($str)
    {
        if (mb_strlen($str) > 0) {
            mb_internal_encoding('UTF-8');
            mb_regex_encoding('UTF-8');
            $space = ' 　';

            return mb_ereg_replace("[$space]*$", '', mb_ereg_replace("^[$space]*", '', $str));
        }
    }

    /**
     * SF認証
     *
     */
    private function sfAuthentication()
    {
        Forrest::revoke();
        Forrest::authenticate();
    }

    /**
     * 配列の各値をシングルクォートで囲む
     *
     * @param array $values
     * @return array
     */
    public function addQuotesValues($values): array
    {
        return array_map(fn($value) => "'{$value}'", $values);
    }
}
