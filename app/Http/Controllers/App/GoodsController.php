<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\model\Goods;
class GoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
 //添加
    public function store(Request $request)
    {
        $goods_name = $request->input('goods_name');
        $goods_price = $request->input('goods_price');
        // var_dump($goods_name);
        // var_dump($goods_price);die;
        if(empty($goods_name) || empty($goods_price)){
            return json_encode(['code'=>202,'msg'=>'参数不能为空!']);
        }


        $goods_pic = $_FILES['file'];
        // var_dump($goods_img);die;
        $allowType = ['image/jpeg','image/jpg','image/png'];
        if(!in_array($goods_pic['type'], $allowType)){
            return json_encode(['code'=>205,'msg'=>'文件格式错误!']);die;
        }
        // 判断文件大小
        if($goods_pic['size'] > 1024*1024*2){
            return json_encode(['code'=>206,'msg'=>'上传文件过大!']);die;
        }
        // 判断错误号是否为0
        if($goods_pic['error'] != 0){
            return json_encode(['code'=>207,'msg'=>'文件上传错误!']);die;
        }
        $ext = pathinfo($goods_pic['name'],PATHINFO_EXTENSION); //后缀名
        $new_name = md5(time().rand(1000,9999)).".".$ext; // 生成新的文件名
        // 生成一个基于当前日期的文件夹
        $date = date("Y-n-j");
        if(!file_exists("./img/".$date)){
            mkdir("./img/".$date);
        }
        $dest = "./img/".$date."/".$new_name;
        // 文件信息
        move_uploaded_file($goods_pic['tmp_name'], $dest);  //在当前文件下 img文件下 根据日期建立文件夹进行存储


        $res =Goods::insert([
            'goods_name'=>$goods_name,
            'goods_price'=>$goods_price,
            'goods_pic'=>$dest,
            'add_time'=>time(),
        ]);
        // var_dump($res);die;
        if($res){
            return json_encode(['code'=>200,'msg'=>'添加成功!']);
        }else{
            return json_encode(['code'=>201,'msg'=>'添加失败!']);
        }

    }
    public function index(Request $request)
    {
        $search=$request->input('search');
        
        // dd($search);
        $data=Goods::where('goods_name','like','%'.$search.'%')->paginate(3)->toArray();
        // dd($data);
        return json_encode(['code'=>200,'data'=>$data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
