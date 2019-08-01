<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>我要竞猜</title>
</head>
<body>
    <center>
    <h1>我要竞猜</h1>
    <form action="{{url('guessadddo')}}" method="get">
    @csrf
    <table>
            @foreach($guess as $v)
            <tr>
                <td>{{ $v->name }}VS{{ $v->title }} </td>
            </tr>
            @endforeach
        </table>
        <input type="radio" name="defeat" value="1">胜
        <input type="radio" name="defeat" value="2">平
        <input type="radio" name="defeat" value="3">负<br>
        <input type="submit" value="添加竞猜">
    </form>
        
    </center>
</body>
</html>