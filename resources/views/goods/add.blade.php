@extends('layouts.bootstarp')
@section('content')
        <form  action="" method="post" id="formData" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="exampleInputEmail1">商品名称</label>
            <input type="text" class="form-control" name="goods_name" id="exampleInputEmail1" placeholder="商品名称">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">商品价格</label>
            <input type="text" class="form-control" name="goods_price" id="exampleInputEmail1" placeholder="商品价格">
        </div>
        <div class="form-group">
            <label for="exampleInputFile">商品图片</label>
            <input type="file" id="exampleInputFile" class="file" name="goods_pic">
        </div>
        <button type="button" class="btn btn-default but">添加</button>
    </form>
    <script src="/jquery.js"></script>
    <script>
        //添加
        $('.but').on('click',function(){
            //上传图片
            var formData = new FormData($("#formData")[0]); //创建一个forData
            formData.append('file', $('.file')[0].files[0]); //把file添加进去  name命名为img
            $.ajax({
                url:"http://www.shop.com/app/goods",
                data: formData,
                type: "POST",
                contentType: false,
                processData: false,
                dataType:'json',
                success: function(res) {
                    console.log(res);
                    if(res.code==200){
                        alert(res.msg);
                        location.href='index';
                    }else{
                        alert(res.msg);
                    }
                }
            })
        })
    </script>
@endsection