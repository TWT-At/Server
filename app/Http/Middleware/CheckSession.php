<?php

namespace App\Http\Middleware;

use Closure;
/*检查seesion中间件*/
class CheckSession
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

        if($request->session()->has("student_id")&&$request->header("token")==$request->session()->get("_token"))
        {

            return $next($request);
        }
        else{
            return response()->json([
                "error_code" => 401,
            ]);
        }
    }
}
