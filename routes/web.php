<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/at/login',['uses' => 'StudentController@login']);
Route::post('/at/save',["uses" => "StudentController@save"]);

Route::get('/at/admin',["uses" => "AdminController@admin"]);

//Route::get('at/main',['uses' => "PageController@main"]);

/*
 * View OF Route*/
Route::get('at/main',function (){
    return view('main');
});
Route::get('at/WeekMessage',function (Request $request){
     $_SESSION["name"]=$request->session()->get("name");
     return view("student.WeekMessage")->with("name",$request->session()->get("name"));
});
