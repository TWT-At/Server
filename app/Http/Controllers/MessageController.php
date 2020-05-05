<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function GetMessage(Request $request)
    {
        $user_id=$request->session()->get("id");
        $message=Message::where("user_id",$user_id)->select("id","title","type","message","read","created_at")->get();
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
        $Message->save();
        if($Message)
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
