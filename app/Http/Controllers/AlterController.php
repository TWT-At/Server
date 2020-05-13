<?php

namespace App\Http\Controllers;

use App\Message;
use App\Student;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class AlterController extends Controller
{
    public function password(Request $request)
    {
        $password=$request->input("password");
        $id=$request->session()->get("id");
        $student=Student::find($id);
        $hash_password=password_hash($password,PASSWORD_DEFAULT);
        $student->password=$hash_password;
        if($student->save())
        {
            $this->PostToMessage($id,"【账号基本信息变动】","更改密码");
            return response()->json([
                "error_code" => 0
            ]);
        }else{
            return response()->json([
                "error_code" => 1,
                "message" => "修改密码失败"
            ]);
        }
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
                $ext=$avatar->getClientOriginalExtension();
                $type=$avatar->getClientMimeType();
                $Type=explode("/",$type)[0];
                if($Type=="image")
                {
                    $realPath=$avatar->getRealPath();
                    $file_new_name=date('Y-m-d-H-i-s').'-'.uniqid().'.'.$ext;
                    Storage::disk('image')->put($file_new_name,file_get_contents($realPath));

                    $student=Student::find($id);
                    $student->avatar=$file_new_name;
                    $student->save();
                    return response()->json([
                        "error_code" => 0,
                    ]);

                }
                else{
                    return response()->json([
                        "error_code" => 1,
                        "message" => "上传失败，不是可上传的文件类型",
                    ]);
                }

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

    public function PostToMessage($id,$title,$message)
    {
        $Message=new Message([
            "user_id" => $id,
            "type" => "【系统消息】",
            "title" => $title,
            "message"=> $message,
            "read" => 0,
        ]);
        $Message->save();
    }
}
