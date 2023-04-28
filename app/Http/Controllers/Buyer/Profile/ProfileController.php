<?php

declare(strict_types=1);

namespace App\Http\Controllers\Buyer\Profile;

use App\Http\Controllers\Base\BaseProfileController;
use Illuminate\View\View;

final class ProfileController extends BaseProfileController
{
    /**
     * Show the form for editing data profile.
     */
    public function show(): View
    {
        return view(view: 'buyer.profile.show', data: [
            'user' => request()->user(),
        ]);
    }
}
