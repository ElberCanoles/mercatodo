<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\Responses\ApiResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    use ApiResponse;
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        if ($request->wantsJson()) {
            return $this->showAll(collection: $this->repository->all($request->all()));
        } else {
            return view('admin.users.index');
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        return view('admin.users.edit', [
            'user' => $this->repository->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        return $this->repository->update($request->all(), $id);
    }
}
