<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Admin\Profile;

use App\Http\Controllers\Web\Base\BaseProfileController;
use Illuminate\View\View;

final class ProfileController extends BaseProfileController
{
    public function show(): View
    {
        return view(view: 'admin.profile.show', data: [
            'user' => request()->user(),
        ]);
    }
}
