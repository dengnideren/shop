<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class CheckController extends Controller
{
    public function create()
    {
        return view('admin/check');
    }
    public function save(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $res=DB::table('check')->insert([
                'check'=>$data['check'],
                'chufa'=>$data['chufa'],
                'doodad'=>$data['doodad'],
                'price'=>$data['price'],
                'number'=>$data['number'],
                'add_time'=>time(),
                'doo_time'=>time(),
        ]);
        // dd($res);
        if($res){
            return redirect('index');
        }else{
            echo "添加失败";
        }
    }
}
