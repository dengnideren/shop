<?php

namespace App\Http\Middleware;

use Closure;

class Update
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        $time=date('H');
        if ($time<9||$time>17)
        {
            echo "<script>alert('当前非可访问时间，请在9：00-17：00时间内访问'),window.history.go(-1)</script>";die();
        }
        return $next($request);
    }
}
