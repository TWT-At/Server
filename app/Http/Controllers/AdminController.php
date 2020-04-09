<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $account=$request->input("account");
        $password=$request->input("password");
    }

    public function search(Request $request)//查询用户资料
    {
        $option=$request->input("option");
        $content=$request->input("content");
        static $data;
        switch ($option)
        {
            case "id":
                $data=Student::where("id",$content)->select("id","student_id","name","email","group_name","permission")->get();
                break;
            case "student_id":
                $data=Student::where("student_id",$content)->select("id","student_id","name","email","group_name","permission")->get();
                break;

            case "name":
                $data=Student::where("name",$content)->select("id","student_id","name","email","group_name","permission")->get();
                break;
        }

        if($data)
        {
            return response()->json([
               "error_code" => 0,
               "data" => $data,
            ]);

        }
        else{
            return response()->json([
                "error_code" => 1,
                "data" => [],
            ]);
        }
    }

    public function remove(Request $request)//删除用户
    {
        $student_id=$request->input("student_id");
        Student::destroy([$student_id]);
    }

    public function add(Request $request)//添加用户
    {
        $name=$request->input("name");
        $student_id=$request->input("student_id");
        $password=$request->input("password");
        $email=$request->input("email");
        $group_name=$request->input("group_name");
        $test=DB::table("student")->insert([
                [
                    "name" => $name,
                    "student_id" => $student_id,
                    "password" => $password,
                    "email" => $email,
                    "group_name" => $group_name,
                    "permission" => 0,
                ],
        ]);
        return $test;
    }

    public function update(Request $request)
    {
        $student_id=$request->input("student_id");//这里写简单一点，限制只能输入学生id进行修改
        $update_option=$request->input("update_option");
        $update_content=$request->input("update_content");

        Student::where("student",$student_id)->update($update_option,$update_content);//更新


    }
}
