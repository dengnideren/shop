<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>购票列表</title>
</head>
<script src="{{ URL::asset('jquery.js') }}"></script>
<body>
    <center>
    <h1>购票列表</h1>
    <form method="get" action="{{url('index')}}">
        <input type="text" name="search" value="{{$search}}" placeholder="请输入出发地">
        <input type="submit" value="搜索">
    </form>
        <table border="1">
            <tr>
                <td>id</td>
                <td>车次</td>
                <td>出发地</td>
                <td>到达地</td>
                <td>价钱</td>
                <td>可购张数</td>
                <td>操作</td>
            @foreach($check as $v)
            </tr>
            <tr>
                <td>{{ $v->id }}</td>
                <td>{{ $v->check }}</td>
                <td>{{ $v->chufa }}</td>
                <td>{{ $v->doodad }}</td>
                <td>{{ $v->price }}</td>
                @if($v->number==0)
                <td>无</td>
                @elseif($v->number>100)
                <td>有</td>
                @else
                <td>{{$v->number}}</td>
                @endif
                <td><button class="check">有票</button></td>
            </tr>
            @endforeach
        </table>
        {{ $check->appends(['search'=>$search])->links() }}
    </center>
</body>
</html>

<script>
    $(function(){
      // alert(111);
        $('.check').click(function(){
            var _this = $(this);
            var state = _this.parent('td').prev('td').html();
            // console.log(status);
            if(state=='无'){
                _this.prop('disabled',true);
                return false;
            }
            // console.log(555);
        });
    });
</script>