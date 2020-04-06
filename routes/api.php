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
/*周报*/
Route::post('/message',"PageController@editor");//存储周报

Route::get('/GetMessage','PageController@GetMessage');//获取周报

/*工作日志*/
Route::post('/UploadLog','LogController@upload_log');//发布日志

Route::get('/GetLog','LogController@get_log');//返回日志内容

Route::get('/DeleteLog','LogController@delete_log');//删除日志


/*登录*/
Route::get('/login',['uses' => 'StudentController@login']);

Route::post('/save',["uses" => "StudentController@save"]);//储存用户信息

Route::get('/admin',["uses" => "AdminController@admin"]);




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
