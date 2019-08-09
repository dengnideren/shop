<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>粉丝列表页</title>
</head>
<body>
    <table border="1">
        <tr>
            <td>ID</td>
            <td>粉丝账号</td>
            <td>是否关注</td>
            <td>添加时间</td>
            <td>操作</td>
        </tr>
        @foreach($wechat as $v)
        <tr>
            <td>{{$v->id}}</td>
            <td>{{$v->openid}}</td>
            <td>{{$v->subscribe}}</td>
            <td>{{ date('Y-m-d H:i:s',$v->add_time) }}</td>
            <td>
                <a href="{{url('wechat/wechatadd')}}?id={{$v->id}}">前往详情页</a>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>