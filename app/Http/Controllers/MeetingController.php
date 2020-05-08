<?php

namespace App\Http\Controllers;

use App\Meeting;
use App\Message;
use App\Student;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function DestineMeeting(Request $request)
    {
        $BeginTime=$request->input("BeginTime");
        $EndTime=$request->input("EndTime");
        $DataTime=$request->input("DateTime");
        $campus=$request->input("campus");
        $topic=$request->input("topic");
        $user_id=$request->session()->get("id");
        $attend_user=$request->input("attend_user");
        $Meeting=new Meeting([
            "user_id" => $user_id,
            "BeginTime" => $BeginTime,
            "EndTime" => $EndTime,
            "DateTime" => $DataTime,
            "campus" => $campus,
            "topic" => $topic,
            "attend_user" => $attend_user,
        ]);
        $Meeting->save();

        if($Meeting)
        {
            $message="您有一场".$topic."会议将在".$BeginTime."开始";
            $this->PostToMessage($attend_user,$topic,$message);
            return response()->json(["error_code" => 0,
            ]);
        }
        return response()->json([
            "error_code" => 1,
            "message" => "会议预定失败"
        ]);
    }

    public function ShowMeeting(Request $request)
    {
        $date=$request->input("date");
        $campus=$request->input("campus");
        $data=Meeting::where(["DateTime" => $date,"campus" => $campus])->select("id","topic","BeginTime","EndTime","campus","attend_user")->get();
        if($data)
        {
            return response()->json([
                "error_code" => 0,
                "data" => $data,
            ]);
        }
        else{
            return response()->json([
                "error_code" => 1,
                "message" => "获取失败",
            ]);
        }
    }

    public function DeleteMeeting(Request $request)
    {
        $id=$request->input("meeting_id");
        $attend_user=Meeting::where('id',$id)->value('attend_user');
        $topic=Meeting::where('id',$id)->value('topic');
        $BeginTime=Meeting::where('id',$id)->value('BeginTime');
        $message="您将在".$BeginTime."开始的会议".$topic."已被取消";
        $Meeting=Meeting::destroy([$id]);
        if($Meeting)
        {
            $this->PostToMessage($attend_user,$topic,$message);
            return response()->json([
                "error_code" => 0
            ]);

        }
        return response()->json([
            "error_code" => 1,
            "message" => "会议取消失败",
        ]);
    }

    public function ChangeMeeting(Request $request)
    {
        $id=$request->input("meeting_id");
        $BeginTime=$request->input("BeginTime");
        $EndTime=$request->input("EndTime");
        $DateTime=$request->input("DateTime");
        $Meeting=Meeting::find($id);
        $Meeting->BeginTime=$BeginTime;
        $Meeting->EndTime=$EndTime;
        $Meeting->DateTime=$DateTime;
        $Meeting->save();
        if($Meeting)
        {
            $topic=Meeting::where("id",$id)->value("topic");
            $attend_user=Meeting::where("id",$id)->value("attend_user");
            $message="您的会议".$topic."已经修改到".$BeginTime;
            $this->PostToMessage($attend_user,$topic,$message);
            return response()->json([
                "error_code" => 0,
            ]);
        }
        return response()->json([
            "error_code" => 1,
            "message" => "会议修改失败",
        ]);
    }


    public function PostToMessage($attend_user,$title,$message)
    {
        $users=explode($attend_user,';');
        $UserID=array();
        $i=0;$j=0;
        while ($users[$i])
        {
            $user_id=Student::where('name',$users[$i])->value("id");
            $UserID[$i]=$user_id;
            $i++;
        }
        while ($UserID[$j])
        {
            $type="【会议信息】";
            $Message=new Message([
                "user_id" => $UserID[$j],
                "type" => $type,
                "title" => $title,
                "message" => $message
            ]);
            $Message->save();
            $j++;
        }
    }

}
