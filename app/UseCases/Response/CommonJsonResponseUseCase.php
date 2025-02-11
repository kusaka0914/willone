<?php

declare(strict_types=1);

namespace App\UseCases\Response;

use Illuminate\Http\JsonResponse;

class CommonJsonResponseUseCase
{
    /**
     * @param array $data
     * @param int $status
     * @param string|null $errorMessage
     * @return JsonResponse
     */
    final public function __invoke(
        array $data = [],
        int $status = 200,
        ?string $errorMessage = null
    ): JsonResponse {
        $response = [
            'data' => $data,
            'status' => $status
        ];
        if (!empty($errorMessage)) {
            $response['error'] = $errorMessage;
        }

        return response()->json($response, $status);
    }
}
