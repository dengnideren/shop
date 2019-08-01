<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录</title>
</head>
<body>
<center>
    <form method="post" action="{{url('admin/dologin')}}">
    @csrf
        <h1>欢迎使用haAdmin</h1><br>
        <input type="text" name="name" placeholder="用户名"><br>
        <input type="password" name="pwd" placeholder="密码"><br>
        <input type="submit" value="登录">
    </form>
    </center>
</body>
</html>