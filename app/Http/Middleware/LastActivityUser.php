<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Closure;

class LastActivityUser
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && $user = Auth::user()) {
            $user->is_online = Carbon::now("Europe/Moscow")->addMinutes(10);
            $user->save(['timestamps' => false]);
        }
        return $next($request);
    }
}
