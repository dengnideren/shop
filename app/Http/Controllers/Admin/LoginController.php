<?php

namespace App\Http\Controllers\admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }
    public function dologin(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $res=DB::table('register')->insert([
            'name'=>$data['name'],
            'pwd'=>$data['pwd'],
            'add_time'=>time(),
        ]);
        $datas=DB::table('register')->where(['name'=>$data['name']])->first();
        // dd($datas);
        $datas=get_object_vars($datas);
        // $info=DB::table('register')->get()
        if(empty($res)){
            echo("<script>alert('登录失败');location='login/login'</script>");
           }else{
            session([
             'id'=>$datas['id'],
             'name'=>$datas['name']
            ]);
            echo("<script>alert('登录成功');location='/news/index'</script>");
           }
    }
}
