<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function UpdatePermission(Request $request)//更改权限
    {
        $data=$request->all()["data"];
        $role=$data["role"];
        $permission=$data["permission"];
        foreach ($permission as $key => $value)
        {
            Permission::where("permission",$key)->update([$role => $value]);
        }

    }
}
