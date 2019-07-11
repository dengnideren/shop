<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class GoodsController extends Controller
{
    public function index(Request $request)
    {
        $data=$request->all();
        $info=DB::table('goods')->paginate(5);
        return view('admin/goodsList',['goods'=>$info]);
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
                'goods_price'=>$data['goods_price'],
                'goods_pic'=>$goods_pic,
                'add_time'=>time(),
        ]);
        // dd($res);
        if($res){
            return redirect('admin/index');
        }else{
            echo "添加商品失败";
        }

    }
}
