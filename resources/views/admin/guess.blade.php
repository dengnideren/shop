<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>添加</title>
</head>
<body>
    <form action="{{url('guessdo')}}" method="post">
    @csrf
        <input type="text" name="name">VS<input type="text" name="title"><br>
        结束竞猜时间 <input type="text" name="endtime"><br>
        <input type="submit" value="添加">
    </form>
</body>
</html>