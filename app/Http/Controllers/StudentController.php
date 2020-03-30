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
            $name=Student::where("student_id",$student)->value("name");
            $request->session()->put("name",$name);
            return view("main",[
                "name" => $request->session()->get("name")
            ]);
        }
        else return "账号不存在或密码错误";

    }
    public function judge($student_id,$password)
    {
        return Student::where("student_id",$student_id)->value('password')==$password;
    }


}
