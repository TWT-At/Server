<?php

use App\Project;
use App\ProjectLog;
use App\ProjectMember;
use App\Student;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/delete",function (){
    Project::where('id','>',0)->delete();
    ProjectMember::where('id','>',0)->delete();
    ProjectLog::where('id','>',0)->delete();
    Task::where('id','>=',1)->delete();
});


Route::get("/TestTimeStamp",function (){
   $time=time();
   echo "当前UNIX时间戳：".$time;
   echo "</br>";
   echo "当前时间：".date("Y-m-d H:i:s",$time);
   echo "<br/>";

   echo "加上八小时之后的时间".date("Y-m-d H:i:s",$time+28800);
});

Route::get('/GetWeek',function (){
    return date('W',time());
});

Route::get("/TestID",function (){
   $id=Student::insertGetId([
       "student_id" => "666666",
       "password" => password_hash("123456",PASSWORD_DEFAULT),
       "name" => "段登峰",
       "group_name" => "产品组",
       "group_role" => "组员",
       "campus" => "卫津路",
       "email" => "itsstevenduan@tju.edu.cn"
   ]);
   return $id;
});
Route::options('/{all}', function () {
    return response('');
})->where(['all' => '([a-zA-Z0-9-]|_|/)+']);//屏蔽options请求


Route::post('/save',["uses" => "StudentController@save"])->middleware(['cache']);//登陆用户验证

Route::get('/logout',function (Request $request){//登出
    $request->session()->flush();
})->middleware(["session"]);

Route::group(["middleware" => "session"],function (){
    Route::get('/getinfo',["uses" => "StudentController@getinfo"]);
});


Route::group([
    'middleware' => ['session','permission','cache'],
    'prefix' => 'user',
],function (){
    Route::post('/UpdateImage' ,["uses" => "AlterController@UpdateImage"]);//上传图片

    Route::get('/GetAvatar',["uses" => "AlterController@GetAvatar"]);//获取用户头像

    Route::post('/UpdatePassword',["uses" => "AlterController@password"]);//修改密码

    Route::post('/UploadPersonalPhoto',["uses" => "MeetingController@UploadPersonalPhoto"]);//上传用户展示照片，用于会议签到头像识别

    Route::post('/UpdateEmailPassword',["uses" => "AlterController@email_password"]);//修改邮箱密码

    Route::get("/GetMessage",["uses" => "MessageController@GetMessage"]);//获取消息

    Route::post('/UpdateRead',["uses" => "MessageController@UpdateRead"]);//更新消息已读情况

    Route::get('/GetPM',["uses" => "UserController@GetPM"]);//获取产品组成员信息

    Route::get('/GetUI',["uses" => "UserController@GetUI"]);//获取设计组成员信息

    Route::get('/GetWeb',["uses" => "UserController@GetWeb"]);//获取前端组成员信息

    Route::get("/GetBackEnd",["uses" => "UserController@GetBackEnd"]);//获取后端组成员信息

    Route::get('/GetAndroid',["uses" => "UserController@GetAndroid"]);//获取安卓组成员信息

    Route::get('/GetIOS',["uses" => "UserController@GetIOS"]);//获取IOS组成员信息

    Route::post('/GetComplex',["uses" => "UserController@GetComplex"]);//获取详细信息

    Route::get('/OnlineStatus',['uses' => "UserController@JudgeOnline"]);//获取成员在线状况
});

//项目管理（组员端)
Route::group([
    'middleware' => ['session','permission','cache'],
    'prefix' => 'project'
],function (){

    Route::post('/CreateProject',['uses' => 'ProjectController@CreateProject']);//创建新项目

    Route::post('/AddMember',['uses' => 'ProjectController@AddMember']);//为项目添加成员

    Route::post('/CreateTask',['uses' => 'ProjectController@CreateTask']);//创建任务

    Route::post('/RemoveMember',["uses" => "ProjectController@RemoveMember"]);//移除成员

    Route::get('/ShowMyProject',['uses' => 'ProjectController@ShowMyProject']);//获取我的项目基本信息

    Route::get('/ShowBasicProject',["uses" => "ProjectController@ShowBasicProject"]);//获取项目基础信息

    Route::post('/ShowSpecifiedProject',["uses" => "ProjectController@ShowSpecifiedProject"]);//获取指定项目的信息

    Route::post('/DelayTask',["uses" => "ProjectController@DelayTask"]);//延迟任务

    Route::post("/DeleteTask",["uses" => "ProjectController@DeleteTask"]);//删除任务

    Route::post('/FinishTask',["uses" => "ProjectController@FinishTask"]);//完结任务

    Route::post('/GetEachSituation',['uses' => 'ProjectController@GetEachSituation']);//获取项目个人信息

    Route::get('/GetUserDatum',["uses" => "ProjectController@GetUserDatum"]);//获取站内成员信息

    Route::post('/FinishProject',["uses" => "ProjectController@FinishProject"]);//完结项目

    Route::post('/GetMemberDatum',["uses" => "ProjectController@GetMemberDatum"]);//获取组员信息

    Route::post('/CreateOtherTask',["uses" => "ProjectController@CreateOtherTask"]);//为其他人创建任务

    Route::post('/TransferLeader',["uses" => "ProjectController@TransferLeader"]);//转让组长

    Route::post('/DeleteMember',["uses" => "ProjectController@DeleteMember"]);//删除成员

    Route::post('/GetDelayTaskLog',["uses" => "PageController@GetDelayTaskLog"]);//获取项目延期日志
});

