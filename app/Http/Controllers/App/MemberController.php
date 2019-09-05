<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\model\Member;
class MemberController extends Controller
{
    /**
     * 展示
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search=$request->input('search');
        
        // dd($search);
        $data=Member::where('name','like','%'.$search.'%')->paginate(3);
        // dd($data);
        return json_encode(['code'=>200,'data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * 添加
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

    /**
     * 修改
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $data=$request->input('id');
        // dd($data);
        $res=Member::where(['id'=>$id])->first();
        // dd($res);
        return json_encode(['code'=>200,'data'=>$res]);
    }

    /**
     * 
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * 执行修改
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $id=$request->input('id');
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

    /**
     * 删除
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $data=$request->input('id');
        // dd($data);
        $res=Member::where(['id'=>$id])->delete();
        // dd($res);
        if($res){
            return json_encode(['code'=>200,'msg'=>'删除成功']);
        }else{
            return json_encode(['code'=>201,'msg'=>'删除失败']);
        }
    }
}
