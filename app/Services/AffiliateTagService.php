<?php

namespace App\Services;

class AffiliateTagService
{
    /**
     * アフィリエイト媒体の計測タグ(ASPレントラックス)設置対象のアクションパラメータかどうか
     *
     * @param string|null $actionParam アクションパラメータ
     * @return bool
     */
    public function isTargetRentracksTag(?string $actionParam): bool
    {
        if (preg_match("/^woa_aff_rt/", $actionParam)) {
            return true;
        }

        return false;
    }
}
