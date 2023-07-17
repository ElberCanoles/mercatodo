<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Buyer\Profile;

use App\Http\Controllers\Web\Base\BaseProfileController;
use Illuminate\Contracts\View\View;

final class ProfileController extends BaseProfileController
{
    public function show(): View
    {
        return view(view: 'buyer.profile.show', data: [
            'user' => request()->user(),
        ]);
    }
}
