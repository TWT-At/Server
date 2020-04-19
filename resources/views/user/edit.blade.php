<!DOCTYPE>
<html>
    <meta charset="UTF-8">
<body>
    <form action="{{url("api/user/image")}}" method="post" enctype="multipart/form-data">
        ID:<input type="text" name="id"><br/>
        上传图片:<input type="file" name="avatar"><br/>
        <input type="submit" name="上传">
    </form>
</body>
</html>
