<?php

namespace App\Http\Controllers;

use App\Student;
use App\WeekPubliction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;



class StudentController extends Controller
{

    public function save(Request $request)
    {
        if($request->session()->has("student_id"))
        {
            $Session=$request->session();
            $data=array(
              "id" => $Session->get("id"),
              "student_id" => $Session->get("student_id"),
              "name" => $Session->get("name"),
              "group" => $Session->get("group"),
              "date" => $Session->get("date"),
              "permission" => $Session->get("permission"),
              "hour" => $Session->get("hour"),
              "group_role" => $Session->get("group_role"),
              "token" => $Session->get("_token"),
              "WeekPublicationSituation" => $this->GetWeekPublicationSituation($Session->get("name"))
            );
            return response()->json(
                [
                    "error_code" => 0,
                    "data" => $data
                ]
            );
        }
        else{
            $student=$request->input("student_id");
            if($this->judge($request->input("student_id"), $request->input("password"))==1)
            {
                $id=Student::where("student_id",$student)->value("id");
                $name=Student::where("student_id",$student)->value("name");
                $group=Student::where("student_id",$student)->value("group_name");
                $group_role=Student::where("student_id",$student)->value("group_role");
                $created_at=strtotime(Student::where("student_id",$student)->value("created_at"));
                $permission=Student::where("student_id",$student)->value("permission");
                $time=time();
                $date=floor(($time-$created_at)/86400);
                $hour=floor(($time-$created_at)/3600);
                $WeekPublicationSituation=$this->GetWeekPublicationSituation($name);

                $request->session()->put("id",$id);
                $request->session()->put("student_id",$student);
                $request->session()->put("name",$name);
                $request->session()->put("group",$group);
                $request->session()->put("date",$date);
                $request->session()->put("permission",$permission);
                $request->session()->put("group_role",$group_role);
                $request->session()->put("hour",$hour);
                $request->session()->put("WeekPublicationSituation",$WeekPublicationSituation);


                return response()->json(
                    [
                        "error_code" => 0,
                        "data" =>
                            [
                                "id" => $id,
                                "name" => $name,
                                "group" => $group,
                                "student_id" => $student,
                                "date" => $date,
                                "hour" =>$hour,
                                "group_role" =>$group_role,
                                "permission" => $permission,
                                "token" => $request->session()->get("_token"),
                                "WeekPublicationSituation" => $WeekPublicationSituation,
                            ],
                    ]
                );
            }
            else return response()->json(
                [
                    "error_code" => 1,
                    "message" => "获取用户信息失败"
                ]
            );
        }

    }


    public function judge($student_id,$password)
    {
        $hash_password=Student::where("student_id",$student_id)->value('password');
        $judge=password_verify($password,$hash_password);
        return $judge;
    }


    public function getinfo(Request $request)
    {
        $id=$request->session()->get("id");
        $student_id=$request->session()->get("student_id");
        $name=$request->session()->get("name");
        $group=$request->session()->get("group");
        $date=$request->session()->get("date");
        $permission=$request->session()->get("permission");
        $hour=$request->session()->get("hour");
        $group_role=$request->session()->get("group_role");
        $token=$request->session()->get("_token");

        return response()->json(
            [
                "error_code" => 0,
                "data" =>
                    [
                        "id" => $id,
                        "name" => $name,
                        "group" => $group,
                        "student_id" => $student_id,
                        "date" => $date,
                        "permission" => $permission,
                        "group_role" =>$group_role,
                        "hour" =>$hour,
                        "token" => $token,
                        "WeekPublicationSituation" => $request->session()->get("WeekPublicationSituation"),
                    ]
            ]
        );
    }

    public function GetWeekPublicationSituation($name)
    {
        $max_id=DB::table("week_publication")->max("publication_id");
        $content=WeekPubliction::where('publication_id',$max_id)->value($name);
        if($content)
        {
            return true;
        }
        return false;
    }

}
