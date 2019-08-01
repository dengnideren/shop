@extends('layout.common')

@section('title','订单页')

@section('body')
    <!-- cart -->
    <div class="cart section">
        <div class="container">
            <div class="pages-head">
                <h3>确认订单</h3>
            </div>
            <div class="content">
                @foreach($data as $key=>$v)
                <div class="cart-1">
                    <div class="row">
                        <div class="col s5">
                            <h5>图片</h5>
                        </div>
                        <div class="col s7">
                            <img src="{{$v->goods_pic}}" alt="" width="30">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s5">
                            <h5>NAME</h5>
                        </div>
                        <div class="col s7">
                            <h5><a href="">{{$v->goods_name}}</a></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s5">
                            <h5>价格</h5>
                        </div>
                        <div class="col s7">
                            <h5>${{$v->goods_price}}</h5>
                        </div>
                    </div>
                </div>
                <div class="divider"></div>

            </div>
                <div class="row">
                    <div class="col s7">
                        <h6>Total</h6>
                    </div>
                    <div class="col s5">
                        <h6>${{$v->goods_price}}</h6>
                    </div>
                </div>
            </div>

            <button class="btn button-default"><a href="{{url('home/cart_do')}}?id={{$v->id}}">返回</a></button>
            <button class="btn button-default"><a href="{{url('home/listadd')}}?id={{$v->id}}">确认支付</a></button>
            @endforeach
        </div>
    </div>
    <!-- end cart -->

@endsection