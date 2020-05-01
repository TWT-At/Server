<?php

namespace App\Http\Middleware;

use App\Permission;
use Closure;

class CheckPermission
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
        //$role=$request->input("role");

        $role=$request->session()->get("group_role");//获取用户角色
        $operation=$request->input("operation");
        $authorize=Permission::where("role",$role)->value($operation);
        if($authorize)
        {
            return $next($request);
        }
        return response()->json([
            "error_code" => 1,
            "message" => "角色权限不允许支持此操作",
        ]);
    }
}
