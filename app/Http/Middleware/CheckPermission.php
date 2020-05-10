<?php

namespace App\Http\Middleware;

use App\Permission;
use App\Project;
use Closure;


class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $role=$request->session()->get("group_role");//获取用户角色
        $action=$request->header("action");
        $operation=$this->GetActive($action);//获取用户行为
        if(in_array($operation,["编辑管理全部项目"]))
        {
            $project_id=$request->input("project_id");
            $name=$request->session()->get("name");
            if($this->CheckProject($project_id,$name))
                return $next($request);
            return response()->json([
                "error_code" => 1,
                "message" => "角色权限不允许".$operation
            ]);
        }

        $authorize=Permission::where("role",$role)->value($operation);
        if($authorize)
        {
            return $next($request);
        }
        return response()->json([
            "error_code" => 1,
            "message" => "角色权限不允许".$operation,
        ]);
    }

    protected function CheckProject($project_id,$name)
    {
        $author_name=Project::where('id',$project_id)->value('name');
        return ($name==$author_name);
    }

    protected function GetActive($action)
    {
        $PermissionArray=[
            "get attendance records"=>"查看考勤记录","record attendance of team members"=>"记录组员考勤情况",
            "receive message"=>"接收消息", "group message"=>"群发消息","get users'message"=>"查看组员信息","receive message2"=>"接受消息",
            "modify basic information"=>"更改基础信息","get online user"=>"查看在线人员情况","attend discussion"=>"参与讨论",
            "upload file"=>"文件上传","download file"=>"文件下载","share file"=>"文件共享","visit others'cloud disk"=>"访问他人云盘",
            "get others' project introduction"=>"查看他人项目介绍","get others' project task"=>"查看他人项目任务",
            "manage all projects"=>"编辑管理全部项目","create project"=>"创建项目","get own project"=>"查看自己的项目","meeting(WJL)"=>"预定/取消会议（卫津路）",
            "meeting(BYY)"=>"预定/取消会议（北洋园）", "get destine"=>"查看预订","attend meeting"=>"参加会议","write week publication"=>"编写周报",
            "get others' week publication"=>"查看他人周报","comment week publication"=>"评论周报","rate week publication"=>"给周报打分"
        ];
        return $PermissionArray[$action];
    }
}
