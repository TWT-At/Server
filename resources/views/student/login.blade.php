@extends("common.layout")
@section("title")
    Login
@stop

@section("content")
<h1><strong>天外天账户登录</strong></h1>

    <form method="post" action="{{url("/at/save")}}">
        {{csrf_field()}}
        学工号:<input type="text" name="student_id" id="student_id" placeholder="请输入学工号"><br/>
        密码：<input type="text" name="password" id="password" placeholder="请输入密码"><br/>
        <input type="submit" value="登录">
    </form>
@stop

