<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="{{ URL::asset('jquery.js') }}"></script>
</head>
<body>
    <form action="{{url('strator/questionadd')}}" method="post">
    @csrf
        <select class="bb">
            <option value="单选">单选</option>
            <option value="多选">多选</option>
            <option value="判断">判断</option>
        </select>
        <div class="radio">
        <input type="text" name="title"><br>
        <input type="radio" name="aa" value="1">A<input type="text" ><br>
        <input type="radio" name="aa" value="2">B<input type="text" ><br>
        <input type="radio" name="aa" value="3">C<input type="text" ><br>
        <input type="radio" name="aa" value="4">D<input type="text" ><br>
        <input type="submit" value="添加">
    </div>
     </form>
     <form action="{{url('duoxuan')}}" method="post">
       @csrf
        <div class="checkbox">
        <input type="text" name="title"><br>
            <input type="checkbox" name="bb" value="1">A<input type="text" ><br>
            <input type="checkbox" name="bb" value="2">B<input type="text" ><br>
            <input type="checkbox" name="bb" value="3">C<input type="text" ><br>
            <input type="checkbox" name="bb" value="4">D<input type="text" ><br>
            <input type="submit" value="添加">
        </div>
    </form>
    <form action="{{url('panduan')}}" method="post">
    @csrf
        <div class="cc">
        <input type="text" name="title"><br>
            <input type="radio" name="cc" value="正确">正确
            <input type="radio" name="cc" value="错误">错误<br>
            <input type="submit" value="添加">
        </div>
    </form>
</body>
</html>
<script>
    $(function(){
        $('.radio').hide();
        $('.checkbox').hide();
        $('.cc').hide();
        $('.bb').click(function(){
            var name=$(this).val();
            if(name=='单选'){
                $('.radio').show();
                $('.checkbox').hide();
                $('.cc').hide();
            };
            if(name=='多选'){
                $('.checkbox').show();
                $('.radio').hide();
                $('.cc').hide();
            };
            if(name=='判断'){
                $('.cc').show();
                $('.checkbox').hide();
                $('.radio').hide();
            }
        });
    });
</script>