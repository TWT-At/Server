<?php

namespace App\Http\Controllers;

use App\Student;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class AlterController extends Controller
{
    public function password(Request $request)
    {
        $password=$request->input("password");
        $id=$request->input("id");
        $student=Student::find($id);
        $student->password=$password;
        $student->save();
    }

    public function image(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $id=$request->input("id");
            $id=intval($id);
            $avatar=$request->file("avatar");
            if($avatar->isValid())
            {
                $oragnalName=$avatar->getClientOriginalName();
                $ext=$avatar->getClientOriginalExtension();
                $type=$avatar->getClientMimeType();
                $realPath=$avatar->getRealPath();
                $file_new_name=date('Y-m-d-H-i-s').'-'.uniqid().'.'.$ext;
                Storage::disk('image')->put($file_new_name,file_get_contents($realPath));

                $student=Student::find($id);
                $student->avatar=$file_new_name;
                $student->save();

            }
        }
    }

    public function GetAvatar(Request $request)//用户头像展示
    {
        $id=$request->session()->get("id");

        $file_name=Student::where("id",$id)->value("avatar");
        $file_path="image/".$file_name;
        $avatar=Storage::get($file_path);
        return $avatar;
    }
}
