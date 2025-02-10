<?php

namespace App\UseCases;

use App\Services\JobNoteApiService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class JobNoteEntryUseCase
{
    final public function __invoke(Collection $woaCustomer): string
    {
        $jobnoteUrl = env('JOBNOTE_URL', 'https://job-note.jp');
        // jobnote仮登録との紐付け用キー発行
        $woaCustomer['woa_id'] = bcrypt(Str::random(10));
        $jobNoteApiService = new JobNoteApiService();
        $response = $jobNoteApiService->executeEntryApi($woaCustomer);

        // validation等でAPIエラーが出た際は同時登録せずWOAサンクスページを表示のみ
        if (empty($response) || $response->getStatusCode() != 200) {
            return '';
        }

        // jobnoteの画面へ遷移
        return $jobnoteUrl . '/entry/from-woa?woa_id=' . $woaCustomer['woa_id'];
    }
}
