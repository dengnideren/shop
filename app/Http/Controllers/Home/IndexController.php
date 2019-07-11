<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class IndexController extends Controller
{
    //登录
    public function login(Request $request)
    {
        return view('home/login');
    }
    public function dologin(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $name=$data['name'];
        $request->session()->put('name',$name);
        // dd($request->session());
        $name1=session('name');
        $where=[
            ['name','=',$name],
        ];
        $res=DB::table('register')->where($where)->select('name')->get()->toArray();
        $name2=array_column($res,'name');
        if($name1==$name2[0]){
            return redirect('home/register');
        }else{
            return redirect('home/login');
        }
    }
    //注册
    public function register(Request $request)
    {
        return view('home/register');
    }
    public function doregister(Request $request)
    {
        $validate=$request->validate([
                'name'=>'unique:shop_register',
            ],['name.unique'=>'该名称已被注册']);
        $data=$request->all();
        $res=DB::table('register')->insert([
                'name'=>$data['name'],
                'email'=>$data['email'],
                'pwd'=>$data['pwd'],
                'add_time'=>time(),
        ]);
        // dd($res);
        if($res){
            return redirect('home/login');
        }else{
            echo "注册失败";
        }
    }
}
