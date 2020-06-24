<?php

namespace App\Http\Middleware;


use App\Settings\Setting;
use Closure;
use Illuminate\Support\Facades\Auth;

class SiteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        if ($site = Setting::siteConfig()->first()) {
//            if (!$site->site_enabled) {
//                if (Auth::check() && Auth::user()->is_admin)
//                    return $next($request);
//                return redirect()->route('login');
//            }
//        }
        return $next($request);
    }
}
