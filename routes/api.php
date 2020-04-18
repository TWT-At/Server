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


Route::group([
    'middleware' => 'session',
    'prefix' => 'user'
],function (){
   Route::post('/image' ,["uses" => "AlterController@image"]);//上传图片

   Route::post('/password',["uses" => "AlterController@password"]);//修改密码

    Route::post('email_password',["uses" => "AlterController@email_password"]);//修改邮箱密码
});
/*周报*/
Route::group([
    'middleware' => "session",
],function (){
    Route::post('/message',["uses" => "PageController@editor"]);//存储周报

    Route::get('/GetMessage',["uses" => 'PageController@GetMessage']);//获取周报
});




/*工作日志*/
Route::group(['middleware' => "session",
    ],function () {
    Route::post('/UploadLog','LogController@upload_log');//发布日志

    Route::get('/GetLog','LogController@get_log');//返回日志内容

    Route::get('/DeleteLog','LogController@delete_log');//删除日志

});


/*登录*/
Route::get('/login',['uses' => 'StudentController@login']);

Route::post('/save',["uses" => "StudentController@save"]);//登陆用户验证




/*后台端*/
Route::post("/admin",["uses" => "AdminController@login"]);//管理员登录
Route::group([
    "middleware" => "AdminSession",
    "prefix" => "admin",
            ],function(){
    Route::get("/basic",["uses" => "AdminController@basic"]);//获取所有用户基本资料

    Route::post('/complex',["uses" => "AdminController@complex"]);//通过post用户id获取用户相关所有信息

    Route::post('/add',["uses" => "AdminController@add"]);//添加用户

    Route::post('/remove',["uses" => "AdminController@remove"]);//删除用户

    Route::post("/search",["uses" => "AdminController@search"]);//查询用户

    Route::post("/update",["uses" => "AdminController@update"]);//更新用户资料

    Route::post("/announce",["uses" => "AdminController@announce"]);//管理员发布公告
});

/*
 * View OF Route
 * */
Route::get('at/main',function (){
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
});
