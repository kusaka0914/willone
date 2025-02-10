<?php

namespace App\UseCases\Commands;

use App\Model\RegistrationInquiryDetail;
use Illuminate\Support\Collection;

class GetInquiryDataUseCase
{
    /**
     * 問い合わせ済み リストの取得
     *
     * @param string $templateId
     * @return Collection
     */
    final public function __invoke(string $templateId): Collection
    {
        return (new RegistrationInquiryDetail)->getInquiryData($templateId);
    }
}
