@extends('layouts.bootstarp')
@section('content')
<h2>列表</h2>
<div id="search" align="center">
<input type="text" name="search" id="search">
<button class="sou">搜索</button>
</div>
<div id="search_auto"></div>
        <table border="1" class="table table-bordered">
            <tr >
                <td class="col-sm-2">id</td>
                <td class="col-sm-2">商品名称</td>
                <td class="col-sm-2">商品价格</td>
                <td class="col-sm-2">商品图片</td>
                <td class="col-sm-2">操作</td>
            </tr>
            <tbody class="index">
                
            </tbody>
            
        </table>
        <a href="http://www.shop.com/goods/add" class="btn btn-success">添加</a>
        <nav aria-label="Page navigation">
          <ul style="color:red" class="pagination">
<!--             <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li> -->
          </ul>
        </nav>

    <script src="/jquery.js"></script>
    <script type="text/javascript">
        var url="http://www.shop.com/app/goods";
        // alert(url);
        $.ajax({
            url:url,
            dataType:"json",
            type:"GET",
            success:function(res){
                // alert(111);
                mypage(res);
            }
        })
        //搜索
        $(document).on('click','.sou',function(){
            // alert(111);
            var search=$("[name='search']").val();
            // console.log(search);
            if(search==""){
                alert('请输入查询姓名');
                location.href="http://www.shop.com/goods/index";
            }
            $.ajax({
                url:url,
                dataType:'json',
                data:{search:search},
                success:function(res){
                        mypage(res);
                }
            })
        })
        //分页
        $(document).on('click','.pagination a',function(){
            // alert(1);
            var page=$(this).attr('page');
            var search=$("[name='search']").val();
            // alert(page);
            $.ajax({
                url:url,
                dataType:'json',
                data:{page:page,search:search},
                success:function(res){
                        mypage(res);
                }
            })
        })
        function mypage(res){
            $('.index').empty();
                        $.each(res.data.data,function(i,v){
                        var tr = $("<tr></tr>"); //构建一个空对象
                        tr.append("<td>"+v.id+"</td>");
                        tr.append("<td>"+v.goods_name+"</td>");
                        tr.append("<td>"+v.goods_price+"</td>");
                        tr.append("<td align='center'><img src='/"+ v.goods_pic+"' alt='暂无图片' width='50'></td>");
                        tr.append("<td><a href='javascript:;' id='"+v.id+"' class='del btn btn-danger'>删除</a> <a href='javascript:;' id='"+v.id+"' class='update btn btn-info'>编辑</a></td>");
                            $(".index").append(tr);
                    })
                        //构建页码
                        var page="";
                        for(var i=1;i<=res.data.last_page;i++){
                            page += "<li><a page='"+i+"'>"+i+"</a></li>";
                        }
                        $('.pagination').html(page);
        }
    </script>
@endsection

