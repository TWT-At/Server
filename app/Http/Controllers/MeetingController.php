<?php

namespace App\Http\Controllers;

use App\Meeting;
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
        $Meeting=new Meeting([
            "user_id" => $user_id,
            "BeginTime" => $BeginTime,
            "EndTime" => $EndTime,
            "DateTime" => $DataTime,
            "campus" => $campus,
            "topic" => $topic,
        ]);
        $Meeting->save();
        if($Meeting)
        {
            return response()->json([
                "error_code" => 0,
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
        $data=Meeting::where(["DateTime" => $date,"campus" => $campus])->select("id","topic","BeginTime","EndTime","campus")->get();
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
        $Meeting=Meeting::destroy([$id]);
        if($Meeting)
        {
            return response()->json([
                "error_code" => 0
            ]);

        }
        return response()->json([
            "error_code" => 1,
            "message" => "删除失败",
        ]);
    }
}
