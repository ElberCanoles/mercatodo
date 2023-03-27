<?php

declare(strict_types=1);

namespace App\Traits\Responses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    private function successResponse($data, $code): JsonResponse
    {
        return response()->json($data, $code);
    }

    protected function errorResponse(string $message, int $code): JsonResponse
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function errorResponseWithBag(array $collection, int $code): JsonResponse
    {
        return response()->json(['errors' => $collection, 'code' => $code], $code);
    }

    protected function showAll($collection, $code = Response::HTTP_OK): JsonResponse
    {
        return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $instance, $code = Response::HTTP_OK): JsonResponse
    {
        return $this->successResponse($instance, $code);
    }

    protected function showMessage($message, $code = Response::HTTP_OK): JsonResponse
    {
        return $this->successResponse(['message' => $message], $code);
    }
}
