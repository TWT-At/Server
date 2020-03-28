@extends("common.layout")
@section("title")
    at
    @stop
@section("content")
    <h1 style="color: blue">at</h1>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <ul>
                    <li><a href="#">主页</a> </li>
                    <li><a href="{{url("/at/describtion")}}">讨论</a> </li>
                    <li><a href="">签到</a> </li>
                    <li><a href="#">周报</a></li>
                    <li><a href="">更多</a> </li>
                </ul>
            </div>
            <div class="col">
                <h2 style="font-size: 25px"><strong>你好, {{$name}}</strong></h2>
            </div>
        </div>

    </div>

    @stop
