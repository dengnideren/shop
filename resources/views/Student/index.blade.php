<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>学生列表</title>
</head>
<body>
    <center>
    <h1>学生列表</h1>
    <form method="get" action="{{url('ceshi/index')}}">
        <input type="text" name="search" value="{{$search}}">
        <input type="submit" value="搜索">
    </form>
        <table border="1">
            <tr>
                <td>ID</td>
                <td>学生姓名</td>
                <td>学生年龄</td>
                <td>添加时间</td>
                <td>操作</td>
            </tr>
            @foreach($data as $v)
            <tr>
                <td>{{ $v->id }}</td>
                <td>{{ $v->name }}</td>
                <td>{{ $v->age }}</td>
                <td>{{ date('Y-m-d H:i:s',$v->add_time) }}</td>
                <td>
                    <a href="{{url('ceshi/delete')}}?id={{$v->id}}">删除</a>
                    <a href="{{url('ceshi/edit')}}?id={{$v->id}}">修改</a>
                </td>
            </tr>
            @endforeach
        </table>
        {{ $data->appends(['search'=>$search])->links() }}
    </center>
</body>
</html>