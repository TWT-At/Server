@extends("common.layout")
@section("title")
    at
    @stop
@section("content")
    <h1 style="color: blue">at</h1>
    <div class="container">
        <div class="row">
            <div class="col-2">
                    <div class="list-group">
                        <a href="{{url("at/main")}}" class="list-group-item list-group-item-action list-group-item-primary">主页</a>
                        <a href="{{url("/at/describtion")}}" class="list-group-item list-group-item-action list-group-item-secondary">讨论</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-success">签到</a>
                        <a href="{{url("/at/WeekMessage")}}" class="list-group-item list-group-item-action list-group-item-danger">周报</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-warning">更多</a>
                        <!--
                        <a href="#" class="list-group-item list-group-item-action list-group-item-info">This is a info list group item</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-light">This is a light list group item</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-dark">This is a dark list group item</a>
                        -->
                    </div>
                </div>
            <div class="col">
                <h2 style="font-size: 25px;"><strong>你好, {{$name}}</strong></h2>
            </div>
        </div>

    </div>

    @stop
