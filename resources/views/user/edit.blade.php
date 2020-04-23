<!DOCTYPE>
<html>
    <meta charset="UTF-8">
<body>
    <form action="{{url("api/admin/AddExcel")}}" method="post" enctype="multipart/form-data">
        ID:<input type="text" name="id"><br/>
        上传图片:<input type="file" name="excel"><br/>
        <input type="submit" name="上传">
    </form>
</body>
</html>
