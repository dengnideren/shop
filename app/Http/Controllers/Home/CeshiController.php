<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class CeshiController extends Controller
{
    //签到
    public function sign()
    {
        $iscontinue=false;
        //格式化时间  2019- - -
        //时间戳   10位
        //时间函数  time  获取当前时间戳  date 得到格式化时间  strtotime  把格式化时间转换为时间戳
        $sign=DB::table('sing_time')->where('uid',1)->orderBy('id','DESC')->first();//取当前用户最后一次签到的数据
        // dd($sign);
        if($sign){
            //判断今日是否签到
            $singtime=$sign->sign_time;
            //获取凌晨0点时间
            $time=date("Y-m-d");
            //转化为时间戳
            $time=strtotime($time);
            if($singtime > $time){
                echo "今日已签到";die;
            }
            //判断时间是否连续签到  咋天00:00  今天24点
            if($singtime<$time && $singtime>$time-86400){
                $iscontinue=true;
            }
        }
        //签到  添加入库
        $res=DB::table('sing_time')->insert([
                'sign_time'=>time(),
                'uid'=>1,
            ]);
        //送分  签到记录表
        $nice=DB::table('sign')->where('uid',1)->first();
        // dd($nice);
        $fen=$nice->fen;
        $signday=$nice->signday;
        if($signday < 3){
            $signday=$signday+1;
            $fen=$fen+$signday*5;
        }else{
            $signday=1;
            $fen=$fen+5;
        }
        $name='赵世伟';
        $data=DB::table('sign')->where('uid',1)->update(['name'=>$name,'signday'=>$signday,'fen'=>$fen]);
        echo "签到成功";die;
    }
    //登录
    public function login(Request $request)
    {
        return view('student/login');
    }
    //执行登陆
    public function dologin(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $res=DB::table('register')->insert([
                'name'=>$data['name'],
                'pwd'=>$data['pwd'],
                'add_time'=>time()
            ]);
        // dd($res);
        if($res){
            return redirect('ceshi/add');
        }
    }
    //学生添加
    public function add()
    {
        return view('student/add');
    }
    //执行添加
    public function doadd(Request $request)
    {
        $data=$request->all();
        $res=DB::table('student')->insert([
                'name'=>$data['name'],
                'age'=>$data['age'],
                'add_time'=>time(),
            ]);
        if($res){
            return redirect('ceshi/index');
        }
    }
    //学生列表
    public function index(Request $request)
    {
        DB::connection('mysql')->enableQueryLog();
        $info=DB::connection('mysql')->table('student')->where('name','like','%22%')->get()->toarray();
        $sql=DB::connection('mysql')->getQueryLog();
        $data=$request->all();
        // dd($data);
        $search="";
        if(!empty($data['search'])){
            $search=$data['search'];
            $info=DB::table('student')->where('name','like','%'.$data['search'].'%')->paginate(2);
        }else{
            $info=DB::table('student')->paginate(2);
        }
        return view('student/index',['data'=>$info,'search'=>$search]);
    }
    //学生删除
    public function delete(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $res=DB::table('student')->where(['id'=>$data['id']])->delete();
        // dd($res);
        if($res){
            return redirect('ceshi/index');
        }
    }
    //学生修改
    public function edit(Request $request)
    {
        $data=$request->all();
        $res=DB::table('student')->where(['id'=>$data['id']])->first();
        return view('student/edit',['data'=>$res]);
    }
    //学生执行修改
    public function update(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $res=DB::table('student')->where(['id'=>$data['id']])->update([
                'name'=>$data['name'],
                'age'=>$data['age'],
            ]);
        // dd($res);
        if($res){
            return redirect('ceshi/index');
        }
    }
}
