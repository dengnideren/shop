<?php
namespace App\Http\Controllers\home;
use App\Http\Controllers\Controller;
use App\Http\Tools\Wechat;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use DB;
class OilController extends Controller
{
    public $wechat;
    public function __construct(Wechat $wechat)
    {
        $this->wechat = $wechat;
    }
    /**
     * 微信消息推送
     */
    public function event()
    {
        //$this->checkSignature();
         $data = file_get_contents("php://input");
        //解析XML
        $xml = simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);        //将 xml字符串 转换成对象
        $xml = (array)$xml; //转化成数组
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents(storage_path('logs/wx_event.log'),$log_str,FILE_APPEND);
        if($xml['MsgType'] == 'event'){
            if($xml['Event'] == 'subscribe'){ //关注
                if(isset($xml['EventKey'])){
                    //拉新操作
                    $agent_code = explode('_',$xml['EventKey'])[1];
                    $agent_info = DB::connection('mysql')->table('user_agent')->where(['uid'=>$agent_code,'openid'=>$xml['FromUserName']])->first();
                    if(empty($agent_info)){
                        DB::connection('mysql')->table('user_agent')->insert([
                            'uid'=>$agent_code,
                            'openid'=>$xml['FromUserName'],
                            'add_time'=>time()
                        ]);
                    }
                }
                $message = '欢迎使用本公司提供的油价查询功能!';
                $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                echo $xml_str;
            }
        }elseif($xml['MsgType'] == 'text'){
            $message = '欢迎使用本公司提供的油价查询功能!';
            $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
            echo $xml_str;
        }
        //echo $_GET['echostr'];
    }
}
