<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiHandler
{
    public function handle(int $code): JsonResponse
    {
        return match ($code) {
            Response::HTTP_NOT_FOUND => $this->jsonFormatException(
                message: 'server.not_found',
                code: Response::HTTP_NOT_FOUND
            ),

            Response::HTTP_UNAUTHORIZED => $this->jsonFormatException(
                message: 'server.unauthorized',
                code: Response::HTTP_UNAUTHORIZED
            ),

            Response::HTTP_FORBIDDEN => $this->jsonFormatException(
                message: 'server.forbidden',
                code: Response::HTTP_FORBIDDEN
            ),

            Response::HTTP_METHOD_NOT_ALLOWED => $this->jsonFormatException(
                message: 'server.method_not_allowed',
                code: Response::HTTP_METHOD_NOT_ALLOWED
            ),

            default => $this->jsonFormatException(
                message: 'server.internal_error',
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            ),
        };
    }

    private function jsonFormatException(string $message, int $code): JsonResponse
    {
        return response()->json(data: [
            'message' => trans(key: $message)
        ], status: $code);
    }
}
