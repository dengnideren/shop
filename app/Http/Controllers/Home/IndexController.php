<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class IndexController extends Controller
{
    public function index(Request $request)
    {
        // $data=$request->all();
        $res=DB::table('goods')->get();
        // dd($res);
        return view('home/index',['goods'=>$res]);
    }
    //详情页
    public function single(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $where=[
            'id'=>$data['id'],
        ];
        // dd($where);
        $res=DB::table('goods')->where($where)->get();
        // dd($res);
        return view('home/single',['goods'=>$res]);
    }
    // 购物车视图
    public function cart_do(Request $request)
    {
        $uid = session('id');
        if($uid==null){
            echo ("<script>alert('请先登录');location='/home/login'</script>");
        }
        // dd($uid);
        $data =DB::table('cart')->where(['uid'=>$uid])->get();
        // dd($data);
        return view('home/cart',['data'=>$data]);
    }
    // 加入购物车
    public function cart(Request $request)
    {
        $id = $request->get('id');
        // dd($id);
        $uid = session('id');
        // dd($uid);
        $data =DB::table('goods')->where(['id'=>$id])->first();
        // dd($data);

        $data=get_object_vars($data);
        $res =DB::table('cart')->where(['id'=>$id])->insert([
            'uid'=>$uid,
            'goods_name'=>$data['goods_name'],
            'goods_id'=>$data['id'],
            'goods_pic'=>$data['goods_pic'],
            'goods_price'=>$data['goods_price'],
            'add_time'=>time(),
        ]);
        // $cate=DB::table('cart')->select('goods_id')->get();
        // $cate=get_object_vars($cate);
        // if($cate['goods_id']==$data['id']){
        //     echo ("<script>alert('该商品已存在');location='/home/index'</script>");
        // }
        // dd($res);
        if($res){
            echo ("<script>alert('加入购物车成功,跳转到购物车页面');location='/home/cart_do'</script>");
        }else{
            echo ("<script>alert('加入失败');location='/home/buy'</script>");
        }
    }
    //确认订单
    public function listdo(Request $request)
    {
        // $data=$request->get('id');
        $data=DB::table('cart')->get();
        // dd($data);
        return view('home/list',['data'=>$data]);
    }
    //订单添加
    public function listadd(Request $request)
    {
        $id=$request->get('id');
        // dd($data);
         $uid = session('id');
        // dd($uid);
        // 订单编号
        $oid = time().rand(1000,9999);
        // dd($oid);
        $data =DB::table('cart')->where(['id'=>$id])->first();
        // dd($data);
        $data=get_object_vars($data);
        $res =DB::table('order')->insert([
            'oid'=>$oid,
            'uid'=>$uid,
            'pay_money'=>$data['goods_price'],
            'pay_time'=>time(),
            'add_time'=>time()
        ]);
        // dd($res);
        if($res){
            echo ("<script>alert('正在前往支付页面');location='/pay'</script>");
        }
    }

}
