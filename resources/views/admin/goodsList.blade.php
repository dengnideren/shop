@extends('layout.common')
@section('title','商品列表')
@section('body')
<div class="pages section">
        <div class="container">
            <div class="blog">
                <div class="row">
                    <div class="col s12">
                        <div class="blog-content">
                        @foreach($goods as $v)
                            <img src="{{$v->goods_pic}}" alt="" >
                            <div class="blog-detailt">
                                <h5><a href="blog-single.html">{{ $v->goods_name }}</a></h5>
                                <div class="date">
                                    <span><i class="fa fa-calendar"></i>{{$v->goods_price}}</span>
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste quasi sit aperiam quia voluptatem odio, facere iusto magni sunt, cumque quae, molestias temporibus ducimus repellendus!</p>
                            </div>
                        @endforeach
                        </div>
                    </div>
                    {{ $goods->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection