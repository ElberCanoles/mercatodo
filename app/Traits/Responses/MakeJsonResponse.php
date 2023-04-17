<?php

declare(strict_types=1);

namespace App\Traits\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait MakeJsonResponse
{

    /**
     * Get Json success response representation
     *
     * @param $data
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($data, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $code);
    }


    /**
     * Get Json failure response representation
     *
     * @param string $message
     * @param integer $code
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }


    /**
     * Get Json failure response representation with errors bag
     *
     * @param array $collection
     * @param integer $code
     * @return JsonResponse
     */
    protected function errorResponseWithBag(array $collection, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json(['errors' => $collection, 'code' => $code], $code);
    }


    /**
     * Get Json success response with message representation
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function showMessage(string $message, int $code = Response::HTTP_OK): JsonResponse
    {
        return $this->successResponse(['message' => $message], $code);
    }
}
