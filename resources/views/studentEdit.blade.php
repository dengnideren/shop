<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>学生修改</title>
</head>
<body>
    <center>
        <form method="post" action="{{url('student/update')}}">
        @csrf
            <input type="hidden" name="id" value={{$student->id}}>
            姓名：<input type="text" name="name" value="{{$student->name}}"></br>
            年龄：<input type="text" name="age" value="{{$student->age}}"></br>
            性别：<input type="radio" name="sex" value="男" @if($student->sex==1) checked @endif >男
                  <input type="radio" name="sex" value="女" @if($student->sex==2) checked @endif >女</br>
            <input type="submit" value="修改">
        </form>
    </center>
</body>
</html>