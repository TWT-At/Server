<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function upload_log(Request $request)
    {

        $author=$request->input("name");
        $title=$request->input("title");
        $content=$request->input("content");
        $bool=DB::table("work_log")->insert([
                ["author" => $author,
                 "title" => $title,
                 "content" => $content],
            ]
        );
        return $bool;
    }

    public function get_log()
    {
        $log=DB::table('work_log')->select('id','author','title','content')->get();
        return $log;
    }

    public function delete_log(Request $request)
    {
        $id=$request->input("id");
        DB::table("work_log")->where("id",$id)->delete();
    }
}
