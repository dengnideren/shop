<?php

namespace App\Http\Controllers\home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class StratorController extends Controller
{
    public function question()
    {
        return view('home/type/question');
    }

    public function questionadd(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $state= [1=>'A',2=>'B','3'=>'C',4=>'D'];
        $info=DB::table('startor')->insert([
             'title'=>$data['title'],
             'aa'=>$state[$data['aa']],
        ]);
        dd($info);
    }
    public function duoxuan(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $state= [1=>'A',2=>'B','3'=>'C',4=>'D'];
        $info=DB::table('startor')->insert([
             'title'=>$data['title'],
             'bb'=>$state[$data['bb']],
        ]);
        dd($info);
    }
    public function panduan(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $state= ['正确'=>'√','错误'=>'×'];
        $info=DB::table('startor')->insert([
             'title'=>$data['title'],
             'cc'=>$state[$data['cc']],
        ]);
        dd($info);
    }
    public function strator()
    {
        return view('home/type/strator');
    }
    public function stratordo(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $info=DB::table('pen')->insert([
             'name'=>$data['name'],
        ]);
        if($info){
            return redirect('stratorlist');
        }
    }
    public function stratorlist(Request $request)
    {
       $data=$request->all();
       $res=DB::table('pen')->get();
       $info=DB::table('startor')->get();
       return view('home/type/stratorlist',['data'=>$info,'pen'=>$res]);
    }

    public function fly(Request $request)
    {
        $data=$request->all('id');
        dd($data);
    }

}
