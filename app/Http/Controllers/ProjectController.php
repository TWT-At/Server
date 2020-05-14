<?php

namespace App\Http\Controllers;

use App\Message;
use App\Project;
use App\ProjectLog;
use App\ProjectMember;
use App\Student;
use App\Task;
use App\TaskLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function CreateProject(Request $request)//创建新项目
    {
        $title=$request->input("title");
        $description=$request->input("description");
        $author_id=$request->session()->get("id");
        $name=$request->session()->get("name");
        $permission=1;

        $project = new Project([
            "title" => $title,
            "description" => $description,
            "author_id" => $author_id,
            "name" => $name,
            "permission" =>$permission,
        ]);//创建项目新模型

        $project->save();

        $project_id=Project::where("title",$title)->value("id");

        $projectmember=new ProjectMember([
            "project_id" => $project_id,
            "name" => $name,
            "user_id" => $author_id,
            "permission" => $permission,
            "group_name" => $request->session()->get("group_name"),
        ]);

        $projectmember->save();

        $projectLog=new ProjectLog([
            "project_id" => $project_id,
            "name" => $name,
            "description" => "创建了项目"
        ]);//增加日志

        $projectLog->save();
        $message=$name."创建了项目";

        $this->PostToMessage($project_id,"创建项目",$message);

    }

    public function AddMember(Request $request)//项目添加成员
    {
        $data=$request->all()["data"];//获取所有数据
        $i=0;
        foreach ($data as $everyone)
        {
            $project_id=$everyone[$i]["project_id"];
            $user_id=$everyone[$i]["id"];
            $name=$everyone[$i]["name"];
            $group=$everyone[$i]["group"];
            $permission=0;

            $ProjectMember=new ProjectMember([
                "project_id" => $project_id,
                "user_id" => $user_id,
                "name" => $name,
                "permission" => $permission,
                "group_name" => $group,
            ]);
            $ProjectMember->save();//更新task数据库

            $ProjectLog=new ProjectLog([
                "project_id" => $project_id,
                "name" => $request->session()->get("name"),
                "description" => $request->session()->get("name").'添加了成员'.$name,
            ]);

            $ProjectLog->save();//更新项目日志
            $i++;//循坏

            $message=$request->session()->get("name").'添加了成员'.$name;
            $this->PostToMessage($project_id,"人员加入",$message);
        }
    }



    public function CreateTask(Request $request)//创建新任务
    {
        $project_id=$request->input("project_id");
        $name=$request->session()->get("name");
        $title=$request->input("title");
        $description=$request->input("description");

        $task=new Task([
            "project_id" => $project_id,
            "name" => $name,
            "title" => $title,
            "description" => $description,
        ]);//新建任务模型

        $task->save();

        $ProjectLog=new ProjectLog([
            "project_id" => $project_id,
            "name" => $name,
            "description" => $name."发起任务【".$title."】",
        ]);
        $message=$name."发起任务【".$title."】";

        $ProjectLog->save();//更新项目日志
        $this->PostToMessage($project_id,"创建任务",$message);
    }

    public function ShowMyProject(Request $request)//展示我的项目
    {
         $id=$request->session()->get("id");
         $project_ids=ProjectMember::where("user_id",$id)->select("project_id")->get();
         $data=array();
         $i=0;
         foreach ($project_ids as $value)
         {
            $project_id=$value["project_id"];
            $data[$i]=Project::where("id",$project_id)->select("id","name","title","created_at")->get();
            $rate=$this->CalculateProjectRate($project_id);
            $task=$this->GetTaskDDL($project_id);
            $data[$i][0]["rate"]=$rate;
            $data[$i][0]["task"]=$task;
            $i++;
         }

         if($data){
             return response()->json([
                 "error_code" => 0,
                 "data" => $data,
             ]);
         }

         else{
             return response()->json([
                 "error_code" =>1,
                 ]);
         }
    }

    public function ShowBasicProject()//展示基础项目
    {
        $BasicProjects=Project::where('id','>',0)->select('id','name','title','created_at')->get();
        for ($i=0;$i<count($BasicProjects,0);$i++)
        {
            $rate=$this->CalculateProjectRate($i+1);
            $BasicProjects[$i]["rate"]=$rate;
        }

        if($BasicProjects)
        {
            return response()->json([
               "error_code" => 0,
               "data" => $BasicProjects,
            ]);
        }
        else{
            return response()->json([
                "error_code" => 1,
            ]);
        }
    }

    public function GetTaskDDL($project_id)//获取项目中任务ddl
    {
        $task=Task::where("project_id",$project_id)->select('title','deadline')->get();
        return $task;
    }

    public function ShowSpecifiedProject(Request $request)//获取特定项目信息
    {
        $project_id=$request->input("project_id");

        $title=Project::where("id",$project_id)->value("title");//项目标题

        $description=Project::where("id",$project_id)->value("description");//项目描述

        $process=Project::where("id",$project_id)->value("process");//项目进程

        $member=ProjectMember::where("project_id",$project_id)->select("name","group_name","permission","created_at")->get();//获取项目成员、组别、权限、加入时间
        for($i=0;$i<count($member,0);$i++)
        {
            $name=$member[$i]["name"];
            $MemberID=$this->GetMemberID($name);
            $TaskNum=$this->GetMemberNumOFTask($project_id,$name);
            $member[$i]["member_id"]=$MemberID;
            $member[$i]["task_num"]=$TaskNum;
        }

        $task=Task::where("project_id",$project_id)->select("name","title","description","process","created_at")->get();

        $log=ProjectLog::where("project_id",$project_id)->select("name","description","update_at")->get();

        $data["title"]=$title;
        $data["description"]=$description;
        $data["process"]=$process;
        $data["member"]=$member;
        $data["task"]=$task;
        $data["log"]=$log;
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
            ]);
        }

    }

    public function GetMemberID($name)
    {
        $ID=Student::where("name",$name)->value("id");
        return $ID;
    }

    public function GetMemberNumOFTask($project_id,$name)
    {
        $Num=count(Task::where([
            "project_id" => $project_id,
            "name" => $name
        ])->select('id')->get(),0);
        return $Num;
    }

    public function DelayTask(Request $request)
    {
        $project_id=$request->input("project_id");
        $task_id=$request->input("task_id");
        $deadline=$request->input("deadline");
        $reason=$request->input("reason");

        $task=Task::where("id",$task_id)->update([
            "deadline" => $deadline,
        ]);

        $task->save();
        $oldDeadline=Task::where("id",$task_id)->value("deadline");//获取设置的deadline
        $TaskTitle=Task::where("id",$task_id)->value("title");//获取任务标题

        $ProjectLog=new ProjectLog([
            "project_id" => $project_id,
            "name" => $request->session()->get("name"),
            "description" => "延迟任务【".$TaskTitle."】"."完结时间从【".$oldDeadline."】"."延期至【".$deadline."】"
            ."因为【".$reason."】",
        ]);
        $message="延迟任务【".$TaskTitle."】"."完结时间从【".$oldDeadline."】"."延期至【".$deadline."】"
            ."因为【".$reason."】";

        $ProjectLog->save();
        $this->PostToMessage($project_id,"延迟任务",$message);
    }

    public function DeleteTask(Request $request)
    {
        $project_id=$request->input("project_id");
        $TaskID=$request->input("task_id");
        $reason=$request->input("reason");

        $TaskTitle=Task::where("id",$TaskID)->value("title");

        $message="删除任务【".$TaskTitle."】"."删除原因为【".$reason."】";

        $task=Task::find($TaskID);
        $task->delete();

        if($task->trashed()){
            $ProjectLog=new ProjectLog([
                "project_id" => $project_id,
                "name" => $request->session()->get("name"),
                "description" => "删除任务【".$TaskTitle."】"."删除原因为【".$reason."】",
            ]);

            $ProjectLog->save();
            $this->PostToMessage($project_id,"删除任务",$message);
        }

    }

    public function FinishTask(Request $request)
    {
        $TaskID=$request->input("task_id");
        $process="Finished";
        $Task=Task::find($TaskID);
        $Task->process=$process;
        $Task->save();
    }

    public function RemoveMember(Request $request)
    {
        $project_id=$request->input("project_id");
        $name=$request->input("name");
        $user_id=$request->input("user_id");
        $reason=$request->input("reason");

        $id=ProjectMember::where(["project" => $project_id,"user_id" => $user_id])->value("id");
        ProjectMember::destroy([$id]);
        $message="删除成员【".$name."】删除原因为【".$reason."】";
        $this->PostToMessage($project_id,"人员退出",$message);
    }

    public function PostToMessage($project_id,$title,$message)
    {
        $IDGroup=ProjectMember::where("project",$project_id)->select("user_id")->get();
        foreach ($IDGroup as $EachArray)
        {
            $user_id=$EachArray["user_id"];
            $Message=new Message([
                "user_id" => $user_id,
                "type" => "【项目信息】",
                "title" => $title,
                "message" => $message,
                "read" => 0
            ]);
            $Message->save();
        }
    }

    public function GetEachSituation(Request $request)
    {
        $project_id=$request->input("project_id");
        $member=ProjectMember::where('project_id',$project_id)->select('name')->get();
        for($i=0;$i<count($member,0);$i++)
        {
            $name=$member[$i]["name"];
            $rate=$this->GetEveryRate($project_id,$name);
            /*$avatar_uri=Student::where('name',$name)->value("avatar");
            $file_path="image/".$avatar_uri;
            $avatar=Storage::get($file_path);
            $member[$i]["avatar"]=$avatar;*/
            $member[$i]["rate"]=$rate;
        }
        if($member)
        {
            return response()->json([
                "error_code" => 0,
                "data" => $member
            ]);
        }
        return response()->json([
            "error_code" => 1,
            "message" => "获取失败"
        ]);


    }

    public function LoadAvatar($name)
    {
        $avatar_uri=Student::where('name',$name)->value("avatar");
        $file_path="image/".$avatar_uri;
        $avatar=Storage::get($file_path);
        return $avatar;
    }

    public function GetUserDatum()
    {
        $student=Student::where('id','>',0)->select('id','group_name','campus','group_role','name')->get();
        if($student)
        {
            return response()->json([
                "error_code" => 0,
                "data" => $student,
            ]);
        }else{
            return response()->json([
                "error_code" => 1,
                "message" => "获取成员信息失败"
            ]);
        }
    }

    public function CalculateProjectRate($project_id)
    {
        $ProjectMembers=ProjectMember::where("project_id",$project_id)->select("name")->get();
        $num=0;
        $count=0;
        foreach ($ProjectMembers as $EachArray)
        {
            $name=$EachArray["name"];
            $num++;
            $count+=$this->GetEveryRate($project_id,$name);
        }
        if($num==0)return 0;
        return ($count/$num);
    }

    public function CalculateTaskRate($task_id)
    {
        $data=Task::where('id',$task_id)->select('deadline','created_at')->get();
        $created_at=strtotime($data[0]["created_at"]);
        $deadline=strtotime($data[0]["deadline"]);
        $TotalSpan=$deadline-$created_at;
        $PersentSpan=time()-$created_at;
        $count=($PersentSpan/$TotalSpan);
        $rate=($count<1)?$count:1;

        return $rate;
    }

    public function GetEveryRate($project_id,$name)
    {
        $num=0;
        $count=0;
        $TaskIDGroup=Task::where(["project_id"=>$project_id,"name"=>$name])->select("id")->get();
        foreach ($TaskIDGroup as $EachArray)
        {
            $id=$EachArray["id"];
            $num++;
            $count+=$this->CalculateTaskRate($id);

        }
        if($num==0)return 0;
        $rate=$count/$num;
        return $rate;
    }
}
