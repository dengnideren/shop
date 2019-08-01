@extends('layout.common')
@section('title','商品修改')
@section('body')
    <!-- login -->
    <div class="pages section">
        <div class="container">
            <div class="pages-head">
            </div>
            <div class="login">
                <div class="row">
                    <form class="col s12" method="post" action="{{url('goods/update')}}" enctype="multipart/form-data">
                    @csrf
                        <input type="hidden" name="id" value={{$goods->id}}>
                        <div class="input-field">
                            <input type="text" name="goods_name" placeholder="NAME" required  value="{{$goods->goods_name}}">
                        </div>
                        <div class="input-field">
                            <input type="text" name="goods_num" placeholder="NUM" required value="{{$goods->goods_num}}">
                        </div>
                        <div class="input-field">
                            <input type="text" name="goods_price" placeholder="PRICE" required value="{{$goods->goods_price}}">
                        </div>
                        <div class="input-field">
                            <input type="file" name="goods_pic" placeholder="PIC" required value="{{$goods->goods_pic}}">
                        </div>
                        <input type="submit" class="btn button-default" value="商品修改">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end login -->
@endsection