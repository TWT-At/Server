<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function GetMessage(Request $request)
    {

        $user_id=$request->session()->get("id");
        try {
            $message = Message::where(["user_id" => $user_id, "read" => 0])->select("id", "title", "type", "message", "read", "created_at")->get();
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "获取消息失败",
                "cause" => $queryException
            ]);
        }

        if($message)
        {
            return response()->json([
                "error_code" => 0,
                "data" => $message,
            ]);
        }
        else{
            return response()->json([
                "error_code" => 1,
            ]);
        }
    }

    public function UpdateRead(Request $request)
    {
        $message_id=$request->input("message_id");
        $status=$request->input("status");//获取更新状态（已读为1，未读为0)

        $Message=Message::find($message_id);
        $Message->read=$status;

        if($Message->save())
        {
            return response()->json([
                "error_code" => 0,
            ]);
        }
        else{
            return response()->json([
                "error_code" => 1,
            ]);
        }
    }
}
