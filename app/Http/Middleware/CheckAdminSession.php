<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdminSession
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
        if($request->session()->has("admin_account")&&$request->session()->has("admin_password"))
        {
            return $next($request);
        }
        else {
            return false;
        }
    }
}
