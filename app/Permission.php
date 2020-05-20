<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Permission
 *
 * @property int $id
 * @property string $role
 * @property int $default
 * @property int $stop
 * @property int $查看考勤记录
 * @property int $记录组员考勤情况
 * @property int $接收消息
 * @property int $群发消息
 * @property int $查看组员信息
 * @property int $接受消息
 * @property int $更改基础信息
 * @property int $查看在线人员情况
 * @property int $参与讨论
 * @property int $文件上传
 * @property int $文件下载
 * @property int $文件共享
 * @property int $访问他人云盘
 * @property int $查看他人项目介绍
 * @property int $查看他人项目任务
 * @property int $编辑管理全部项目
 * @property int $创建项目
 * @property int $查看自己的项目
 * @property int $预定/取消会议（卫津路）
 * @property int $预定/取消会议（北洋园）
 * @property int $查看预订
 * @property int $参加会议
 * @property int|null $编写周报
 * @property int $查看他人周报
 * @property int $评论周报
 * @property int $给周报打分
 * @property string $created_at
 * @property string|null $update_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereStop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereUpdateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where创建项目($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where参与讨论($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where参加会议($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where接受消息($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where接收消息($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where文件上传($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where文件下载($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where文件共享($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where更改基础信息($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where查看他人周报($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where查看他人项目介绍($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where查看他人项目任务($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where查看在线人员情况($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where查看组员信息($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where查看考勤记录($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where查看自己的项目($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where查看预订($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where给周报打分($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where编写周报($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where编辑管理全部项目($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where群发消息($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where记录组员考勤情况($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where访问他人云盘($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where评论周报($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where预定/取消会议（北洋园）($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where预定/取消会议（卫津路）($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where预定/取消会议（北洋园）($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission where预定/取消会议（卫津路）($value)
 */
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
