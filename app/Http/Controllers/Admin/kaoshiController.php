<?php

namespace App\Http\Controllers\admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class kaoshiController extends Controller
{
    public function login()
    {
        return view('car\login');
    }
    public function dologin()
    {
        $data=request()->all();
        if(empty($data['user'])){
            echo "<script>alert('用户名不能为空');history.back(0)</script>";die;
        }
        if(empty($data['pwd'])){
            echo "<script>alert('密码不能为空');history.back(0)</script>";die;
        }
        $login=DB::table('admin')->where('user',$data['user'])->first();
        if(empty($login)){
            echo "<script>alert('用户名不存在');history.back(0)</script>";die;
        }
        if($data['pwd']!=$login->pwd){
            echo "<script>alert('密码错误');history.back(0)</script>";die;
        }
        session(['loginInfo'=>$data]);
        return redirect('car\index');
    }
    public function index()
    {
        return view('car\index');
    }
    public function addcar()
    {
        return view('car\addcar');
    }
    public function doaddcar()
    {
        $data=request()->all();
        unset($data['_token']);
        $carInfo=DB::table('car')->first();
        if(!empty($carInfo)){
            $data['shengyu']= $carInfo->shengyu+$data['sum'];
            $data['sum'] += $carInfo->sum;
            DB::table('car')->where('id',$carInfo->id)->update($data);
        }else{
            $data['shengyu']=$data['sum'];
            DB::table('car')->insert($data);
        }
        return redirect('car\index');
    }
    public function addmenwei()
    {
        return view('car\addmenwei');
    }
    public function doaddmenwei()
    {
        $data=request()->all();
        if(empty($data['user'])){
            echo "<script>alert('门卫名字不能为空');history.back(0)</script>";die;
        }
        unset($data['_token']);
        DB::table('menwei')->insert($data);
        return redirect('car\index');
    }
    public function admin()
    {
        $data=DB::table('car')->first();
        return view('car\admin',['data'=>$data]);
    }
    public function carin()
    {
        return view('car\carin');
    }
    public function docarin()
    {
        $data=request()->all();
        if(empty($data['chepaihao'])){
            echo "<script>alert('车牌号不能为空');history.back(0)</script>";die;
        }
        $inoutInfo=DB::table('inout')->where('chepaihao',$data['chepaihao'])->where('outtime',0)->first();
        if(!empty($inoutInfo)){
            echo "<script>alert('该车牌号未出库');history.back(0)</script>";die;
        }
        unset($data['_token']);
        $data['intime']=time();
        DB::table('inout')->insert($data);
        $carInfo=DB::table('car')->first();
        DB::table('car')->where('id',$carInfo->id)->update([
            'shengyu'=>$carInfo->shengyu-1,
            'num'=>$carInfo->num+1,
        ]);
        return redirect('car\admin');
    }
    public function carout()
    {
        return view('car\carout');
    }
    public function docarout()
    {
        $data=request()->all();
        if(empty($data['chepaihao'])){
            echo "<script>alert('车牌号不能为空');history.back(0)</script>";die;
        }
        $inoutInfo=DB::table('inout')->where('chepaihao',$data['chepaihao'])->where('intime','>',0)->where('outtime',0)->first();
        if(empty($inoutInfo)){
            echo "<script>alert('该车牌号未进库');history.back(0)</script>";die;
        }
        unset($data['_token']);
        $data['outtime']=time();
        $chaji=time()-($inoutInfo->intime);
        if($chaji<900){
            $data['money']=0;
        }else{
            if($chaji>=900&&$chaji<3600*6){
                $ban=(int)ceil($chaji/1800);
                $data['money']=$ban*2;
            }else{
                $zheng=(int)ceil($chaji/1800);
                $data['money']=24+($zheng-6);
            }
        }
        DB::table('inout')->where('id',$inoutInfo->id)->update($data);
        $carInfo=DB::table('car')->first();
        DB::table('car')->where('id',$carInfo->id)->update([
            'shengyu'=>$carInfo->shengyu+1,
            'money'=>$carInfo->money+$data['money'],
        ]);
        return redirect()->action('kaoshiController@detail',['id'=>$inoutInfo->id]);
    }
    public function detail()
    {
        $id=request()->get('id');
        $data=DB::table('inout')->where('id',$id)->first();
        $chaji=($data->outtime)-($data->intime);
        if($chaji>3600){
            $hours=(int)floor($chaji/60*60);
            $minute=(int)ceil(($chaji-$hours*3600)/60);
        }else{
            $minute=(int)ceil($chaji/60);
            $hours=0;
        }
        return view('car\detail',['data'=>$data,'hours'=>$hours,'minute'=>$minute]);
    }
    public function info()
    {
        $data=DB::table('car')->first();
        return view('car\info',['data'=>$data]);
    }
    public function logout(Request $request)
    {
        $request->session()->forget('loginInfo');
        return redirect('car\login');
    }
}
