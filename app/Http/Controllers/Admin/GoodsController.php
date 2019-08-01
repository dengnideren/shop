<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class GoodsController extends Controller
{
    public function index(Request $request)
    {
        // $data=$request->all();
        // $info=DB::table('goods')->paginate(5);
        // return view('admin/goodsList',['goods'=>$info]);
        DB::connection('mysql')->enableQueryLog();
        $info=DB::connection('mysql')->table('goods')->where('goods_name','like','%22%')->get()->toarray();
        $sql=DB::connection('mysql')->getQueryLog();
        $redis= new\Redis();
        $redis->connect('127.0.0.1','6379');
        $redis->incr('num');
        $num=$redis->get('num');
        echo "访问次数：",$num;
        $data=$request->all();
        $search="";
        if(!empty($data['search'])){
            $search=$data['search'];
            $info=DB::table('goods')->where('goods_name','like','%'.$data['search'].'%')->paginate(3);
        }else{
            $info=DB::table('goods')->paginate(3);
        }
        return view('admin/goodsList',['goods'=>$info,'search'=>$search]);
    }
    public function addgoods()
    {
        // dd(storage_path('app\public'));
        return view('admin/goods');
    }
    public function doaddgoods(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $path = $request->file('goods_pic')->store('goods');
        // dd($_FILES);
        $goods_pic=asset('storage').'/'.$path;
        $res=DB::table('goods')->insert([
                'goods_name'=>$data['goods_name'],
                'goods_num'=>$data['goods_num'],
                'goods_price'=>$data['goods_price'],
                'goods_pic'=>$goods_pic,
                'add_time'=>time(),
        ]);
        // dd($res);
        if($res){
            return redirect('goods/index');
        }else{
            echo "添加商品失败";
        }
    }
    public function delete(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $res=DB::table('goods')->where(['id'=>$data['id']])->delete();
        // dd($res);
        if($res){
            return redirect('goods/index');
        }else{
            echo '删除失败';
        }
    }
    public function edit(Request $request)
    {
        $data=$request->all();
        $res=DB::table('goods')->where(['id'=>$data['id']])->first();
        // dd($res);
        return view('admin/goodsEdit',['goods'=>$res]);
    }
    //处理修改
    public function update(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $path = $request->file('goods_pic')->store('goods');
        // dd($_FILES);
        $goods_pic=asset('storage').'/'.$path;
        $res=DB::table('goods')->where(['id'=>$data['id']])->update([
                'goods_name'=>$data['goods_name'],
                'goods_price'=>$data['goods_price'],
                'goods_num'=>$data['goods_num'],
                'goods_pic'=>$goods_pic,
                'add_time'=>time(),
            ]);
        if($res){
            return redirect('goods/index');
        }else{
            echo '修改失败';
        }
    }
}
