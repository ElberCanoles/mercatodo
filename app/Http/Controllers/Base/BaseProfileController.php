<?php

declare(strict_types=1);

namespace App\Http\Controllers\Base;

use App\Contracts\Repository\User\UserWriteRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateRequest;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

abstract class BaseProfileController extends Controller
{
    use MakeJsonResponse;

    public function __construct(private readonly UserWriteRepositoryInterface $writeRepository)
    {
    }

    abstract public function show(): View;

    /**
     * Update personal data
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        if ($this->writeRepository->update(data: $request->validated(), id: $request->user()->id)) {
            return $this->showMessage(message: trans(key: 'server.record_updated'));
        } else {
            return $this->errorResponseWithBag(
                collection: ['server' => [trans(key: 'server.internal_error')]]
            );
        }
    }

    /**
     * Update password
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        if ($this->writeRepository->updatePassword(data: $request->safe()->only(['password']), id: $request->user()->id)) {
            return $this->showMessage(message: trans(key: 'passwords.updated'));
        } else {
            return $this->errorResponseWithBag(
                collection: ['server' => [trans(key: 'server.internal_error')]]
            );
        }
    }
}
