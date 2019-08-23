<?php
namespace App\Http\Controllers\home;
use App\Http\Controllers\Controller;
use App\Http\Tools\Wechat;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use DB;
class LoveController extends Controller
{
	public $wechat;
    public function __construct(Wechat $wechat)
    {
        $this->wechat = $wechat;
    }
	public function send(Request $request){
		$openid = $request->all()['openid'];
		return view('loveadd',['openid'=>$openid]);
	}

	public function send_do(Request $request){
		$content = $request->all()['content'];
		// dd($content);
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
            'template_id'=>'30Iw6df_4uCWDA0cXD3La5MhP4wnvWhH5a0mYldsQ5M',
            'url'=>'http://www.baidu.com',
            'data' => [
                'first' => [
                    'value' => $nickname,
                    'color' => ''
                ],
                'keyword1' => [
                    'value' => $content,
                    'color' => ''
                ]
            ]
        ];
        $re = $this->wechat->post($url,json_encode($data));
        dd($re);
	}


}
