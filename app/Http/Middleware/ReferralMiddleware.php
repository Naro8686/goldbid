<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;


class ReferralMiddleware
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
        if ($request->hasCookie('referral'))
            return $next($request);
        else {
            if ($referred_by = $request->query('ref') && $user = User::find($request->query('ref'))) {
                if ($user->fullProfile())
                    return redirect($request->fullUrl())->withCookie(cookie()->forever('referral', $request->query('ref')));
            }
        }

        return $next($request);
    }
}
