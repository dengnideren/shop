<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>修改</title>
</head>
<body>
    <center>
        <form action="" method="post" enctype="multipart/form-data">
        @csrf
            <input type="text" name="name" id="name"  placeholder="姓名"><br>
            <input type="text" name="age" id="age"  placeholder="年龄 "><br>
            <button class="btn">修改</button>
        </form>
    </center>
    <script src="/jquery.js"></script>
    <script type="text/javascript">
        var url="http://www.shop.com/app/member";
        var id = getUrlparam("id");
        // alert(id);
        $.ajax({
            url:url+'/'+id,
            dataType:"json",
            success:function(res){
                $("[name='name']").val(res.data.name);
                $("[name='age']").val(res.data.age);
            }
        })
        $(".btn").on('click',function(){
            var name = $("#name").val();
            var age = $("#age").val();
            // alert(name);
            $.ajax({
                url:url+'/'+id,
                data:{_method:'PUT',name:name,age:age},
                dataType:"json",
                type:"POST",
                success:function(res){
                    alert(res.msg);
                    location.href="http://www.shop.com/mem/index";
                }
            });
        })


        function getUrlparam(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]); return null;
        }
    </script>
</body>
</html>