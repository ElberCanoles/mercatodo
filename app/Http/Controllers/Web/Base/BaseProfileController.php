<?php declare(strict_types=1);

namespace App\Http\Controllers\Web\Base;

use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Domain\Users\Actions\UpdateUserAction;
use App\Domain\Users\Actions\UpdateUserPasswordAction;
use App\Domain\Users\DataTransferObjects\UpdateUserData;
use App\Domain\Users\DataTransferObjects\UpdateUserPasswordData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;

abstract class BaseProfileController extends Controller
{
    use MakeJsonResponse;

    public function __construct(
        private readonly UpdateUserAction         $updateUserAction,
        private readonly UpdateUserPasswordAction $updateUserPasswordAction)
    {
    }

    abstract public function show(): View;

    public function update(UpdateRequest $request): JsonResponse
    {
        $this->updateUserAction->execute(UpdateUserData::fromRequest($request), $request->user());
        return $this->showMessage(message: trans(key: 'server.record_updated'));
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $this->updateUserPasswordAction->execute(UpdateUserPasswordData::fromRequest($request), $request->user());
        return $this->showMessage(message: trans(key: 'passwords.updated'));
    }
}
