<?php

namespace App\Http\Controllers;

use App\Meeting;
use App\MeetingAttendee;
use App\Message;
use App\Student;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        try {
            /*$Meeting = new Meeting([
                "user_id" => $user_id,
                "BeginTime" => $BeginTime,
                "EndTime" => $EndTime,
                "DateTime" => $DataTime,
                "campus" => $campus,
                "topic" => $topic,
                "attend_user" => $attend_user,
            ]);
            $Meeting->save();*/
            $meeting_id=Meeting::insertGetId([
                "user_id" => $user_id,
                "BeginTime" => $BeginTime,
                "EndTime" => $EndTime,
                "DateTime" => $DataTime,
                "campus" => $campus,
                "topic" => $topic,
                "attend_user" => $attend_user
            ]);

            $this->PostToMeetingAttendee($attend_user,$meeting_id);

        }catch (QueryException $queryException){
            return response([
                "error_code" => 1,
                "message" => "会议创建失败",
                "cause" => $queryException
            ]);
        }

            try {
                $message = "您有一场" . $topic . "会议将在" . $BeginTime . "开始";
                $this->PostToMessage($attend_user,$topic,$message);
            }catch (QueryException $queryException){
                return response()->json([
                    "error_code" => 1,
                    "message" => "发送消息失败",
                    "cause" => $queryException
                ]);
            }

            return response()->json([
                "error_code" => 0
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




    public function UploadPersonalPhoto(Request $request)
    {
        $photo=$request->file("photo");
        $user_id=$request->session()->get("id");
        if($photo->isValid())
        {
            $ext=$photo->getClientOriginalExtension();
            $type=explode("/",$photo->getMimeType())[0];
            if($type=="image")
            {
                $realPath=$photo->getRealPath();
                $file_new_name=date('Y-m-d-H-i-s').'-'.uniqid().'.'.$ext;
                Storage::disk('face')->put($file_new_name,file_get_contents($realPath));

                $student=Student::find($user_id);
                $student->people_face=$file_new_name;
                $student->save();
                return response()->json([
                    "error_code" => 0,
                ]);

            }
            else{
                return response()->json([
                    "error_code" => 1,
                    "message" => "上传失败，不是可上传的文件类型",
                ]);
            }

        }

    }

    public function FaceRecognition(Request $request)
    {
        if($this->GetAccessToken()["error_code"]==1)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "获取access_token失败"
            ]);
        }
        $Face=$request->file("face");
        $People=$this->GetPeopleImage();
        $access_token=$this->GetAccessToken()["access_token"];
        $URL="https://aip.baidubce.com/rest/2.0/face/v3/match?access_token=".$access_token;
        $curl=curl_init();
        $FaceImage=$this->ImgBase64Encode($Face,false);
        $PeopleImage=$this->ImgBase64Encode($People,true);
        $body=json_encode(array(
            0 =>
                array(
                    "image" => $FaceImage,
                    "image_type" => "BASE64"
                ),
            1 =>
                array(
                    "image" => $PeopleImage,
                    "image_type" => "BASE64"
                )
            )
        );

        curl_setopt($curl,CURLOPT_URL,$URL);
        curl_setopt($curl,CURLOPT_HEADER,0);

        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);

        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$body);

        $data=json_decode(curl_exec($curl));
        curl_close($curl);


        foreach ($data as $key => $value)
        {
            if($key=="error_code")
            {
                if($value==1)
                    return response()->json([
                        "error_code" => 1,
                        "message" => "识别失败"
                    ]);
            }
            if($key=="result")
            {
                foreach ($value as $k => $v)
                {
                    if($k=="score")
                    {
                       $score=$v;
                        return response()->json([
                            "error_code" => 0,
                            "score" => $score
                        ]);
                    }
                }
            }
        }


    }

    public function SignIn(Request $request)
    {
        $meeting_id=$request->input("meeting_id");
        $user_id=$request->session()->get("id");
        try {
            $id = MeetingAttendee::where(["meeting_id" => $meeting_id, "user_id" => $user_id])->value("id");
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "查询失败",
                "cause" => $queryException
            ]);
        }
        try {
            $MeetingAttendee = MeetingAttendee::find($id);
            $MeetingAttendee->status = 1;
            $MeetingAttendee->save();
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "更新失败",
                "cause" => $queryException
            ]);
        }
    }




    /*保护方法*/

    protected function PostToMeetingAttendee($attend_user,$meeting_id)
    {
        $attendee=explode(";",$attend_user);
        for($i=0;$i<count($attendee);$i++){
            $name=$attendee[$i];
            $user_id=Student::where("name",$name)->value("id");

            $MeetingAttendee=new MeetingAttendee([
                "user_id" => $user_id,
                "name" => $name,
                "meeting_id" => $meeting_id
            ]);
            $MeetingAttendee->save();
        }
    }

    protected function PostToMessage($attend_user,$title,$message)
    {
        $users=explode(";",$attend_user);
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





    protected function GetAccessToken()
    {
        $param=array(
            "grant_type" => "client_credentials",
            "client_id" => "nDVIpGo0TR97oAiOcFDwQt75",
            "client_secret" => "jFKjlVmtsjcmhcBHSktC3tCi6uzvWGbf"
        );
        $HTTPURL="https://aip.baidubce.com/oauth/2.0/token";
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,$HTTPURL);
        curl_setopt($curl,CURLOPT_HEADER,0);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$param);

        $data=json_decode(curl_exec($curl));
        curl_close($curl);

        $access_token="";
        foreach ($data as $key => $value)
        {
            if($key=="access_token")$access_token=$value;
        }
        if($access_token=="")
            return array(
                "error_code" => 1,
                "message" => "获取access_token失败"
            );
        return array(
            "error_code" => 0,
            "access_token" => $access_token
        );

    }


    protected function ImgBase64Encode($image,$ImgContent=false)//base64编码
    {
        $file_content="";
        if($ImgContent==false)
        {
            $file_content=file_get_contents($image);
        }else if($ImgContent==true){
            $file_content=$image;
        }

        if($file_content === false){
            return $image;
        }
        $base64 =chunk_split(base64_encode($file_content));
        return $base64;
    }


    protected function GetPeopleImage()
    {
        $user_id=\Illuminate\Support\Facades\Request::session()->get("id");
        $image_name=Student::where("id",$user_id)->value("people_face");
        $file_path="face/".$image_name;
        $face=Storage::get($file_path);
        return $face;
    }



}
