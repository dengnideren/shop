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
        $name = $request->input('name');
        $age = $request->input('age');
        // var_dump($goods_name);
        // var_dump($goods_price);die;
        if(empty($name) || empty($age)){
            return json_encode(['code'=>202,'msg'=>'参数不能为空!']);
        }


        $goods_img = $_FILES['file'];
        // var_dump($goods_img);die;
        $allowType = ['image/jpeg','image/jpg','image/png'];
        if(!in_array($goods_img['type'], $allowType)){
            return json_encode(['code'=>205,'msg'=>'文件格式错误!']);die;
        }
        // 判断文件大小
        if($goods_img['size'] > 1024*1024*2){
            return json_encode(['code'=>206,'msg'=>'上传文件过大!']);die;
        }
        // 判断错误号是否为0
        if($goods_img['error'] != 0){
            return json_encode(['code'=>207,'msg'=>'文件上传错误!']);die;
        }
        $ext = pathinfo($goods_img['name'],PATHINFO_EXTENSION); //后缀名
        $new_name = md5(time().rand(1000,9999)).".".$ext; // 生成新的文件名


        // 生成一个基于当前日期的文件夹
        $date = date("Y-n-j");
        if(!file_exists("./img/".$date)){
            mkdir("./img/".$date);
        }
        $dest = "./img/".$date."/".$new_name;
        // 文件信息
        move_uploaded_file($goods_img['tmp_name'], $dest);  //在当前文件下 img文件下 根据日期建立文件夹进行存储


        $res =Member::insert([
            'name'=>$name,
            'age'=>$age,
            'pic'=>$dest
        ]);
        // var_dump($res);die;
        if($res){
            return json_encode(['code'=>200,'msg'=>'添加成功!']);
        }else{
            return json_encode(['code'=>201,'msg'=>'添加失败!']);
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
        $data=Member::where(['id'=>$id])->first()->toArray();
        $image=$data['pic'];
        // dd($data);
        $res=Member::where(['id'=>$id])->delete();
        // dd($res);
        if($res){
            unlink($image);
            return json_encode(['code'=>200,'msg'=>'删除成功']);
        }else{
            return json_encode(['code'=>201,'msg'=>'删除失败']);
        }
    }
}
