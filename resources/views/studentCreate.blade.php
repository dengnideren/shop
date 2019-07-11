<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>学生添加</title>
</head>
<body>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <center>
        <form method="post" action="{{url('student/save')}}">
        @csrf
            姓名：<input type="text" name="name"></br>
            年龄：<input type="text" name="age"></br>
            性别：<input type="radio" name="sex" value="1" checked>男
                  <input type="radio" name="sex" value="2">女</br>
            <input type="submit" value="添加">
        </form>
    </center>
</body>
</html>