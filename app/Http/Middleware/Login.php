<?php

namespace App\Http\Middleware;

use Closure;

class Login
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
        $data=$request->session()->has('name');
        if($result){
            echo "登陆成功";
        }
        $response = $next($request);
        echo 222;
        // Perform action

        return $response;
    }
}
