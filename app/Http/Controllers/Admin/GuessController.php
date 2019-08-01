<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class GuessController extends Controller
{
    public function guess()
    {
        return view('admin/guess');
    }
    public function guessdo(Request $request)
    {
        $data=$request->all();
        // dd($data);
        // dd($where);
        $res=DB::table('guess')->insert([
            'name'=>$data['name'],
            'title'=>$data['title'],
            'endtime'=>$data['endtime'],
        ]);
        // dd($res);
        if($res){
            return redirect('guesslist');
        }else{
            echo "添加失败";
        }
    }
    public function guesslist(Request $request)
    {
        $info=DB::connection('mysql')->table('guess')->get()->toarray();
        // dd($info);
        return view('admin/guesslist',['guess'=>$info]);
    }
    public  function guessadd(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $where=[
            'id'=>$data['id']
        ];
        $info=DB::connection('mysql')->table('guess')->where($where)->get()->toarray();
        // dd($info);

        return view('admin/guessadd',['guess'=>$info]);
    }
    public  function guessadddo(Request $request)
    {
        $data=$request->all();
        // dd($data);
        // $id=DB::table('guess')->get('id')->toarray();
        // dd($id);
        $state= [1=>'胜',2=>'平','3'=>'负'];
        $res=DB::table('def')->insert([
            // 'id'=>$id['id'],
            'defeat'=>$state[$data['defeat']],
        ]);
        // dd($res);
        if($res){
            return redirect('guesslist');
        }else{
            echo "竞猜失败";
        }
    }
    public function guessend(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $where=[
            'id'=>$data['id']
        ];
        $info=DB::connection('mysql')->table('guess')->where($where)->get()->toarray();
        // dd($info);
        return view('admin/guessend',['guess'=>$info]);
    }
    public  function guessenddo(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $dd=DB::table('guess')->get()->toarray();
        // dd($dd);
        $state= [1=>'胜',2=>'平','3'=>'负'];
        $res=DB::table('guessend')->insert([
            // 'id'=>$data['id'],
            'defeato'=>$state[$data['defeato']],
        ]);
        // dd($res);
        if($res){
            return redirect('guesslist');
        }else{
            echo "比赛结果失败";
        }
    }
    public function guessbai(Request $request)
    {
        // $data=$request->all();
        // dd($data);
        // $where=[
        //     'id'=>$data['id']
        // ];
        $id=$_GET['id'];
        // dd($info);
        $info=DB::table('guess')->join('def', 'id', '=', 'id')->where('id',$id)->first();
        dd($info);
        return view('admin/guessbai');
    }
}
