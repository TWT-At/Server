<?php

namespace App\Http\Controllers;

use App\Message;
use App\Project;
use App\ProjectLog;
use App\ProjectMember;
use App\Student;
use App\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
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

        try {
            $project = new Project([
                "title" => $title,
                "description" => $description,
                "author_id" => $author_id,
                "name" => $name,
                "permission" => $permission,
            ]);//创建项目新模型

            $project->save();
        }catch (QueryException $exception)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "创建项目失败",
                "cause" => $exception
            ]);
        }

        $project_id=Project::where(["name" => $name, "title" => $title, "description" => $description])->value("id");

        try {
            $ProjectMember = new ProjectMember([
                "project_id" => $project_id,
                "name" => $name,
                "user_id" => $author_id,
                "permission" => $permission,
                "group_name" => $request->session()->get("group"),
            ]);

            $ProjectMember->save();
        }catch (QueryException $exception)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "添加到项目成员失败",
                "cause" => $exception
            ]);
        }

        try {
            $projectLog = new ProjectLog([
                "project_id" => $project_id,
                "name" => $name,
                "description" => "创建了项目"
            ]);//增加日志

            $projectLog->save();
        }catch (QueryException $exception)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "添加到日志失败",
                "cause" => $exception
            ]);
        }
        try {
            $message = $name . "创建了项目";

            $this->PostToMessage($project_id, "创建项目", $message);
        }catch (QueryException $exception)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "添加到日志失败",
                "cause" => $exception
            ]);
        }
        if($project_id)
        {
            return response()->json([
                "error_code" => 0,
                "project_id" => $project_id
            ]);
        }
        return response()->json([
            "error_code" => 1,
            "message" => "创建项目失败"
        ]);

    }

    public function AddMember(Request $request)//项目添加成员
    {
        $data=$request->all()["data"];//获取所有数据
        $project_id=$request->all()["project_id"];
        $i=0;
        foreach ($data as $everyone)
        {

            $user_id=$everyone["user_id"];
            $name=$everyone["name"];
            $group=$everyone["group"];
            $permission=0;
            try {
                $ProjectMember = new ProjectMember([
                    "project_id" => $project_id,
                    "user_id" => $user_id,
                    "name" => $name,
                    "permission" => $permission,
                    "group_name" => $group,
                ]);
                $ProjectMember->save();//更新task数据库
            }catch (QueryException $queryException){
                return response()->json([
                    "error_code" => 1,
                    "message" => "加入成员失败",
                    "cause" => $queryException
                ]);
            }
            try {
                $ProjectLog = new ProjectLog([
                    "project_id" => $project_id,
                    "name" => $request->session()->get("name"),
                    "description" => $request->session()->get("name") . '添加了成员' . $name,
                ]);

                $ProjectLog->save();//更新项目日志
            }catch (QueryException $queryException){
                return response()->json([
                    "error_code" => 1,
                    "message" => "更新日志失败",
                    "cause" => $queryException
                ]);
            }
            $i++;//循坏

            $message=$request->session()->get("name").'添加了成员'.$name;
            $this->PostToMessage($project_id,"人员加入",$message);
        }
        return response()->json([
            "error_code" => 0
        ]);
    }



    public function CreateTask(Request $request)//创建新任务
    {
        $project_id=$request->input("project_id");
        $name=$request->session()->get("name");
        $title=$request->input("title");
        $description=$request->input("description");
        $deadline=$request->input("deadline");

        try {
            $task = new Task([
                "project_id" => $project_id,
                "name" => $name,
                "title" => $title,
                "description" => $description,
                "deadline" => $deadline
            ]);//新建任务模型

            $task->save();
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "创建项目失败",
                "cause" => $queryException
            ]);
        }
        $task_id=Task::where(["project_id" => $project_id, "name" => $name, "title" => $title])->value("id");

        try {
            $ProjectLog = new ProjectLog([
                "project_id" => $project_id,
                "name" => $name,
                "description" => $name . "发起任务【" . $title . "】",
            ]);
            $message = $name . "发起任务【" . $title . "】";

            $ProjectLog->save();//更新项目日志
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "更新日志失败",
                "cause" => $queryException
            ]);
        }
        $this->PostToMessage($project_id,"创建任务",$message);
        if($task_id)
        {
            return response()->json([
                "error_code" => 0,
                "task_id" => $task_id
            ]);
        }
        return response()->json([
            "error_code" => 1,
            "message" => "创建任务失败"
        ]);
    }

    public function ShowMyProject(Request $request)//展示我的项目
    {
         $id=$request->session()->get("id");

         try {
             $project_ids = ProjectMember::where("user_id", $id)->select("project_id")->get();
         }catch (QueryException $queryException){
             return response()->json([
                 "error_code" => 1,
                 "message" => "获取成员id失败",
                 "cause" => $queryException
             ]);
         }

         $data=array();
         $i=0;
         foreach ($project_ids as $value)
         {
            $project_id=$value["project_id"];


            try {
                $data[$i] = Project::where("id", $project_id)->select("id", "name", "title", "created_at")->get();
            }catch (QueryException $queryException){
                return response()->json([
                    "error_code" => 1,
                    "message" => "未查询到成员",
                    "cause" => $queryException
                ]);
            }
            try {
                $rate = $this->CalculateProjectRate($project_id);
                $task = $this->GetTaskDDL($project_id);
            }catch (QueryException $exception){
                return response()->json([
                    "error_code" => 1,
                    "message" => "计算错误",
                    "cause" => $exception
                ]);
            }
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
                 "message" => "未获取到成员"
                 ]);
         }
    }

    public function ShowBasicProject()//展示基础项目
    {
        try {
            $BasicProjects = Project::where('id', '>', 0)->select('id', 'name', 'title', 'created_at')->get();
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "未找到项目",
                "cause" => $queryException
            ]);
        }
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



    public function ShowSpecifiedProject(Request $request)//获取特定项目信息
    {
        $project_id=$request->input("project_id");

        try {
            $title = Project::where("id", $project_id)->value("title");//项目标题

            $description = Project::where("id", $project_id)->value("description");//项目描述

            $process = Project::where("id", $project_id)->value("process");//项目进程

            $member = ProjectMember::where("project_id", $project_id)->select("name", "group_name", "permission", "created_at")->get();//获取项目成员、组别、权限、加入时间
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "获取信息失败",
                "cause" => $queryException
            ]);
        }
        for($i=0;$i<count($member,0);$i++)
        {
            $name=$member[$i]["name"];
            try {
                $MemberID = $this->GetMemberID($name);
                $TaskNum = $this->GetMemberNumOFTask($project_id, $name);
            }catch (QueryException $queryException){
                return response()->json([
                    "error_code" => 1,
                    "message" => "获取信息失败",
                    "cause" => $queryException
                ]);
            }
            $member[$i]["member_id"]=$MemberID;
            $member[$i]["task_num"]=$TaskNum;
        }

        try {
            $task = Task::where("project_id", $project_id)->select("name", "title", "description", "process", "deadline","created_at")->get();

            $log = ProjectLog::where("project_id", $project_id)->select("name", "description", "update_at")->get();
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "获取信息失败",
                "cause" => $queryException
            ]);
        }

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

    public function FinishProject(Request $request)//完结项目
    {
        $project_id=$request->input("project_id");
        try{
            $project=Project::find($project_id);
            $project->process="完结";
            $project->save();

        }catch (ModelNotFoundException $exception)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "项目完结失败",
                "cause" => $exception
            ]);
        }

        try{
            $this->PostToMessage($project_id,"项目完结","项目完结");
        }catch (ModelNotFoundException $exception)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "发送消息失败",
                "cause" => $exception
            ]);
        }
        return response()->json([
            "error_code" => 0
        ]);


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

        try {
            $TaskTitle = Task::where("id", $TaskID)->value("title");
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "查找任务失败",
                "cause" => $queryException
            ]);
        }

        $message="删除任务【".$TaskTitle."】"."删除原因为【".$reason."】";

        try {
            $task = Task::find($TaskID);
            $task->delete();
        }catch (ModelNotFoundException $modelNotFoundException){
            return response()->json([
                "error_code" => 1,
                "message" => "删除任务失败",
                "cause" => $modelNotFoundException
            ]);
        } catch (QueryException $queryException) {
            return response()->json([
                "error_code" => 1,
                "message" => "删除任务失败",
                "cause" => $queryException
            ]);
        }

        if($task->trashed()){
            try {
                $ProjectLog = new ProjectLog([
                    "project_id" => $project_id,
                    "name" => $request->session()->get("name"),
                    "description" => "删除任务【" . $TaskTitle . "】" . "删除原因为【" . $reason . "】",
                ]);

                $ProjectLog->save();
            }catch (QueryException $queryException){
                return response()->json([
                    "error_code" => 1,
                    "message" => "更新日志失败",
                    "cause" => $queryException
                ]);
            }
            //$this->PostToMessage($project_id,"删除任务",$message);
            $title="删除任务";


            try {
                $this->PostToMessage($project_id, $title, $message);
            }catch (QueryException $queryException){
                return response()->json([
                    "error_code" => 1,
                    "message" => "发送消息失败",
                    "cause" => $queryException
                ]);
            }
        }

    }

    public function FinishTask(Request $request)
    {
        try {
            $TaskID = $request->input("task_id");
            $process = "Finished";
            $Task = Task::find($TaskID);
            $Task->process = $process;
            $Task->save();
        }catch (ModelNotFoundException $exception)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "删除任务失败",
                "cause" => $exception
            ]);
        }
        return response()->json([
            "error_code" => 0
        ]);
    }

    public function RemoveMember(Request $request)
    {
        $project_id=$request->input("project_id");
        $name=$request->input("name");
        $user_id=$request->input("user_id");
        $reason=$request->input("reason");
        try {
            $id = ProjectMember::where(["project" => $project_id, "user_id" => $user_id])->value("id");
            ProjectMember::destroy([$id]);
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "删除成员失败",
                "cause" => $queryException
            ]);
        }
        $message="删除成员【".$name."】删除原因为【".$reason."】";
        $this->PostToMessage($project_id,"人员退出",$message);
        return response()->json([
            "error_code" => 0
        ]);
    }



    public function GetEachSituation(Request $request)
    {
        $project_id=$request->input("project_id");
        try {
            $member = ProjectMember::where('project_id', $project_id)->select('name')->get();
            //$avatar = $this->LoadAvatar($member);
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "获取信息失败",
                "cause" => $queryException
            ]);
        }

        for($i=0;$i<count($member,0);$i++)
        {
            $name=$member[$i]["name"];
            $rate=$this->GetEveryRate($project_id,$name);
            $avatar=chunk_split(base64_encode($this->LoadAvatar($name)));

            $member[$i]["rate"]=$rate;
            $member[$i]["avatar"]=$avatar;
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

    public function GetMemberDatum(Request $request)
    {
        $project_id=$request->input("project_id");
        try{
            $ProjectMember=ProjectMember::where("project_id",$project_id)->select("user_id","name","group_name","permission")->get();
        }catch (QueryException $exception)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "成员获取失败",
                "cause" => $exception
            ]);
        }
        if($ProjectMember) {
            return response()->json([
                "error_code" => 0,
                "data" => $ProjectMember
            ]);
        }
        return response()->json([
            "error_code" => 1,
            "message" => "查询为空"
        ]);
    }

    public function CreateOtherTask(Request $request)
    {
        $project_id = $request->input("project_id");
        $name=$request->input("name");
        $title=$request->input("title");
        $description=$request->input("description");
        $deadline=$request->input("deadline");

        try {
            $task = new Task([
                "project_id" => $project_id,
                "name" => $name,
                "title" => $title,
                "description" => $description,
                "deadline" => $deadline,
            ]);//新建任务模型

            $task->save();
        }catch (QueryException $exception)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "创建任务失败",
                "cause" => $exception
            ]);
        }
        $task_id=Task::where(["project_id" => $project_id, "name" => $name, "title" => $title])->value("id");

        try {
            $ProjectLog = new ProjectLog([
                "project_id" => $project_id,
                "name" => $name,
                "description" => $name . "发起任务【" . $title . "】",
            ]);
            $message = $name . "发起任务【" . $title . "】";
            $ProjectLog->save();//更新项目日志
        }catch (QueryException $exception)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "更新项目日志失败",
                "cause" => $exception
            ]);
        }

        try {
            $this->PostToMessage($project_id, "创建任务", $message);
        }catch (QueryException $exception)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "发送消息失败",
                "cause" => $exception
            ]);
        }
        if($task_id) {
            return response()->json([
                "error_code" => 0,
                "task_id" => $task_id
            ]);
        }
    }

    public function TransferLeader(Request $request)
    {
        $project_id=$request->input("project_id");
        $user_id=$request->input("user_id");
        $name=$request->input("name");

        try{
            $project=Project::find($project_id);
        }catch (ModelNotFoundException $exception)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "未发现该项目",
                "cause" => $exception
            ]);
        }
        try{
            $project->name=$name;
            $project->author_id=$user_id;
            $project->save();
        }catch (QueryException $exception){
            return response()->json([
                "error_code" => 1,
                "message" => "转让组长失败",
                "cause" => $exception
            ]);
        }

        try{
            $leader_id=$request->session()->get("id");
            $ProjectLeader_id=ProjectMember::where(["project_id" => $project_id,"user_id" => $leader_id])->value("id");
            $ProjectMember_id=ProjectMember::where(["project_id" => $project_id,"user_id" => $user_id])->value("id");
        }catch (QueryException $exception)
        {
            return response()->json([
                "error_code" => 1,
                "message" => "未找到该成员",
                "cause" => $exception
            ]);
        }

        try{
            $ProjectLeader=ProjectMember::find($ProjectLeader_id);
            $ProjectMember=ProjectMember::find($ProjectMember_id);
        }catch (ModelNotFoundException $exception){
            return response()->json([
                "error_code" => 1,
                "message" => "未找到该成员",
                "cause" => $exception
            ]);
        }


        try{
            $ProjectLeader->permission=0;
            $ProjectLeader->save();
            $ProjectMember->permission=1;
            $ProjectMember->save();
        }catch (QueryException $exception){
            return response()->json([
                "error_code" => 1,
                "message" => "转让组长失败",
                "cause" => $exception
            ]);
        }

        try{
            $ProjectLog=new ProjectLog([
             "project_id" => $project_id,
             "name" => $request->session()->get("name"),
             "description" => $request->session()->get("name")."转让组长给".$name
            ]);
            $ProjectLog->save();
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "更新日志失败",
                "cause" => $queryException,
            ]);
        }

        $title="组长转让";
        $message=$request->session()->get("name")."转让组长给".$name;
        //$this->PostToMessage($project_id,$title,$message);
        try {
            $this->PostToMessage($project_id, $title, $message);
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

    public function DeleteMember(Request $request)
    {
        $name=$request->input("name");
        $user_id=$request->input("user_id");
        $project_id=$request->input("project_id");
        try{
            $ProjectMember_id=ProjectMember::where(["project_id" => $project_id,"user_id" => $user_id])->value("id");
            ProjectMember::destroy([$ProjectMember_id]);
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "删除成员失败",
                "cause" => $queryException
            ]);
        }

        try{
            $ProjectLog=new ProjectLog([
               "project_id" => $project_id,
                "name" => $request->session()->get("name"),
                "description" => $request->session()->get("name")."删除了成员".$name
            ]);
            $ProjectLog->save();
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "更新消息失败",
                "cause" => $queryException
            ]);
        }
        $title="删除成员";
        $message=$request->session()->get("name")."删除了成员".$name;


        try {
            $this->PostToMessage($project_id, $title, $message);
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


    public function ChangeProjectDescription(Request $request)
    {
        $project_id=$request->input("project_id");
        $description=$request->input("description");

        try{
            $Project=Project::find($project_id);
        }catch (ModelNotFoundException $modelNotFoundException){
            return response()->json([
                "error_code" => 1,
                "message" => "未找到该项目",
                "cause" => $modelNotFoundException
            ]);
        }

        try{
            $Project->description=$description;
            $Project->save();
        }catch (QueryException $queryException){
            return response()->json([
                "error_code" => 1,
                "message" => "修改项目描述失败",
                "cause" => $queryException
            ]);
        }

        return response()->json([
            "error_code" => 0
        ]);
    }


    /*仅在本控制器内使用的方法*/
    /*保护方法*/




    protected function LoadAvatar($name)//加载成员头像
    {
        $avatar_uri=Student::where('name',$name)->value("avatar");
        $file_path="image/".$avatar_uri;
        $avatar =Storage::get($file_path);
        return $avatar;
    }

    protected function GetMemberID($name)
    {
        $ID=Student::where("name",$name)->value("id");
        return $ID;
    }

    protected function GetMemberNumOFTask($project_id,$name)
    {
        $Num=count(Task::where([
            "project_id" => $project_id,
            "name" => $name
        ])->select('id')->get(),0);
        return $Num;
    }

    protected function GetTaskDDL($project_id)//获取项目中任务ddl
    {
        $task = Task::where("project_id", $project_id)->select('title', 'deadline')->get();
        return $task;
    }

    protected function CalculateProjectRate($project_id)
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
        return round($count/$num);
    }

    protected function CalculateTaskRate($task_id)
    {
        $data=Task::where('id',$task_id)->select('deadline','created_at')->get();
        $created_at=strtotime($data[0]["created_at"]);
        $deadline=strtotime($data[0]["deadline"]);
        $TotalSpan=$deadline-$created_at;
        $PersentSpan=time()-$created_at;
        $count=($PersentSpan/$TotalSpan);
        $rate=($count<1)?$count:1;

        return round($rate);
    }

     protected function GetEveryRate($project_id,$name)
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
        $rate=round($count/$num);
        return $rate;
    }

    protected function PostToMessage($project_id,$title,$message)
    {
        $IDGroup=ProjectMember::where("project_id",$project_id)->select("user_id")->get();
        foreach ($IDGroup as $EachArray) {
            $user_id = $EachArray["user_id"];
            try {
                $Message = new Message([
                    "user_id" => $user_id,
                    "type" => "【项目信息】",
                    "title" => $title,
                    "message" => $message,
                    "read" => 0
                ]);
                $Message->save();
            }catch (QueryException $queryException){
                return response()->json([
                    "error_code" => 1,
                    "message" => "更新消息失败",
                    "cause" => $queryException
                ]);
            }


        }
    }
}
