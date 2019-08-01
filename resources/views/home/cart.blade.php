@extends('layout.common')

@section('title','购物车')

@section('body')
    <!-- cart -->
    <div class="cart section">
        <div class="container">
        @foreach($data as $key=>$v)
            <div class="pages-head">
                <h3>CART</h3>
            </div>
            <div class="content">
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
                            <h5>数量</h5>
                        </div>
                        <div class="col s7">
                            <input value="1" type="text">
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
                    <div class="row">
                        <div class="col s5">
                            <h5>行动</h5>
                        </div>
                        <div class="col s7">
                            <h5><i class="fa fa-trash"></i></h5>
                        </div>
                    </div>
                </div>
                <div class="divider"></div>

            </div>
            <div class="total">
                <div class="row">
                    <div class="col s7">
                        <h5>{{$v->goods_name}}</h5>
                    </div>
                    <div class="col s5">
                        <h5>${{$v->goods_price}}</h5>
                    </div>
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
            <button class="btn button-default"><a href="{{url('home/listdo')}}?id={{$v->id}}">去结算</a></button>
            @endforeach
        </div>
    </div>
    <!-- end cart -->

@endsection