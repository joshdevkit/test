<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // return redirect(RouteServiceProvider::HOME);
                /**
                 * @var App\Models\User;
                 */
                $user = Auth::user();
                if ($user->hasRole('site secretary')) {
                    return redirect('/office/dashboard');
                } else if ($user->hasRole('laboratory')) {
                    return redirect('/laboratory/dashboardo');
                } else if ($user->hasRole('superadmin')) {
                    return redirect('/superadmin/dashboard');
                } else if ($user->hasRole('user')) {
                    return redirect('/user/dashboard');
                } else if ($user->hasRole('dean')) {
                    return redirect('/dean/dashboard');
                }
            }
        }

        return $next($request);
    }
}
