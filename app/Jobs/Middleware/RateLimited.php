<?php

namespace App\Jobs\Middleware;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class RateLimited
{

    /**
     * @param $job
     * @param $next
     * @throws \Illuminate\Contracts\Redis\LimiterTimeoutException
     */
    public function handle($job, $next)
    {
        Redis::throttle('key')
            ->block(0)->allow(1)->every(1)
            ->then(function () use ($job, $next) {
                $next($job);
            }, function () use ($job) {
                $job->release(1);
            });
    }
}
