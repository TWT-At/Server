@extends("common.layout")
@section("title")
    at
    @stop
@section("content")
    @inject("request","Illuminate\Http\Request")
    <h1 style="color: blue">at</h1>
    <div class="container">
        <div class="row">
            <div class="col-2">
                    <div class="list-group">
                        <a href="{{url("/api/at/main")}}" class="list-group-item list-group-item-action list-group-item-primary">主页</a>
                        <a href="{{url("/api/at/description")}}" class="list-group-item list-group-item-action list-group-item-secondary">讨论</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-success">签到</a>
                        <a href="{{url("/api/at/WeekMessage")}}" class="list-group-item list-group-item-action list-group-item-danger">周报</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-warning">更多</a>

                    </div>
                </div>
            <div class="col-5">

                <h2 style="font-size: 25px;"><strong>你好, {{$request->session()->get("name")}}</strong></h2>
                <div class="card">
                    <div class="card-body">
                        <div class="card-text">
                            今天是你加入天外天工作室的第<strong>{{$request->session()->get("date")}}</strong>天<br/><br/>
                            <strong>账号:</strong>{{$request->session()->get("student")}}<br/>
                            <strong>组别：</strong>{{$request->session()->get("group")}}<br/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <h2 style="font-size: 25px"><strong>周报状态</strong></h2>
                <div class="card">
                    <div class="card-body">
                        <div class="card-text">
                            第xxxx期周报:<strong style="color: gold">未提交</strong><br/>
                            <p style="font-size: 12px">起止日期：xxxx--xxxx</p>
                            <p style="font-size: 12px">周报截止日期为每周周一，在时间范围内进行编辑，超过编辑时间的周报不能编辑。 <a href="{{url("/api/at/editor")}}">去编辑周报>></a> </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    @stop
