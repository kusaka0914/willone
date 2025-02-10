<?php

declare(strict_types=1);

namespace App\UseCases\Sidebars;

class MakePopularSearchUrlUseCase
{
    /**
     * サイドバーの人気検索条件リンク生成
     *
     * @param string $targetRouteName
     * @param string|null $typeRoma
     * @param string $prefRoma
     * @param string|null $stateRoma
     * @return string|null
     */
    final public function __invoke(
        string $targetRouteName,
        ?string $typeRoma,
        string $prefRoma,
        ?string $stateRoma
    ): ?string {
        switch ($targetRouteName) {
            case 'AreaStateSelectEkichika5':
                # /woa/area/{pref}/{state}/ekichika5/{page?}
                return route($targetRouteName, [
                    'pref' => $prefRoma,
                    'state' => $stateRoma
                ]);
            case 'JobAreaSelectEkichika5':
                # /woa/job/{id}/{pref}/ekichika5/{page?}
                return route($targetRouteName, [
                    'id' => $typeRoma,
                    'pref' => $prefRoma
                ]);
            case 'JobAreaStateSelectEkichika5':
                # /woa/job/{id}/{pref}/{state}/ekichika5/{page?}
                return route($targetRouteName, [
                    'id' => $typeRoma,
                    'pref' => $prefRoma,
                    'state' => $stateRoma
                ]);
        }

        return null;
    }
}
