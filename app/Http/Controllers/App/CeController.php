<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Http\Tools\Aes;
class CeController extends Controller
{
    public function addUser(Request $request)
    {   
        $authstr = $request->input('authstr');
        $obj = new Aes('fdjfdsfjakfjadii');
        $authstr = $obj->decrypt($authstr); //字符串
        if(!$authstr){
            if(empty($name) || empty($age) || empty($mobile)){
                return json_encode(['ret'=>0,'msg'=>'加密不对兄弟'],JSON_UNESCAPED_UNICODE);
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
            return json_encode(['ret'=>0,'msg'=>'别逗我了,参数不对'],JSON_UNESCAPED_UNICODE);
        }
        $ip = $_SERVER['REMOTE_ADDR'];
 
        $data = \DB::table('test')->where(['ip'=>$ip])->first();
        //var_dump($data);die;
        if($data){
            return json_encode(['ret'=>0,'msg'=>'当前ip已经录入数据了'],JSON_UNESCAPED_UNICODE);
        }
        $res = \DB::table('test')->insert(
            [
                'name' => $name, 
                'age' => $age,
                'mobile'=>$mobile,
            ]);
        if($res){
        	return json_encode(['ret'=>1,'msg'=>'入库成功'],JSON_UNESCAPED_UNICODE);
        }
    }
}

