@extends("common.layout")
@section("content")
    <div class="container">
        <div class="row">
            <div class="col-2">
                <div class="list-group">
                    <a href="{{url("/api/at/main")}}" class="list-group-item list-group-item-action list-group-item-primary">主页</a>
                    <a href="{{url("/api/at/description")}}" class="list-group-item list-group-item-action list-group-item-secondary">讨论</a>
                    <a href="#" class="list-group-item list-group-item-action list-group-item-success">签到</a>
                    <a href="{{url("/api/atWeekMessage")}}" class="list-group-item list-group-item-action list-group-item-danger">周报</a>
                    <a href="#" class="list-group-item list-group-item-action list-group-item-warning">更多</a>

                </div>
            </div>
            @yield("col-content")
        </div>
        @yield("row-col-content")
    </div>
    @stop
