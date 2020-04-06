@extends("common.PageLayout")
@section("col-content")
    <div class="col">
       <form method="post" action="{{url("")}}">
           <div class="form-group">
               <label for="WeekMessage">周报</label>
               <div class="input-group">
                   <input type="text" name="WeekMessage" class="form-control" style="height: 300px">
               </div>
               <button type="submit" class="btn btn-primary mb-2">提交</button>
           </div>
       </form>
    </div>
    @stop
