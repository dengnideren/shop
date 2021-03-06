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
        <form  action="" method="post" id="formData" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="exampleInputEmail1">姓名</label>
            <input type="text" class="form-control" name="name" id="exampleInputEmail1" placeholder="姓名">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">年龄</label>
            <input type="text" class="form-control" name="age" id="exampleInputEmail1" placeholder="年龄">
        </div>
        <div class="form-group">
            <label for="exampleInputFile">图片</label>
            <input type="file" id="exampleInputFile" class="file" name="pic">
        </div>
        <button type="button" class="btn btn-default but">添加</button>
    </form>
    </center>
    <script src="/jquery.js"></script>
    <script type="text/javascript">
        //添加
        $('.but').on('click',function(){
            //上传图片
            var formData = new FormData($("#formData")[0]); //创建一个forData
            formData.append('file', $('.file')[0].files[0]); //把file添加进去  name命名为img
            $.ajax({
                url:"http://www.shop.com/app/member",
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                dataType:'json',
                success: function(res) {
                    console.log(res);
                    if(res.code==200){
                        alert(res.msg);
                        location.href='mem/index';
                    }else{
                        alert(res.msg);
                    }
                }
            })
        })
    </script>
</body>
</html>