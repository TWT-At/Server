<?php

namespace App\Http\Controllers;

use App\Student;
use App\Admin;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function judge($account,$password)
    {
        return Admin::where("account",$account)->value("password")==$password;


    }
    public function login(Request $request)
    {
        /*$account=$request->input("account");
        $password=$request->input("password");*/
        if($request->session()->has("admin_account")&&$request->session()->has("admin_password"))
        {
            return response()->json([
                "error_code" => 0,
            ]);
        }
        else{
            $validate=$request->validate([
               "account" => "required",
                "password" => "required",
            ]);
            $account=$request->input("account");
            $password=$request->input("password");
            if($this->judge($account,$password))
            {
                $request->session()->put("admin_account",$account);
                $request->session()->put("admin_password",$password);
                return response()->json([
                    "error_code" => 0,
                    ]
                );
            }
            return response()->json([
                "error_code" => 1,
            ]);
        }
    }
    public function basic()
    {
        $basic=Student::all();
        $information=array();
        $i=0;
        foreach ($basic as $value)
        {
            //$i=0;
            $information[$i]["id"]=$value["id"];
            $information[$i]["name"]=$value["name"];
            $information[$i]["group"]=$value["group_name"];
            $information[$i]["group_value"]=$value["group_role"];
            $information[$i]["campus"]=$value["campus"];
            $i++;

        }
        return response()->json([
            "error_code" =>0,
            "information" => $information,
        ]);
    }

    public function complex(Request $request)//通过post用户id获取用户相关所有信息
    {
        $id=$request->input("id");
        $student=Student::where("id",$id)->select("id","student_id","name","email","group_name","group_role","campus","permission")->get();
        $created_at=strtotime(Student::where("id",$id)->value("created_at"));
        $time=time();
        $date=floor(($time-$created_at)/86400);
        $hour=floor(($time-$created_at)/3600);
        $student[0]["date"]=$date;
        $student[0]["hour"]=$hour;

        $project=Project::where("author_id",$id)->select("title","description","process")->get();
        $project_created_at=Project::where("author_id",$id)->select("created_at")->get();
        $i=0;
        foreach ($project_created_at as $each_created_at)
        {
            $project_hour=floor(($time-strtotime($each_created_at["created_at"]))/3600);
            $project[$i]["hour"]=$project_hour;
            $i++;
        }


        return response()->json([
            "error_code" => 0,
            "student" => $student,
            "project" => $project,
        ]);
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
        $id=$request->input("id");
        //return $student_id;
        $student=explode(";",$id);
        Student::destroy($student);


        //Student::destroy([$student_id]);
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
        $validate=$request->validate([
            "id" => "required",
            "student_id" => "required",
            "name" => "required",
            "group" => "required",
            "group_role" => "required",
            "campus" =>"required",
            "email" =>"required",
            "password" => "required",
        ]);
        if(!$validate)
        {
            return response()->json(
                [
                    "error_code" => 1,
                    "error_message" => "必须输入全部信息",
                ]
            );
        }
        else{
            $id=$request->input("id");
            $student_id=$request->input("student_id");
            $name=$request->input("name");
            $group_name=$request->input("group");
            $group_role=$request->input("group_role");
            $campus=$request->input("campus");
            $email=$request->input("email");
            $password=$request->input("password");

            $student=new Student(
            [
                "id" => $id,
                "student_id" => $student_id,
                "name" => $name,
                "group_name" => $group_name ,
                "group_role" => $group_role,
                "campus" => $campus,
                "email" => $email,
                "password" => $password,
            ]
            );

            $student->save();

        }

        //$id=$request->input("id");//这里写简单一点，限制只能输入学生id进行修改
        //$update_option=$request->input("update_option");
        //$update_content=$request->input("update_content");


        //Student::where("student",$student_id)->update($update_option,$update_content);//更新

    }
}
