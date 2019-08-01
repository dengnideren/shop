<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        DB::connection('mysql')->enableQueryLog();
        $info=DB::connection('mysql')->table('check')->where('chufa','like','%22%')->get()->toarray();
        $sql=DB::connection('mysql')->getQueryLog();
        // dd($data);
        $data=$request->all();
        $search="";
        if(!empty($data['search'])){
            $search=$data['search'];
            $info=DB::table('check')->where('chufa','like','%'.$data['search'].'%')->paginate(8);
            $redis= new\Redis();
            $redis->connect('127.0.0.1','6379');
            $redis->incr('num');
            $num=$redis->get('num');
            echo "搜索次数：",$num;
        }else{
            $info=DB::table('check')->paginate(8);
        }
        return view('home/checkout',['check'=>$info,'search'=>$search]);
    }
}
