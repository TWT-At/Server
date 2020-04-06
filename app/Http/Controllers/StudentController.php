<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use mysql_xdevapi\Session;

class StudentController extends Controller
{
    public function login()
    {
        return view('student.login');
    }

    public function save(Request $request)
    {
        $student=$request->input("student_id");
        if($this->judge($request->input("student_id"), $request->input("password"))==1)
        {
            /*从数据库取出数据*/
            $name=Student::where("student_id",$student)->value("name");
            $group=Student::where("student_id",$student)->value("group_name");
            $created_at=strtotime(Student::where("student_id",$student)->value("created_at"));
            $permission=Student::where("student_id",$student)->value("permission");
            $time=time();
            $date=floor(($time-$created_at)/86400);

            /*设置session*/
            $request->session()->put("name",$name);
            $request->session()->put("group",$group);
            $request->session()->put("date",$date);
            $request->session()->put("student",$student);
            $request->session()->put("permission",$permission);
            $session=$request->session()->all();
            //$_SESSION["name"]=$name;
            //return view("main");
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
                        ],
                    "session" => $session,
                ]
            );
        }
        else return response()->json(
            [
                "error_code" => 1
            ]
        );

    }
    public function judge($student_id,$password)
    {
        return Student::where("student_id",$student_id)->value('password')==$password;
    }


}
