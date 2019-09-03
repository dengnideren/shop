<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>学生修改</title>
</head>
<body>
    <center>
        <form method="post" action="{{url('ceshi/update')}}">
        @csrf
            <input type="hidden" name="id" value={{$data->id}}>
            姓名：<input type="text" name="name" value="{{$data->name}}"></br>
            年龄：<input type="text" name="age" value="{{$data->age}}"></br>
            <input type="submit" value="修改">
        </form>
    </center>
</body>
</html>