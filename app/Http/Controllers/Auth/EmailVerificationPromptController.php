<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if (!$request->user()) {
            return view('login');
        } elseif ($request->user() && !$request->user()->hasVerifiedEmail()) {
            return view('auth.verify-email');
        } else {

            return redirect(RouteServiceProvider::HOME);
        }
        // dd($request->user()->hasVerifiedEmail());
        // return $request->user()->hasVerifiedEmail()
        //     ? redirect()->intended(RouteServiceProvider::HOME)
        //     : view('auth.verify-email');
    }
}
