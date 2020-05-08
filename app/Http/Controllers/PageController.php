<?php

namespace App\Http\Controllers;

use App\Student;
use App\WeekPublicationScore;
use App\WeekPubliction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function editor(Request $request)
    {
        $name=$request->session()->get("name");
        $message = $request->input("message");
        $max_id=DB::table("week_publication")->max("publication_id");
        $num=DB::table("week_publication")->where("publication_id",$max_id)->update(
            [$name => $message]
        );
        if($num>0)return true;
        return false;
    }

    public function  GetMessage()
    {
        $time=strtotime('2020-02-17 00:00:00');
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

    public function ScoreMessage(Request $request)
    {
        $WeekPublication_id=$request->input("WeekPublication_id");
        $author=$request->input("author");
        $scorer=$request->session()->get("name");
        $score=$request->input("score");
        $WeekPublicationScore=new WeekPublicationScore([
            "WeekPublication_id" => $WeekPublication_id,
            "author" => $author,
            "scorer" => $scorer,
            "score" => $score,
        ]);
        $WeekPublicationScore->save();
        if($WeekPublicationScore)
        {
            return response()->json([
                "error_code" => 0,
            ]);
        }
        return response()->json([
            "error_code" => 1,
            "message" => "打分失败",
        ]);
    }
}
