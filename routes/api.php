<?php

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

Route::options('/{all}', function () {
    return response('');
})->where(['all' => '([a-zA-Z0-9-]|_|/)+']);//屏蔽options请求

Route::get('/filetest',function (){
    return view("user.edit");
});//文件上传测试

Route::group([
    'middleware' => ['session'],
    'prefix' => 'user',
],function (){
    Route::post('/UpdateImage' ,["uses" => "AlterController@image"]);//上传图片

    Route::get('/GetAvatar',["uses" => "AlterController@GetAvatar"]);//获取用户头像

    Route::post('/UpdatePassword',["uses" => "AlterController@password"]);//修改密码

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
});

//项目管理（组员端)
Route::group([
    'middleware' => ['session'],
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


});


/*周报*/
Route::group([
    'middleware' => ["session"],
],function (){
    Route::post('/message',["uses" => "PageController@editor"]);//存储周报

    Route::get('/GetMessage',["uses" => 'PageController@GetMessage']);//获取周报
});




/*工作日志*/
Route::group(['middleware' => ["session"],
    ],function () {
    Route::post('/UploadLog','LogController@upload_log');//发布日志

    Route::get('/GetLog','LogController@get_log');//返回日志内容

    Route::get('/DeleteLog','LogController@delete_log');//删除日志

});


/*登录*/
//Route::get('/login',['uses' => 'StudentController@login']);

Route::post('/save',["uses" => "StudentController@save"]);//登陆用户验证

Route::group(["middleware" => "session"],function (){
    Route::get('/getinfo',["uses" => "StudentController@getinfo"]);
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

    Route::group(["prefix" => "permission"],function (){
       Route::post("/UpdatePermission",["uses" => "PermissionController@UpdatePermission"]);//更改权限
    });
});

/*
 * View OF Route
 * */
/*Route::get('at/main',function (){
    return view('main');
});

Route::get('at/WeekMessage',function (){

    return view("student.WeekMessage");
});

Route::get('at/description',function (){
    return view("student.description");
});

Route::get("at/editor",function (){
    return view("student.editor");
});*/
