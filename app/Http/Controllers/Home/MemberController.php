<?php
namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;
use App\Http\Tools\Wechat;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use App\Http\model\Member;
use DB;
class MemberController extends Controller
{
    //添加
    public function add(Request $request)
    {
        $name=$request->input('name');
        $age=$request->input('age');
        if(empty($name) || empty($age)){
            return json_encode(['code'=>500,'msg'=>'参数不能为空']);
        }
        $res=Member::insert([
            'name'=>$name,
            'age'=>$age,
        ]);
        // dd($res);
        if($res){
            return json_encode(['code'=>200,'msg'=>'添加成功']);
        }else{
            return json_encode(['code'=>201,'msg'=>'添加失败']);
        }
    }
    public function index(Request $request)
    {
        $data=Member::get()->toarray();
        // dd($data);
        return json_encode($data);
    }
    public function del(Request $request)
    {
        $data=$request->input('id');
        // dd($data);
        $res=Member::where(['id'=>$data])->delete();
        // dd($res);
        if($res){
            return json_encode(['code'=>200,'msg'=>'删除成功']);
        }else{
            return json_encode(['code'=>201,'msg'=>'删除失败']);
        }
    }
    public function edit(Request $request)
    {
        $data=$request->input('id');
        // dd($data);
        $res=Member::where(['id'=>$data])->first();
        // dd($res);
        return json_encode(['code'=>200,'data'=>$res]);
    }
    public function update(Request $request)
    {
        $id=$request->input('id');
        // dd($id);
        $name=$request->input('name');
        $age=$request->input('age');
        $res=Member::where(['id'=>$id])->update([
            'name'=>$name,
            'age'=>$age,
        ]);
        // dd($res);
        if($res){
            return json_encode(['code'=>200,'msg'=>'修改成功']);
        }else{
            return json_encode(['code'=>201,'msg'=>'修改失败']);
        }
    }
}
