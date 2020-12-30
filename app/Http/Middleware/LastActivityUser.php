<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
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
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!is_null($user) && !$user->is_admin) {
            $user->is_online = Carbon::now("Europe/Moscow")->addMinutes(10);
            $user->save(['timestamps' => false]);
        }
        return $next($request);
    }
}
