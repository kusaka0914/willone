<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;

/**
 * コマンドユーティリティ
 */
trait CommandUtility
{
    /**
     * 非同期バッチ実行
     *
     * @param string $command バッチコマンド
     * @return void
     */
    public static function asyncCommand($command)
    {
        $artisan = base_path() . '/artisan';
        $shell = "nohup php {$artisan} {$command} > /dev/null &";
        Log::info('Batch', ['command' => $command]);
        exec($shell);
    }
}
