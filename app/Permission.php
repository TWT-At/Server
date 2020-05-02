<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table="permission";
    protected $primaryKey="id";
    protected $fillable=[
        "role","default","stop","查看考勤记录","记录组员考勤情况","接收消息",
        "群发消息","查看组员信息","接受消息","更改基础信息","查看在线人员情况","参与讨论",
        "文件上传","文件下载","文件共享","访问他人云盘","查看他人项目介绍","查看他人项目任务",
        "编辑管理全部项目","创建项目","查看自己的项目","预定/取消会议（卫津路）","预定/取消会议（北洋园）",
        "查看预订","参加会议","编写周报","查看他人周报","评论周报","给周报打分"
    ];
    public $timestamps=false;
}
