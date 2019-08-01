<?php

namespace App\Http\Controllers\admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class NewsController extends Controller
{
    public function info(Request $request)
    {
        $data=$request->all();
        dd($data);
        // $data =DB::table('news')->get()->toArray();
    	// $data = json_decode(json_encode($data),1);
    	// echo json_encode($data);
    }
    public function create()
    {
        $uid = session('id');
        if($uid==null){
        echo("<script>alert('请先登录');location='/login/login'</script>");
        }
        return view('newsCreate');
    }
    public function save(Request $request)
    {
        $uid = session('id');
        if($uid==null){
        echo("<script>alert('请先登录');location='/login/login'</script>");
        }
        $data=$request->all();
        // dd($data);
        $path = $request->file('pic')->store('goods');
        // dd($_FILES);
        $pic=asset('storage').'/'.$path;
        $res=DB::table('news')->insert([
            'title'=>$data['title'],
            'pic'=>$pic,
            'name'=>$data['name'],
            'content'=>$data['content'],
            'add_time'=>time(),
        ]);
        // dd($res);
        if($res){
            return redirect('news/index');
        }
    }
    public function index(Request $request)
    {
        $uid = session('id');
        // dd($uid);
        if($uid==null){
        echo("<script>alert('请先登录');location='/login/login'</script>");
        }
        DB::connection('mysql')->enableQueryLog();
        $info=DB::connection('mysql')->table('News')->get()->toarray();
        $sql=DB::connection('mysql')->getQueryLog();
        $data=$request->all();
        $info=DB::table('news')->paginate(2);
        return view('newsIndex',['news'=>$info]);
    }
    public function delete(Request $request)
    {
        $uid = session('id');
        if($uid==null){
        echo("<script>alert('请先登录');location='/login/login'</script>");
        }
        $data=$request->all();
        // dd($data);
        $res=DB::table('news')->where(['nid'=>$data['id']])->delete();
        // dd($res);
        if($res){
            return redirect('news/index');
        }
    }
    public function list(Request $request)
    {
        $uid = session('id');
        if($uid==null){
        echo("<script>alert('请先登录');location='/login/login'</script>");
        }
        $data=$request->all();
        $info=DB::connection('mysql')->table('News')->where(['nid'=>$data['id']])->get()->toarray();
        $redis= new\Redis();
        $redis->connect('127.0.0.1','6379');
        $redis->incr('num');
        $num=$redis->get('num');
        // dd($num);
        return view('newsList',['news'=>$info,'num'=>$num]);
    }
}
