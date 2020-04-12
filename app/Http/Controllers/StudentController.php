<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class StudentController extends Controller
{
    public function login()
    {
        return view('student.login');
    }

    public function save(Request $request)
    {
        //$request->session()->flush();
        if($request->session()->has("student_id"))
        {
            $id=$request->session()->get("id");
            $student_id=$request->session()->get("student_id");
            $name=$request->session()->get("name");
            $group=$request->session()->get("group");
            $date=$request->session()->get("date");
            $permission=$request->session()->get("permission");
            //$SessionId=Session::getId();
            //return $SessionId;
            return response()->json(
                [
                    "error_code" => 0,
                    "data" =>
                        [
                            "name" => $name,
                            "group" => $group,
                            "student_id" => $student_id,
                            "date" => $date,
                            "permission" => $permission,
                        ]
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
                $created_at=strtotime(Student::where("student_id",$student)->value("created_at"));
                $permission=Student::where("student_id",$student)->value("permission");
                $time=time();
                $date=floor(($time-$created_at)/86400);

                $request->session()->put("id",$id);
                $request->session()->put("student_id",$student);
                $request->session()->put("name",$name);
                $request->session()->put("group",$group);
                $request->session()->put("date",$date);
                $request->session()->put("permission",$permission);

                $session_id=Session::getId();
                return response()->json(
                    [
                        "error_code" => 0,
                        "data" =>
                            [
                                "name" => $name,
                                "group" => $group,
                                "student_id" => $student,
                                "date" => $date,
                                "permission" => $permission,
                                "session_id" => $session_id,

                            ],
                    ]
                );
            }
            else return response()->json(
                [
                    "error_code" => 1,
                ]
            );
        }


    }
    public function judge($student_id,$password)
    {
        return Student::where("student_id",$student_id)->value('password')==$password;
    }


}
