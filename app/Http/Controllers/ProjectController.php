<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProjectLog;
use App\ProjectMember;
use App\Task;
use App\TaskLog;
use Illuminate\Http\Request;

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

        $ProjectLog->save();//更新项目日志
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
        $BasicProjects=Project::where('id','>',0)->select('id','name','title')->get();

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

    public function ShowSpecifiedProject(Request $request)//获取特定项目信息
    {
        $project_id=$request->input("project_id");

        $title=Project::where("id",$project_id)->value("title");//项目标题
        $description=Project::where("id",$project_id)->value("description");//项目描述
        $process=Project::where("id",$project_id)->value("process");//项目进程
        $member=ProjectMember::where("project_id",$project_id)->select("name","group_name","permission","created_at")->get();//获取项目成员、组别、权限、加入时间

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

        $ProjectLog->save();
    }

    public function DeleteTask(Request $request)
    {
        $project_id=$request->input("project_id");
        $TaskID=$request->input("task_id");
        $reason=$request->input("reason");

        $TaskTitle=Task::where("id",$TaskID)->value("title");



        $task=Task::find($TaskID);
        $task->delete();

        if($task->trashed()){
            $ProjectLog=new ProjectLog([
                "project_id" => $project_id,
                "name" => $request->session()->get("name"),
                "description" => "删除任务【".$TaskTitle."】"."删除原因为【".$reason."】",
            ]);

            $ProjectLog->save();
        }



    }
}
