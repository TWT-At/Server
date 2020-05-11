<?php

namespace App\Http\Controllers;

use App\Student;
use App\WeekPublicationComment;
use App\WeekPublicationLoveComment;
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
        $comment=$request->input("comment");
        $WeekPublicationScore=new WeekPublicationScore([
            "WeekPublication_id" => $WeekPublication_id,
            "author" => $author,
            "scorer" => $scorer,
            "score" => $score,
            "comment" => $comment,
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

    public function CommentMessage(Request $request)
    {
        $name=$request->session()->get("name");
        $to_publication_id=$request->input("week_publication_id");//评论的周报id
        $to_author=$request->input("author");
        $comment=$request->input("comment");
        $WeekPublicationComment=new WeekPublicationComment([
           "to_publication_id" => $to_publication_id,
           "to_author" => $to_author,
           "comment" => $comment,
           "name" => $name,
        ]);
        if($WeekPublicationComment->save())
        {
            return response()->json([
                "error_code" => 0,
            ]);
        }
        return response()->json([
            "error_code" => 1,
            "message" => "评论失败",
        ]);

    }

    public function LoveMessageComment(Request $request)
    {
        $to_comment_id=$request->input("comment_id");
        $user_id=$request->session()->get("id");
        $WeekPublicationLoveComment=new WeekPublicationLoveComment([
            "to_comment_id" =>$to_comment_id,
            "user_id" => $user_id,
        ]);
        if($WeekPublicationLoveComment->save())
        {
            return response()->json([
                "error_code" => 0,
            ]);

        }
        return response()->json([
            "error_code" => 1,
            "message" => "点赞失败",
        ]);
    }
}
