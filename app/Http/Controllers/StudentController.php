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
            $time=time();
            $date=floor(($time-$created_at)/86400);

            /*设置session*/
            $request->session()->put("name",$name);
            $request->session()->put("group",$group);
            $request->session()->put("date",$date);
            $request->session()->put("student",$student);

            //$_SESSION["name"]=$name;
            return view("main");
        }
        else return "账号不存在或密码错误";

    }
    public function judge($student_id,$password)
    {
        return Student::where("student_id",$student_id)->value('password')==$password;
    }


}