//云盘
Route::group([
    'middleware' => ['session',"permission",'cache'],
    'prefix' => 'CloudDriver'
],function (){

    Route::post("/UploadFile",["uses" => "CloudDriverController@UploadFile"]);//上传文件

});

/*周报*/
Route::group([
    'middleware' => ["session","permission","cache"],
],function (){
    Route::post('/message',["uses" => "PageController@editor"]);//存储周报

    Route::post('/GetMessage',["uses" => 'PageController@GetMessage']);//获取周报

    Route::post('/GetMessageDetail',['uses' => "PageController@GetMessageDetail"]);//获取周报细节

    Route::post('/ScoreMessage',['uses' => 'PageController@ScoreMessage']);//给周报打分

    Route::post('/CommentMessage',['uses' => 'PageController@CommentMessage']);//评论周报

    Route::post('/LoveMessageComment',["uses" => "PageController@LoveMessageComment"]);//周报评论点赞

    Route::post('/GetComment',['uses' => "PageController@GetComment"]);//获取周报评论
});




/*工作日志*/
Route::group(['middleware' => ["session",'permission',"cache"],
    ],function () {
    Route::post('/UploadLog','LogController@upload_log');//发布日志

    Route::get('/GetLog','LogController@get_log');//返回日志内容

    Route::get('/DeleteLog','LogController@delete_log');//删除日志

});







/*后台端*/
Route::post("/admin",["uses" => "AdminController@login"]);//管理员登录
Route::group([
    "middleware" => "AdminSession",
    "prefix" => "admin",
            ],function(){
    Route::get("/basic",["uses" => "AdminController@basic"]);//获取所有用户基本资料

    Route::post('/complex',["uses" => "AdminController@complex"]);//通过post用户id获取用户相关所有信息

    Route::post('/add',["uses" => "AdminController@add"]);//添加用户

    Route::post('/AddExcel',["uses" => "ExcelController@AddExcel"]);//通过excel导入数据

    Route::post('/remove',["uses" => "AdminController@remove"]);//删除用户

    Route::post("/search",["uses" => "AdminController@search"]);//查询用户

    Route::post("/update",["uses" => "AdminController@update"]);//更新用户资料

    Route::post("/announce",["uses" => "AdminController@announce"]);//管理员发布公告

    Route::get("/GetAnnounce",["uses" => "AdminController@GetAnnounce"]);//管理员获取公告

    Route::group(["prefix" => "permission"],function (){
       Route::post("/UpdatePermission",["uses" => "PermissionController@UpdatePermission"]);//更改权限

       Route::post("/AddRole",["uses" => "PermissionController@AddRole"]);//添加角色

       Route::post('/DeleteRole',["uses" => "PermissionController@DeleteRole"]);//删除角色

       Route::post('/StopRole',["uses" => "PermissionController@StopRole"]);//停用角色

       Route::post('/SetDefault',["uses" => "PermissionController@SetDefult"]);//设置默认角色

       Route::post('/RecoverRole',['uses' => "PermissionController@RecoverRole"]);//恢复角色
    });
});

//会议预定
Route::group([
    'middleware' => ['session','permission',"cache"],
    'prefix' => 'meeting'
],function (){
    Route::post('/DestineMeeting',['uses' => 'MeetingController@DestineMeeting']);//预定会议

    Route::post('/ShowMeeting',['uses' => "MeetingController@ShowMeeting"]);//获取会议信息

    Route::post('/DeleteMeeting',['uses' => "MeetingController@DeleteMeeting"]);//取消会议

    Route::post('/ChangeMeeting',["uses" => "MeetingController@ChangeMeeting"]);//修改会议

    Route::post('/FaceRecognition',['uses' => 'MeetingController@FaceRecognition']);//会议预定人脸识别

    Route::post('/SignIn',["uses" => "MeetingController@SignIn"])->middleware(["MeetingSignIn"]);//签到

    Route::post('/AskForLeave',["uses" => "MeetingController@AskForLeave"])->middleware(["MeetingAskForLeave"]);
});

