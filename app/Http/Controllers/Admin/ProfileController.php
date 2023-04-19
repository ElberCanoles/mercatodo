<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\BaseProfileController;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\View\View;

final class ProfileController extends BaseProfileController
{
    public function __construct(UserRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Show the form for editing data profile.
     */
    public function show(): View
    {
        return view('admin.profile.show', [
            'user' => request()->user(),
        ]);
    }
}
