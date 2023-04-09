<?php

declare(strict_types=1);

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\Responses\MakeJsonResponse;
use App\Http\Requests\Profile\UpdateRequest;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseProfileController extends Controller
{

    use MakeJsonResponse;


    /**
     * @param UserRepositoryInterface $repository
     */
    public function __construct(private UserRepositoryInterface $repository)
    {
    }


    abstract public function show(): View;


    /**
     * Update personal data
     *
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        if ($this->repository->update($request->validated(), $request->user()->id)) {

            return $this->showMessage(message: trans('server.record_updated'));
        } else {

            return $this->errorResponseWithBag(
                collection: ['server' => [trans('server.internal_error')]],
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    /**
     * Update password
     *
     * @param UpdatePasswordRequest $request
     * @return JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        if ($this->repository->updatePassword($request->safe()->only(['password']), $request->user()->id)) {

            return $this->showMessage(message: trans('passwords.updated'));
        } else {

            return $this->errorResponseWithBag(
                collection: ['server' => [trans('server.internal_error')]],
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
