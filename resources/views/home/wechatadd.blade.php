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
            <td>头像</td>
            <td>性别</td>
            <td>昵称</td>
            <td>城市</td>
            <td>是否关注</td>
            <td>openid</td>
            <td>添加时间</td>
        </tr>
        @foreach($wechat as $v)
        <tr>
            <td><img src="{{$v->headimgurl}}" width="50px" height="50px" alt="" ></td>
            <td>{{$v->sex}}</td>
            <td>{{$v->nickname}}</td>
            <td>{{$v->country}}{{$v->province}}{{$v->city}}</td>
            <td>{{$v->subscribe}}</td>
            <td>{{$v->openid}}</td>
            <td>{{ date('Y-m-d H:i:s',$v->add_time) }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>