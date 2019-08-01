<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>添加票据</title>
</head>
<body>
    <center>
        <form method="post" action="{{url('admin/save')}}">
        @csrf
            车次：<input type="text" name="check"></br>
            出发地：<input type="text" name="chufa"></br>
            到达地：<input type="text" name="doodad"></br>
            价钱：<input type="text" name="price"></br>
            张数：<input type="text" name="number"></br>
            出发时间：<input type="text" name="add_time"></br>
            到达时间：<input type="text" name="doo_time"></br>
            <input type="submit" value="添加">
        </form>
    </center>
</body>
</html>