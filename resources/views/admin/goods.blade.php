@extends('layout.common')
@section('title','商品添加')
@section('body')
    <!-- login -->
    <div class="pages section">
        <div class="container">
            <div class="pages-head">
            </div>
            <div class="login">
                <div class="row">
                    <form class="col s12" method="post" action="{{url('goods/doaddgoods')}}" enctype="multipart/form-data">
                    @csrf
                        <div class="input-field">
                            <input type="text" name="goods_name" class="validate" placeholder="NAME" required>
                        </div>
                        <div class="input-field">
                            <input type="text" name="goods_num" class="validate" placeholder="NUM" required>
                        </div>
                        <div class="input-field">
                            <input type="text" name="goods_price" class="validate" placeholder="PRICE" required>
                        </div>
                        <div class="input-field">
                            <input type="file" name="goods_pic" class="validate" placeholder="PIC" required>
                        </div>
                        <input type="submit" class="btn button-default" value="商品添加">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end login -->
@endsection