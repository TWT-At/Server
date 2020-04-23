<?php

namespace App\Http\Controllers;

use App\Imports\StudentImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function AddExcel(Request $request)
    {
        if($request->isMethod("POST"))
        {
            $excel=$request->file("excel");
            $ext=$excel->getClientOriginalExtension();
            if($excel->isValid()&&($ext=='xlsx'||$ext=='xls'))
            {
                if($this->CheckData($excel))
                {
                    Excel::import(new StudentImport,$excel);
                    return response()->json([
                        "error_code" => 0,
                    ]);
                }
                else return response()->json([
                    "error_code" => 1,
                    "message" => "上传格式不正确"
                ]);
            }
        }
    }

    public function CheckData($excel)//检查数据是否符合标准
    {
        $table=Excel::toArray(new StudentImport,$excel);
        $data=$table[0];
        $flag=true;
        foreach ($data as $key => $value)
        {

           $email=filter_var($value["email"],FILTER_VALIDATE_EMAIL);
           $name=preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/',$value["name"]);
           $student_id=preg_match('/^\d{10}$/',$value["student_id"]);
           $group_name=preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/',$value["group_name"]);
           $group_role=preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/',$value["group_role"]);
           $campus=preg_match('/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/',$value["campus"]);
           if(!($email||$name||$student_id||$group_name||$group_role||$campus))
           {
               $flag=false;
           }
        }
        return $flag;
    }
}
