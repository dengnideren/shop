<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Http\Tools\Aes;
class CeController extends Controller
{
    public function ase(Request $request)
    {   
        $authstr = $request->input('authstr');
        $obj = new Aes('asdfghjklzxcvbnm');
        $authstr = $obj->decrypt($authstr); //字符串
        if(!$authstr){
            if(empty($name) || empty($age) || empty($mobile)){
                return json_encode(['code'=>201,'msg'=>'小老弟你还嫩的很'],JSON_UNESCAPED_UNICODE);
            }
        }
        $authArr = explode("&",$authstr);
        $args = [];
        foreach ($authArr as $key => $value) {
            $argsArr = explode("=",$value);
            $args[$argsArr[0]] = $argsArr[1];
        }
        //echo 1;die;
        $name = empty($args['name']) ? "" : $args['name'];
        $age = empty($args['age']) ? "" : $args['age'];
        $mobile = empty($args['mobile']) ? "" : $args['mobile'];
 
        if(empty($name) || empty($age) || empty($mobile)){
            return json_encode(['code'=>201,'msg'=>'笑死我了'],JSON_UNESCAPED_UNICODE);
        }
        $ip = $_SERVER['REMOTE_ADDR'];
 
        $data = \DB::table('test')->where(['ip'=>$ip])->first();
        //var_dump($data);die;
        if($data){
            return json_encode(['code'=>201,'msg'=>'数据已经入库了'],JSON_UNESCAPED_UNICODE);
        }
        $res = \DB::table('test')->insert(
            [
                'name' => $name, 
                'age' => $age,
                'mobile'=>$mobile,
            ]);
        if($res){
        	return json_encode(['code'=>200,'msg'=>'恭喜你,入库成功'],JSON_UNESCAPED_UNICODE);
        }
    }
}

