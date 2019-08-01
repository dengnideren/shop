<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>竞猜结果</title>
</head>
<body>
    <center>
    <h1>竞猜结果</h1>
    <form action="{{url('guessenddo')}}" method="get">
    @csrf
    <table>
            @foreach($guess as $v)
            <tr>
                <td>{{ $v->name }}VS{{ $v->title }} </td>
            </tr>
            @endforeach
        </table>
        <input type="radio" name="defeato" value="1">胜
        <input type="radio" name="defeato" value="2">平
        <input type="radio" name="defeato" value="3">负<br>
        <input type="submit" value="比赛结果">
    </form>
        
    </center>
</body>
</html>