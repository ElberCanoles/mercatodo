<?php

declare(strict_types=1);

namespace App\Domain\Shared\Traits\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait MakeJsonResponse
{
    /**
     * Get Json success response representation
     */
    protected function successResponse(mixed $data, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $code);
    }

    /**
     * Get Json failure response representation
     */
    protected function errorResponse(string $message, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    /**
     * Get Json failure response representation with errors bag
     */
    protected function errorResponseWithBag(array $collection, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json(['errors' => $collection, 'code' => $code], $code);
    }

    /**
     * Get Json success response with message representation
     */
    protected function showMessage(string $message, int $code = Response::HTTP_OK): JsonResponse
    {
        return $this->successResponse(['message' => $message], $code);
    }
}
