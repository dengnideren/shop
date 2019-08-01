@extends('layout.common')
@section('title','商品列表')
@section('body')
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>商品列表</title>
    </head>
    <body>
    <div class="pages section">
        <div class="container">
            <div class="pages-head">
            </div>
            <div class="login">
                <div class="row">
        <center>
        <h1>商品列表</h1>
        <form method="get" action="{{url('goods/index')}}">
            <input type="text" name="search" value="{{$search}}">
            <input type="submit" value="搜索">
        </form class="col s12">
            <table border="1">
                <tr>
                    <td>id</td>
                    <td>商品图片</td>
                    <td>商品名称</td>
                    <td>商品价格</td>
                    <td>添加时间</td>
                    <td>操作</td>
                @foreach($goods as $v)
                </tr>
                <tr>
                    <td>{{ $v->id }}</td>
                    <td><img src="{{$v->goods_pic}}" width="50px" height="50px" alt="" ></td>
                    <td>{{ $v->goods_name }}</td>
                    <td>{{ $v->goods_num }}</td>
                    <td>{{ $v->goods_price }}</td>
                    <td>{{ date('Y-m-d H:i:s',$v->add_time) }}</td>
                    <td>
                        <a href="{{url('goods/delete')}}?id={{$v->id}}">删除</a>
                        <a href="{{url('goods/edit')}}?id={{$v->id}}">修改</a>
                    </td>
                </tr>
                @endforeach
            </table>
            {{ $goods->appends(['search'=>$search])->links() }}
        </center>
             </div>
            </div>
        </div>
    </div>
    </body>
    </html>
@endsection
