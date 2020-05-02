<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function UpdatePermission(Request $request)//更改权限,接受一个JSON对象
    {
        $data=$request->all()["data"];
        $role=$data["role"];
        $permission=$data["permission"];
        foreach ($permission as $key => $value)
        {
            Permission::where("permission",$key)->update([$role => $value]);
        }

    }

    public function DeleteRole(Request $request)
    {
        $role=$request->input("role");
        Permission::where('role',$role)->delete();
    }

    public function AddRole(Request $request)//增加角色，接受一个JSON对象
    {
        $data=$request->all()["data"];
        $role=$data["role"];
        $permission=$data["permission"];
        $NewRole=new Permission;
        $NewRole->role=$role;//设置角色
        foreach($permission as $key => $value)
        {
            $NewRole->$key=$value;
        }
        $NewRole->save();
    }

    public function StopRole(Request $request)
    {
        $role=$request->input("role");
        $Stop=Permission::where("role",$role)->update(["stop",1]);
    }

    public function SetDefault(Request $request)
    {
        $role=$request->input("role");
        $Empty=Permission::where('default',1)->update(["default",0]);
        $Update=$request->where("role",$role)->update(["default",1]);
    }

    public function RecoverRole(Request $request)
    {
        $role=$request->input("role");
        $Recover=Permission::where("role",$role)->update(["stop" => 0]);
    }
}
