<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class LoginController extends Controller
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
        // $password=md5($data['password']);
        $where=[
            ['name','=',$data['name']],
            ['pwd','=',$data['pwd']],
        ];
        $arr=DB::table('register')->where($where)->first();
        $arr=get_object_vars($arr);
        // dd($arr);
        $state=$arr['state'];
        // dd($state);
        // dd($where);
        $count=DB::table('register')->count();
        // dd($count);
        if($count<=0){
            // echo 111;die;
            echo "登录失败,账户或者密码错误";die;
        }else{
            // echo 222;die;
            //将账户密码存入session
            session(['id'=>$arr['id'],'name'=>$data['name'],'pwd'=>$data['pwd'],'state'=>$state]);
            // dd(session('id'));
            return redirect('home/index');
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
                'name'=>'unique:register',
            ],['name.unique'=>'该名称已被注册']);
        $data=$request->all();
        $res=DB::table('register')->insert([
                'name'=>$data['name'],
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
