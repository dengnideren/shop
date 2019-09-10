@extends('layouts.bootstarp')
@section('content')
        <form  action="" method="post" id="formData" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="exampleInputEmail1">姓名</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="姓名">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">年龄</label>
            <input type="text" class="form-control" name="age" id="age" placeholder="年龄">
        </div>
        <div class="form-group">
            <label for="exampleInputFile">图片</label>
            <input type="file" id="exampleInputFile" class="file" name="pic">
            <img src="" id='img_show'>
        </div>
        <button type="button" class="btn btn-default but">修改</button>
    </form>
    <script src="/jquery.js"></script>
    <script type="text/javascript">
        var url="http://www.shop.com/app/member";
        var id = getUrlparam("id");
        // alert(id);
        var base64Str;
        $(".file").on('change',function(){
            //模拟表单对象  FormData
            var file = $('[name="pic"]')[0].files[0]; //获取到文件
            var reader = new FileReader(); //h5 
            reader.readAsDataURL(file); //读base编码后的url地址
            reader.onload = function()
            {   
                base64Str = this.result;
                //console.log(this.result);
                $("#img_show").attr('src',this.result);
            }
        })
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
                data:{_method:'PUT',name:name,age:age,pic:base64Str},
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
@endsection