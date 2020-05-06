<?php

namespace App\Http\Controllers;

use App\CloudDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CloudDriverController extends Controller
{
    public function UploadFile(Request $request)
    {
        $file=$request->file("file");
        if($file->isValid())
        {
            $user_id=$request->session()->get("id");
            $FileName=$file->getClientOriginalName();
            $ext=$file->getExtension();
            $FileSize=$file->getSize();
            if($FileSize<8000000)
            {
                $realPath=$file->getRealPath();
                $file_new_name=date('Y-m-d-H-i-s').'-'.uniqid().'.'.$ext;
                Storage::disk('file')->put($file_new_name,file_get_contents($realPath));
                $UploadFile=new CloudDriver([
                    "user_id" => $user_id,
                    "filename" => $FileName,
                    "filenewname" => $file_new_name
                ]);
                $UploadFile->save();
                return response()->json([
                    "error_code" => 0,
                ]);
            }
            return response()->json([
                "error_code" => 1,
                "message" => "文件过大",
            ]);
        }
        return response()->json([
            "error_code" => 1,
            "message" => "文件不合法",
        ]);
    }

    public function DeleteFile(Request $request)
    {
        $id=$request->input("FileID");
        $FileNewName=CloudDriver::where('id',$id)->value('filenewname');
        Storage::disk('file')->delete([$FileNewName]);
    }
}
