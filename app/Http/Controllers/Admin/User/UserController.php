<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class UserController extends Controller
{

    use MakeJsonResponse;

    private UserRepositoryInterface $repository;


    /**
     * New Admin/UserController instance
     *
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse|View
     */
    public function index(Request $request): JsonResponse|View
    {
        if ($request->wantsJson()) {

            return $this->successResponse(data: $this->repository->all($request->all()));
        } else {

            return view('admin.users.index');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param integer $id
     * @return View
     */
    public function edit(int $id): View
    {
        return view('admin.users.crud.edit', [
            'user' => $this->repository->find($id),
            'statuses' => $this->repository->allStatuses(),
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param integer $id
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        if ($this->repository->update($request->validated(), $id)) {

            return $this->showMessage(message: trans('server.record_updated'));
        } else {

            return $this->errorResponseWithBag(
                collection: ['server' => [trans('server.internal_error')]],
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
