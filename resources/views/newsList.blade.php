<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>新闻详情页</title>
</head>
<body>
    <center>
        <table>
        @foreach($news as $v)
            <tr>
                <td><h1>新闻详情页</h1></td><br>
            </tr>
            <tr>
                <td><h3>新闻</h3></td><br>
            </tr>
            <tr>
                <td>作者：{{ $v->name }}</td><br>
            </tr>
            <tr>
                <td>访问量:{{ $num }}</td><br>
            </tr>
            <tr>
                <td>新闻详细内容:{{ $v->content }}</td><br>
            </tr>
        </table>
        @endforeach
    </center>
</body>
</html>