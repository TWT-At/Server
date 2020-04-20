<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProjectMember;
use App\Task;
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

            $projectmember=new ProjectMember([
                "project_id" => $project_id,
                "user_id" => $user_id,
                "name" => $name,
                "permission" => $permission,
                "group_name" => $group,
            ]);
            $projectmember->save();
            $i++;
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

    }

    public function ShowMyProject(Request $request)//展示我的项目
    {

    }

    public function ShowBasicProject()//展示基础项目
    {
        $BasicProject=Project::where('id','>',0)->select('name','title');
        if($BasicProject)
        {
            return response()->json([
               "error_code" => 0,
               "data" => $BasicProject,
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

        $title=Project::where("id",$project_id)->value("title");
        $description=Project::where("id",$project_id)->value("description");
        $process=Project::where("id",$project_id)->value("process");
        $member=ProjectMember::where("project_id",$project_id)->select("name","created_at")->get();

    }
}
