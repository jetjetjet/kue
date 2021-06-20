<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;
use webcarrent\Repositories\SettingRepository;

class Authenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::check()) {
            return redirect()->guest('login');
        }
        //$user = new \stdClass();
        // $user->id = Auth::user()->getAuthIdentifier();
        // $user->name = Auth::user()->getField('username');
        // $user->currentUrl = url()->current();
        return $next($request);
    }
}
