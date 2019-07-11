<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>学生列表</title>
</head>
<body>
    <center>
    <h1>学生列表</h1>
    <form method="get" action="{{url('student/index')}}">
        <input type="text" name="search" value="{{$search}}">
        <input type="submit" value="搜索">
    </form>
        <table border="1">
            <tr>
                <td>id</td>
                <td>年龄</td>
                <td>性别</td>
                <td>姓名</td>
                <td>添加时间</td>
                <td>操作</td>
            @foreach($student as $v)
            </tr>
            <tr>
                <td>{{ $v->id }}</td>
                <td>{{ $v->name }}</td>
                <td>{{ $v->age }}</td>
                <td>@if($v->sex==1)男 @elseif($v->sex==2)女 @endif</td>
                <td>{{ date('Y-m-d H:i:s',$v->add_time) }}</td>
                <td>
                    <a href="{{url('student/delete')}}?id={{$v->id}}">删除</a>
                    <a href="{{url('student/edit')}}?id={{$v->id}}">修改</a>
                </td>
            </tr>
            @endforeach
        </table>
        {{ $student->appends(['search'=>$search])->links() }}
    </center>
</body>
</html>