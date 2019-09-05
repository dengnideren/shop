<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>添加</title>
</head>
<body>
    <center>
        <form action="" method="post" enctype="multipart/form-data">
        @csrf
            <input type="text" name="name" id="name" placeholder="姓名"><br>
            <input type="text" name="age" id="age" placeholder="年龄 "><br>
            <input type="button" class="btn" value="添加">
        </form>
    </center>
    <script src="/jquery.js"></script>
    <script type="text/javascript">
        $(".btn").on('click',function(){
            // alert(1);
            var name=$("#name").val();
            var age=$("#age").val();
            console.log(name);
            console.log(age);
            $.ajax({
                url:"http://www.shop.com/app/member",
                type:'POST',
                data:{name:name,age:age},
                dataType:"json",
                success:function(res){
                    // location.href('index');
                    alert(res.msg);
                    location.href="http://www.shop.com/mem/index";
                }
            });
        });
    </script>
</body>
</html>