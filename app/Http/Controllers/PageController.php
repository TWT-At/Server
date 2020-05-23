<?php

namespace App\Http\Controllers;

use App\Student;
use App\WeekPublicationComment;
use App\WeekPublicationLoveComment;
use App\WeekPublicationScore;
use App\WeekPubliction;
use DateTime;
use Illuminate\Database\QueryException;
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

    public function  GetMessage(Request $request)
    {
        $semester=$request->input("semester");
        $DateRange = $this->GetStartData($semester);
        $StartTime = $DateRange[0];
        $EndTime = $DateRange[1];

        try {
            $publication = WeekPubliction::whereDate('created_at', '>=', $StartTime)
                ->whereDate('created_at', '<=', $EndTime)
                ->orderBy('created_at', 'desc')->get();
        }catch (QueryException $queryException)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "获取周报失败",
                "cause" => $queryException
            ]);
        }
       $publication=json_decode($publication,true);
       $data=array();
       try {
           $student = Student::where('id', '>', 0)->select('name', 'campus', 'group_name')->get();
       }catch (QueryException $queryException)
       {
           return response()->json([
               "error_code" => 1,
               "message" => "获取成员信息失败",
               "cause" => $queryException
           ]);
       }

       for($i=0;$i<count($student,0);$i++)//构造响应
       {
           $data[$i]=array();
           $name=$student[$i]["name"];
           $data[$i]["name"]=$name;
           $data[$i]["campus"]=$student[$i]["campus"];
           $data[$i]["group"]=$student[$i]["group_name"];
           $WeekPublication=array();
           for ($j=0;$j<count($publication,0);$j++)
           {
               $publication_id=$publication[$j]["publication_id"];
               $period=$publication[$j]["period"];
               $created_at=$publication[$j]["created_at"];
               $update_at=$publication[$j]["update_at"];

               $content=null;
               if(isset($publication[$i]["name"]))
               {
                   $content=$publication[$i]["name"];
               }

               $status=null;
               if($content==null)$status="UnFinished";
               else $status="Finished";


               $student[$i]["WeekPublication"]=array();

               $WeekPublication[$j]["publication_id"]=$publication_id;
               $WeekPublication[$j]["period"]=$period;
               $WeekPublication[$j]["created_at"]=$created_at;
               $WeekPublication[$j]["update_at"]=$update_at;
               $WeekPublication[$j]["status"]=$status;

               $data[$i]["WeekPublication"]=$WeekPublication;

           }

       }



        if($publication)
        {
            return response()->json([
                "error_code" => 0,
                "data" => $data
            ]);
        }else{
            return response()->json([
                "error_code" => 1,
                "message" => "获取周报失败"
            ]);
        }
    }

    public function GetMessageDetail(Request $request)
    {
        $publication_id=$request->input("publication_id");
        $author=$request->input("author");
        try{
            $WeekPublication=WeekPubliction::where(["publication_id" => $publication_id])->value($author);
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "获取周报失败",
                "cause" => $queryException

            ]);
        }

        return response()->json([
            "error_code" => 0,
            "data" => $WeekPublication
        ]);
    }



    public function GetStartData($semester)//获取周报展示的开始日期和截止日期
    {
        $data=explode('-',$semester);

        $year=null;
        $split=null;

        if($data[2]=="1")
        {
            $year=$data[0];
            $split=2;
        }else{
            $year=$data[1];
            $split=1;
        }

        if($split==1){
            return array(
                0 => $year.'-02-17 00:00:00',
                1 => $year.'-09-01 00:00:00'
            );

        }else{
            return array(
                0 => $year.'-09-01 00:00:00',
                1 => (string)(intval($year)+1).'-02-17 00:00:00'
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
            $LoveSituation=$this->LoveSituation($comment_id,$request);
            $count=$LoveSituation["count"];
            $exist=$LoveSituation["exist"];
            $comment[$i]["love"]=array("count"=> $count,"exist" => $exist);

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

    public function LoveSituation($comment_id,Request $request)
    {
        $LoveComment=WeekPublicationLoveComment::where("to_comment_id",$comment_id)->select("user_id")->get();
        $count=count($LoveComment,0);
        $exist=0;
        for($i=0;$i<$count;$i++)
        {
            if($LoveComment[$i]["user_id"]==$request->session()->get("id"))
            {
                $exist=1;
            }
        }
        return ["count"=> $count,"exist"=>$exist];
    }
}
