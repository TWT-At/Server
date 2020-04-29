<?php

namespace App\Http\Controllers;

use App\Message;
use App\Student;
use App\Admin;
use App\Project;
use App\Announce;
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
        $data=$request->all()["data"];
        if(!$this->CheckData($data))
        {
            return response()->json([
                "error_code" => 1,
                "message" => "输入信息格式不正确",
            ]);
        }
        foreach ($data as $EachMember)
        {
            $student=new Student;

            $student->name=$EachMember["name"];
            $student->student_id=$EachMember["student_id"];
            $student->password=$EachMember["password"];
            $student->group_name=$EachMember["group"];
            $student->group_role=$EachMember["group_role"];
            $student->email=$EachMember["email"];
            $student->campus=$EachMember["campus"];
            $student->permission=0;
            $student->save();
        }

        return response()->json([
            "error_code" => 0,
        ]);
    }

    public function CheckData($data)
    {
        $flag=true;
        foreach ($data as $EachMember)
        {
            $email=filter_var($EachMember["email"],FILTER_VALIDATE_EMAIL);
            $name=preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/',$EachMember["name"]);
            $student_id=preg_match('/^\d{10}$/',$EachMember["student_id"]);
            $group_name=preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/',$EachMember["group_name"]);
            $group_role=preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/',$EachMember["group_role"]);
            $campus=preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/',$EachMember["campus"]);
            if(!($email&&$name&&$student_id&&$group_name&&$group_role&&$campus))
            {
                $flag=false;
            }
        }
        return $flag;
    }

    public function update(Request $request)
    {

            $id=intval($request->input("id"));
            $student=Student::find($id);
            $student->update($request->all());
    }

    public function announce(Request $request)//管理员发布公告
    {
        $validate=$request->validate([
           "title" => "required",
            "content" => "required",
            "post_group" => "required",
        ]);
        if($validate)
        {
            $announce=new Announce;
            $announce->title=$request->input("title");
            $announce->content=$request->input("content");
            $announce->post_group=$request->input("post_group");

            $announce->save();
            $this->PostToMessage($request->input("title"),$request->input("content"),$request->input("post_group"));
            return response()->json([
                "error_code" => 0,
            ]);
        }
        return response()->json([
            "error_code" => 1,
        ]);
    }

    public function PostToMessage($title,$content,$post_group)
    {
        $IDGroup=Student::where("group_name",$post_group)->select("id")->get();
        foreach ($IDGroup as $EachArray)
        {
            $id=$EachArray["id"];
            $Message=new Message([
                "user_id" => $id,
                "type" => "【消息公告】",
                "title" => $title,
                "message" => $content,
                "read" => 0,
            ]);

            $Message->save();
        }

    }
}
