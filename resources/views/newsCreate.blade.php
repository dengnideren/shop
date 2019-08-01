<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>新闻添加</title>
</head>
<body>
    <center>
        <form action="{{url('news/save')}}" method="post" enctype="multipart/form-data">
        @csrf
            <input type="text" name="title" placeholder="新闻标题"><br>
            <input type="file" name="pic" placeholder="新闻图片 "><br>
            <input type="text" name="name" placeholder="新闻作者"><br>
            <input type="text" name="content" placeholder="新闻内容"><br>
            <input type="submit" value="新闻添加">
        </form>
    </center>
</body>
</html>