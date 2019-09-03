<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>学生添加</title>
</head>
<body>
    <center>
        <form action="{{url('ceshi/doadd')}}" method="post" enctype="multipart/form-data">
        @csrf
            <input type="text" name="name" placeholder="学生姓名"><br>
            <input type="text" name="age" placeholder="学生年龄 "><br>
            <input type="submit" value="学生添加">
        </form>
    </center>
</body>
</html>