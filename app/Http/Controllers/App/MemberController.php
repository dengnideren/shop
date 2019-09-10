<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\model\Member;
use App\Http\Tools\Aes;
use App\Http\Tools\Rsa;
class MemberController extends Controller
{
    // public function __construct()
    // {
    //     $obj = new Aes('1234567890123456');
    //     $url = "what are you 弄啥嘞？";
    //     echo $eStr = $obj->encrypt($url);
    //     echo "<hr>";
    //     echo $obj->decrypt($eStr);
    // }    
    /**
     * 展示
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search=$request->input('search');
        // var_dump($search);die;
        $where=[];
        $where1=[];
        if(isset($search)){
            $where[]=['name','like',"%$search%"];
        }
        if(isset($search)){
            $where1[]=['age','like',"%$search%"];
        }
        //查询数据库
        $data=Member::where($where)->orwhere($where1)->paginate(3)->toArray();
        // var_dump($data);die;
        if(isset($search)){
            foreach($data['data'] as $k=>$v){
                $data['data'][$k]['name']=str_replace($search, "<b style='color:red'>".$search."</b>" ,$v['name']);
                $data['data'][$k]['age']=str_replace($search,"<b style='color:red'>".$search."</b>",$v['age']);    
            }
        }
        $obj = new Aes('fdjfdsfjakfjadii');
        $data=json_encode('authstr');
        echo $eStr = $obj->encrypt($data);
        // $Rsa=new Rsa();
        // $privkey =file_get_contents('private.php');//$keys['privkey'];
        // $pubkey  =file_get_contents('public.php');//$keys['pubkey'];
        // $Rsa->init($privkey, $pubkey,TRUE);
        // //原文
        // $data = '你妈妈让你回家吃饭了';
        // //私钥加密示例
        // $data = json_encode($data);
        // $data = $Rsa->priv_encode($data);
        // // $data = json_decode($data);
        // $data = $Rsa->pub_decode($data);
        // return json_decode($data);die;
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
        // var_dump($name);die;
        $age=$request->input('age');
        $image=$request->input('pic');
        $n = strpos($image,',');
        $str = substr($image, $n+1);
        $str = base64_decode($str);  
        // var_dump($str);die;
        $ext = $this->check_image_type($str);  // 获取后缀 JPG
        $ext = strtolower($ext); // 转为小写
        $imageName = md5(time().rand(1000,9999)).'.'.$ext; // 设置生成的图片名字

        $date = date("Y-n-j");    //设置图片保存路径
        if(!file_exists("./img/".$date)){   // 判断目录是否存在 不存在就创建 并赋予777权限
            mkdir("./img/".$date,0700);
        }
        $imageSrc= "./img/".$date."/". $imageName; //拼接路径和图片名称
        $r = file_put_contents($imageSrc, $str);//生成图片 返回的是字节数
        // var_dump($r);die;
        $data=Member::where(['id'=>$id])->first()->toArray();
        $image=$data['pic'];
        // var_dump($image);die;
        $res=Member::where(['id'=>$id])->update([
            'name'=>$name,
            'age'=>$age,
            'pic'=>$imageSrc,
        ]);
        // dd($res);
        if($res){
            unlink($image);
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
    // 判断文件格式
    function check_image_type($image)
    {
        $bits = array(
            'JPEG' => "\xFF\xD8\xFF",
            'GIF' => "GIF",
            'PNG' => "\x89\x50\x4e\x47\x0d\x0a\x1a\x0a",
            'BMP' => 'BM',
        );
        foreach ($bits as $type => $bit) {
            if (substr($image, 0, strlen($bit)) === $bit) {
                return $type;
            }
        }
        return 'UNKNOWN IMAGE TYPE';
    }
}
