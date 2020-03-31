@extends("common.layout")
@section("title")
    周报
@stop
@section("content")
    <h1 style="color: blue">at</h1>
    <div class="container">
        <div class="row">
            <div class="col-2">
                <div class="list-group">
                    <a href="{{url("at/main")}}" class="list-group-item list-group-item-action list-group-item-primary">主页</a>
                    <a href="{{url("/at/description")}}" class="list-group-item list-group-item-action list-group-item-secondary">讨论</a>
                    <a href="#" class="list-group-item list-group-item-action list-group-item-success">签到</a>
                    <a href="{{url("/at/WeekMessage")}}" class="list-group-item list-group-item-action list-group-item-danger">周报</a>
                    <a href="#" class="list-group-item list-group-item-action list-group-item-warning">更多</a>

                </div>
            </div>
            <div class="col">
                <h2>程序组</h2>
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">姓名</th>
                            <th scope="col">周报状态</th>
                        </tr>
                    </thead>
                    @foreach($students as $student)
                       @if($student["group_name"]=="程序组")
                           <tr>
                               <th scope="col">{{$student["name"]}}</th>
                               <th scope="col">未交</th>
                           </tr>
                        @endif

                    @endforeach
                </table>
                <h2>前端组</h2>
                <table class="table">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">姓名</th>
                        <th scope="col">周报状态</th>
                    </tr>
                    </thead>
                    @foreach($students as $student)
                        @if($student["group_name"]=="前端组")
                            <tr>
                                <th scope="col">{{$student["name"]}}</th>
                                <th scope="col">未交</th>
                            </tr>
                @endif

                @endforeach

            </div>
        </div>

    </div>
@stop
