<?php

namespace App\UseCases\Breadcrumbs;

class MakeBreadcrumbsTextWithPageUseCase
{
    /**
     * 対象ページのパンくずテキスト(xページ目対応)を取得
     *
     * @param string $breadcrumbText
     * @param int|null $pageNo
     * @return string
     */
    final public function __invoke(
        string $breadcrumbText,
        ?int $pageNo
    ): string {
        if (empty($breadcrumbText)) {
            return '';
        }

        $pageNoText = '';
        if (!empty($pageNo) && $pageNo > 1) {
            $pageNoText = ' (' . $pageNo . 'ページ目)';
        }

        return $breadcrumbText . $pageNoText;
    }
}
