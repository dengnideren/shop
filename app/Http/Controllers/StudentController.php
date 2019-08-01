<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class StudentController extends Controller
{
    //登录
    public function login()
    {
        return view('login');
    }
    public function register()
    {
        return view('register');
    }
    public function do_login(Request $request)
    {
        $data=$request->all();
        $request->session()->put('name','...');
        return redirect('student/create');
    }
    //列表展示
    public function index(Request $request)
    {
        DB::connection('mysql')->enableQueryLog();
        $info=DB::connection('mysql')->table('check')->where('chufa','like','%22%')->get()->toarray();
        $sql=DB::connection('mysql')->getQueryLog();
        // var_dump($sql);die;
        $redis= new\Redis();
        $redis->connect('127.0.0.1','6379');
        $redis->incr('num');
        $num=$redis->get('num');
        echo "访问次数：",$num;
        $data=$request->all();
        $search="";
        if(!empty($data['search'])){
            $search=$data['search'];
            $info=DB::table('student')->where('name','like','%'.$data['search'].'%')->paginate(2);
        }else{
            $info=DB::table('student')->paginate(2);
        }
        return view('studentList',['student'=>$info,'search'=>$search]);
    }
    //添加视图
    public function create()
    {
        return view('studentCreate',[]);
    }
    //处理添加
    public function save(Request $request)
    {
        $validate=$request->validate([
                'name'=>'required',
                'age'=>'required',
            ],['name.required'=>'姓名必填','age.required'=>'年龄必填']);
        $data=$request->all();
        // dd($data);
        $res=DB::table('student')->insert([
                'name'=>$data['name'],
                'age'=>$data['age'],
                'sex'=>$data['sex'],
                'add_time'=>time(),
            ]);
        // dd($res);
        if($res){
            return redirect('student/index');
        }else{
            echo '添加失败';
        }
    }
    //删除
    public function delete(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $res=DB::table('student')->where(['id'=>$data['id']])->delete();
        // dd($res);
        if($res){
            return redirect('student/index');
        }else{
            echo '删除失败';
        }
    }
    //修改视图
    public function edit(Request $request)
    {
        $data=$request->all();
        $res=DB::table('student')->where(['id'=>$data['id']])->first();
        // dd($res);
        return view('studentEdit',['student'=>$res]);
    }
    //处理修改
    public function update(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $res=DB::table('student')->where(['id'=>$data['id']])->update([
                'name'=>$data['name'],
                'age'=>$data['age'],
                'sex'=>$data['sex'],
            ]);
        if($res){
            return redirect('student/index');
        }else{
            echo '修改失败';
        }
    }
}