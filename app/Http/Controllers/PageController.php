<?php

namespace App\Http\Controllers;

use App\Student;
use App\WeekPubliction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function editor(Request $request)
    {
        //$id = $request->session()->get("id");
        $name=$request->session()->get("name");
        $message = $request->input("message");
        $max_id=DB::table("week_publication")->max("publication_id");

        //$date=(time()-(7*24*60*60));
        $num=DB::table("week_publication")->where("publication_id",$max_id)->update(
            [$name => $message]
        );
        if($num>0)return true;
        return false;
    }

    public function  GetMessage()
    {
        $time=time();
        $test_time=$time-(7*24*60*60);
        $publication_ids=WeekPubliction::where('created_at','>',$time)->
            orderBy('publication_id','asc')->get();


        $maps=Student::select('id','name')->get();


        if($publication_ids)
        {
            return response()->json(
                [
                    "error_code" => 0,
                    "week_message" => $publication_ids,

                ]
            );
        }
        else
        {
            return response()->json(
                [
                    "error_code" => 1,
                    "week_message" => [],
                ]
            );
        }
    }
}
