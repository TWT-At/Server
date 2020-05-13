<?php

namespace App\Http\Middleware;

use App\Student;
use Closure;
use Illuminate\Support\Facades\Cache;

class CacheActivity
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
        if($request->session()->has("name"))
        {
            $name=$request->session()->get("name");
            if(Cache::has($name))
            {
                return $next($request);
            }else{
                Cache::put($name,"online",600);
                return $next($request);
            }
        }else{
            $name=Student::where('student_id',$request->input("student_id"))->value('name');
            Cache::put($name,'online',600);
            return $next($request);
        }
    }



}
