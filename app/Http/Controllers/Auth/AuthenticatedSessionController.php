<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($request->user()->hasRole('office')) {
            return redirect()->route('office.dashboardo');
        } elseif ($request->user()->hasRole('laboratory')) {
            return redirect()->route('laboratory.dashboardo');
        } elseif ($request->user()->hasRole('dean')) {
            return redirect()->route('dean.dashboard');
        } elseif ($request->user()->hasRole('superadmin')) {
            return redirect()->route('superadmin.dashboard');
        } else if ($request->user()->hasRole('site secretary')) {
            return redirect()->route('office.dashboardo');
        }

        return redirect()->route('user.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
