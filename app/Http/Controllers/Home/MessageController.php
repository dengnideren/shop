<?php
namespace App\Http\Controllers\home;
use App\Http\Controllers\Controller;
use App\Http\Tools\Wechat;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use DB;
class MessageController extends Controller
{
    public $wechat;
    public function __construct(Wechat $wechat)
    {
        $this->wechat = $wechat;
    }

    // 用户同意授权，获取code
    public function login(){
    	$redirect_uri = 'http://www.shop.com/message/code';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID').'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect ';
        header('Location:'.$url);
    }

    // 通过code获得access_token
    public function code(Request $request){
    	$req = $request->all();
    	$code = $req['code'];
    	// 获取access_token
    	$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env("WECHAT_APPID")."&secret=".env("WECHAT_APPSECRET")."&code=".$code."&grant_type=authorization_code";
    	// 获取access_token
        $re = file_get_contents($url);
        $result = json_decode($re,1);//转为数组

        // 登录网站
        $access_token = $result['access_token'];
        $openid = $result['openid'];
        //获取用户基本信息
        $wechat_user_info = $this->wechat->wechat_user_info($openid);
        // dd($wechat_user_info);
        // 去user_openid 表查 是否有数据 openid = $openid
         $user_openid = DB::connection('mysql')->table("user_wechat")->where(['openid'=>$openid])->first();
        // dd($user_openid);
        if(!empty($user_openid)){
        	// 有数据 跳转粉丝列表页面
        	$user_info = DB::connection('mysql')->table("user")->where(['id'=>$user_openid->uid])->first();
            $request->session()->put('username',$user_info['name']);
            $openid_info = DB::connection('mysql')->table("wechat_openid")->select('openid')->limit(10)->get()->toArray();
	        foreach($openid_info as $v){
	            $this->wechat->push_template($v['openid']);
	        }
        	header('Location:http://www.shopdemo.com/wechat/get_user_info_list');
        }
    }

    // 发送留言视图
    public function send_message(Request $request){
    	$openid = $request->all()['openid'];
        // dd($openid);
    	return view('send_message',['openid'=>$openid]);
    }

    public function send_message_do(Request $request){
    	// 留言内容
    	$content = $request->all()['content'];
    	$openid = $request->all()['openid'];

    	$access_token = $this->wechat->get_access_token();
    	// 用户信息
    	$Wechat_openid_user = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
    	$Wechat_openid_info = json_decode($Wechat_openid_user,1);
    	$nickname = $Wechat_openid_info['nickname'];//名称
    	// dd();

    	// dd($openid);
    	// $this->wechat->push_template($openid);

    	$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->wechat->get_access_token();
        $data = [
            'touser'=>$openid,
            'template_id'=>'24V7_RpjhE7SmeIi2GLi27A2e-pr89uDDYKYSAoto8c',
            'url'=>'http://www.baidu.com',
            'data' => [
                'first' => [
                    'value' => $nickname,
                    'color' => ''
                ],
                'keyword' => [
                    'value' => $content,
                    'color' => ''
                ]
            ]
        ];
        // dd($data);
        $re = $this->wechat->post($url,json_encode($data));
        dd($re);
    }
}
