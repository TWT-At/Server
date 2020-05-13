<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use App\Student;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function GetPM()
    {
        $group_name="产品组";
        $information=Student::where("group_name",$group_name)->select("id","name","group_name","group_role","campus")->get();
        if($information)
        {
            return response()->json([
                "error_code" => 0,
                "data" => $information,
            ]);
        }

        else{
            return response()->json([
                "error_code" => 1,
            ]);
        }
    }

    public function GetUI()
    {
        $group_name="设计组";
        $information=Student::where("group_name",$group_name)->select("id","name","group_name","group_role","campus")->get();
        if($information)
        {
            return response()->json([
                "error_code" => 0,
                "data" => $information,
            ]);
        }

        else{
            return response()->json([
                "error_code" => 1,
            ]);
        }
    }

    public function GetWeb()
    {
        $group_name="前端组";
        $information=Student::where("group_name",$group_name)->select("id","name","group_name","group_role","campus")->get();
        if($information)
        {
            return response()->json([
                "error_code" => 0,
                "data" => $information,
            ]);
        }

        else{
            return response()->json([
                "error_code" => 1,
            ]);
        }
    }

    public function GetBackEnd()
    {
        $group_name="后端组";
        $information=Student::where("group_name",$group_name)->select("id","name","group_name","group_role","campus")->get();
        if($information)
        {
            return response()->json([
                "error_code" => 0,
                "data" => $information,
            ]);
        }

        else{
            return response()->json([
                "error_code" => 1,
            ]);
        }
    }

    public function GetAndroid()
    {
        $group_name="安卓组";
        $information=Student::where("group_name",$group_name)->select("id","name","group_name","group_role","campus")->get();
        if($information)
        {
            return response()->json([
                "error_code" => 0,
                "data" => $information,
            ]);
        }

        else{
            return response()->json([
                "error_code" => 1,
            ]);
        }
    }

    public function GetIOS()
    {
        $group_name="IOS组";
        $information=Student::where("group_name",$group_name)->select("id","name","group_name","group_role","campus")->get();
        if($information)
        {
            return response()->json([
                "error_code" => 0,
                "data" => $information,
            ]);
        }

        else{
            return response()->json([
                "error_code" => 1,
            ]);
        }
    }

    public function GetComplex(Request $request)//通过post用户id获取用户相关所有信息
    {
        $id=$request->input("id");
        $student=Student::where("id",$id)->select("id","student_id","name","email","group_name","group_role","campus","permission","created_at")->get();
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

    public function JudgeOnline()//判断成员是否在线
    {
        $nameGroup=Student::where('id','>',0)->select('name')->get();
        $IfOnline=array();
        for($i=0;$i<count($nameGroup,0);$i++)
        {
            $name=$nameGroup[$i]["name"];
            if(Cache::has($name))
            {
                $IfOnline[$name]="online";
            }else{
                $IfOnline[$name]="offline";
            }
        }
        if($IfOnline)
        {
            return response()->json([
                "error_code" => 0,
                "online" => $IfOnline
            ]);
        }
        return response()->json([
            "error_code" => 1,
            "message" => "获取成员在线情况失败"
        ]);

    }



}
