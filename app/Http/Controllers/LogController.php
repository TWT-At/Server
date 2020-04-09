<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function upload_log(Request $request)
    {

        $author=$request->session()->get("name");
        $title=$request->input("title");
        $content=$request->input("content");
        $number=DB::table("work_log")->insert([
                ["author" => $author,
                 "title" => $title,
                 "content" => $content],
            ]
        );
        if($number>0)return true;
        return false;
    }

    public function get_log()
    {
        $log=DB::table('work_log')->select('id','author','title','content')->get();
        return response()->json([
            "log" => $log,
        ]);


    }

    public function delete_log(Request $request)
    {
        $id=$request->input("id");
        $boolen=DB::table("work_log")->where("id",$id)->delete();
        return $boolen;
    }
}
