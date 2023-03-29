<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    use ApiResponse;

    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function show(): View
    {
        return view('buyer.profile.show', [
            'user' => request()->user()
        ]);
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        if ($this->repository->update($request->validated(), $request->user()->id)) {
            return $this->showMessage(message: trans('server.record_updated'));
        } else {
            return $this->errorResponseWithBag(collection: ['server' => [trans('server.internal_error')]],
                code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        if ($this->repository->updatePassword($request->safe()->only(['password']), $request->user()->id)) {
            return $this->showMessage(message: trans('passwords.updated'));
        } else {
            return $this->errorResponseWithBag(collection: ['server' => [trans('server.internal_error')]],
                code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
