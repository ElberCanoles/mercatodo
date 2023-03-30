<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\EntryPoint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class EmailVerificationPromptController extends Controller
{
   
    /**
     * Display the email verification prompt.
     *
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(EntryPoint::resolveRedirectRoute())
                    : view('auth.verify-email');
    }
}
