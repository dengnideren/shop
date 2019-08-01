<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>竞猜列表</title>
</head>
<body>
    <center>
    <h1>竞猜列表</h1>
        <table>
            @foreach($guess as $v)
            <tr>
                <td>{{ $v->name }}VS{{ $v->title }} 
                @if($v->endtime > date('Y-m-d',time()))
                <button><a href="{{url('guessadd')}}?id={{$v->id}}">竞猜</a></button>||<button><a href="{{url('guessend')}}?id={{$v->id}}">添加竞猜结果</a></button>
                @else
                <button><a href="{{url('guessbai')}}?id={{$v->id}}">查看结果</a></button>
                @endif
                </td>
                
            </tr>
            @endforeach
        </table>
    
    </center>
</body>
</html>