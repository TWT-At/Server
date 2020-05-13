<?php

namespace App\Http\Controllers;

use App\Student;
use App\WeekPublicationComment;
use App\WeekPublicationLoveComment;
use App\WeekPublicationScore;
use App\WeekPubliction;
use DateTime;
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
        $DateRange = $this->GetStartData();
        $StartTime = $DateRange[0];
        $EndTime = $DateRange[1];

        $publication=WeekPubliction::whereDate('created_at','>=',$StartTime)
            ->whereDate('created_at','<=',$EndTime)
            ->orderBy('created_at','desc')->get();


        if($publication)
        {
            return response()->json([
                "error_code" => 0,
                "publication" => $publication
            ]);
        }else{
            return response()->json([
                "error_code" => 1,
                "message" => "获取周报失败"
            ]);
        }
    }



    public function GetStartData()//获取周报展示的开始日期和截止日期
    {
        $date = new DateTime(date('Y-m-d h:i:s',time()));
        $year=$date->format('Y');
        $month=$date->format('m');
        $day=$date->format('d');
        $DayHash=intval($month)*100+intval($day);
        if($DayHash>=217&&$DayHash<901)
        {
            return [$year.'-02-17',$year.'-09-01'];
        }else if($month<=2&&$month>0){
            return [strtotime(($year-1).'-09-01 00:00:00'),strtotime($year.'-02-17 00:00:00')];
        }else{
            return [strtotime($year.'-09-01 00:00:00'),strtotime(($year+1).'-02-17 00:00:00')];
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

    public function GetComment(Request $request)
    {
        $WeekPublicationID=$request->input("week_publication_id");
        $author=$request->input("author");
        $comment=WeekPublicationComment::where([
            "to_publication_id" => $WeekPublicationID,
            "to_author" => $author
        ])->select('id','name','comment','update_at')->get();

        for($i=0;$i<count($comment,0);$i++)
        {
            $comment[$i]["love"]=array();
            $comment_id=$comment[$i]["id"];
            $LoveSituation=$this->LoveSituation($comment_id);
            $count=$LoveSituation["count"];
            $exist=$LoveSituation["exist"];
            $comment[$i]["love"]=array("count"=> $count,"exist" => $exist);
            /*$comment[$i]["love"]["count"]=$count;
            $comment[$i]["love"]["exist"]=$exist;*/
        }
        if($comment)
        {
            return response()->json([
                "error_code" => 0,
                "data" => $comment
            ]);

        }else{
            return response()->json([
                "error_code" => 1,
                "message" => "获取评论失败"
            ]);
        }
    }

    public function LoveSituation($comment_id)
    {
        $LoveComment=WeekPublicationLoveComment::where("to_comment_id",$comment_id)->select("user_id")->get();
        $count=count($LoveComment,0);
        $exist=0;
        for($i=0;$i<$count;$i++)
        {
            if($LoveComment[$i]["user_id"]==\Illuminate\Support\Facades\Request::session()->get("id"))
            {
                $exist=1;
            }
        }
        return ["count"=> $count,"exist"=>$exist];
    }
}
