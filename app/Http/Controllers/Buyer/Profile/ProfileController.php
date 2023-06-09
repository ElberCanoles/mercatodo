<?php

declare(strict_types=1);

namespace App\Http\Controllers\Buyer\Profile;

use App\Http\Controllers\Base\BaseProfileController;
use Illuminate\View\View;

final class ProfileController extends BaseProfileController
{
    public function show(): View
    {
        return view(view: 'buyer.profile.show', data: [
            'user' => request()->user(),
        ]);
    }
}
