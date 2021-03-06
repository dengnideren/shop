<?php
namespace  App\Http\Tools;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
class Wechat{
    public  $request;
    public  $client;
    public  $app;
    public function __construct(Request $request,Client $client)
    {
        $this->request = $request;
        $this->client = $client;
        $this->app = $app = app('wechat.official_account');
    }
    public function wechat_user_info($openid){
        $access_token = $this->get_access_token();
        $wechat_user = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
        $user_info = json_decode($wechat_user,1);
        return $user_info;
    }/**
         * 获取jsapi_ticket并且缓存
     */
    public function jsapi_ticket()
    {
        //获取access_token
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $jsapi_ticket_key = 'jsapi_ticket';
        if($redis->exists($jsapi_ticket_key)){
            //去缓存拿
            $jsapi_ticket = $redis->get($jsapi_ticket_key);
        }else{
            //去微信接口拿
            $jsapi_re = file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$this->get_access_token()."&type=jsapi");
            $jsapi_result = json_decode($jsapi_re,1);
            $jsapi_ticket = $jsapi_result['ticket'];
            $expire_time = $jsapi_result['expires_in'];
            //加入缓存
            $redis->set($jsapi_ticket_key,$jsapi_ticket,$expire_time);
        }
        return $jsapi_ticket;
    }
    // 根据标签id获取标签粉丝
    public function mark_user($tag_id){
        $url = 'https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token='.$this->get_access_token();
        $data = [
            'tagid' => $tag_id,
            'next_openid' => ''
        ];
        $re = $this->post($url,json_encode($data));
        return json_decode($re,1);
    }
    /**
     * 公众号的标签列表
     */
    public function wechat_tag_list()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->get_access_token();
        // dd($url);
        $re = file_get_contents($url);
        // dd($re);
        $data = json_decode($re,1);
        // dd($data);
        return $data;
    }
    /**
     * 上传微信素材资源
     */
    public function upload_source($up_type,$type,$title='',$desc=''){
        $file = $this->request->file($type);
        $file_ext = $file->getClientOriginalExtension();          //获取文件扩展名
        //重命名
        $new_file_name = time().rand(1000,9999). '.'.$file_ext;
        //文件保存路径
        //保存文件
        $save_file_path = $file->storeAs('wechat/video',$new_file_name);       //返回保存成功之后的文件路径
        $path = './storage/'.$save_file_path;
        if($up_type  == 1){
            $url='https://api.weixin.qq.com/cgi-bin/media/upload?access_token=' . $this->get_access_token().'&type='.$type;
        }elseif($up_type == 2){
            $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->get_access_token().'&type='.$type;
        }
        $multipart = [
            [
                'name'     => 'media',
                'contents' => fopen(realpath($path), 'r')
            ],
        ];
        if($type == 'video' && $up_type == 2){
            $multipart[] = [
                    'name'     => 'description',
                    'contents' => json_encode(['title'=>$title,'introduction'=>$desc])
            ];
        }
        $response = $this->client->request('POST',$url,[
            'multipart' => $multipart
        ]);
        //返回信息
        $body = $response->getBody();
        unlink($path);
        return $body;
    }
    public function post($url,$data = []){
        //初使化init方法
        $ch = curl_init();
        //指定URL
        curl_setopt($ch, CURLOPT_URL, $url);
        //设定请求后返回结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //声明使用POST方式来进行发送
        curl_setopt($ch, CURLOPT_POST, 1);
        //发送什么数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //忽略header头信息
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //设置超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        //发送请求
        $output = curl_exec($ch);
        //关闭curl
        curl_close($ch);
        //返回数据
        return $output;
    }
    public function get($url)
    {
        $ch = curl_init();
        //2设置
        curl_setopt($ch,CURLOPT_URL,$url); //访问地址
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //返回格式
        //请求网址是https
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false); // 对认证证书来源的检查
        //3执行
        $content = curl_exec($ch);
        //4关闭
        curl_close($ch);
        return $content;
    }
    //推送模板信息
    public function push_template($openid)
    {
        //$openid = 'otAUQ1XOd-dph7qQ_fDyDJqkUj90';
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->get_access_token();
        $data = [
            'touser'=>$openid,
            'template_id'=>'rwU1i2NPPjXoKDzWt7A-9wNcUtesvYpMOszU0bM4aCM',
            'url'=>'http://www.baidu.com',
            'data' => [
                'first' => [
                    'value' => '商品名称',
                    'color' => ''
                ],
                'keyword1' => [
                    'value' => '低价',
                    'color' => ''
                ],
                'keyword2' => [
                    'value' => '是低价',
                    'color' => ''
                ],
                'remark' => [
                    'value' => '备注',
                    'color' => ''
                ]
            ]
        ];
        $re = $this->post($url,json_encode($data));
        return $re;
    }
    //获取access_token
    public function get_access_token()
    {
         // 获取access_token
         // $sccess_token = '';
         $redis= new \Redis();
         $redis->connect('127.0.0.1','6379');
        //  dd($redis->connect);
         $access_token_key='wechat_access_token11';
        if($redis->exists($access_token_key)){
            //去缓存拿
            $access_token=$redis->get($access_token_key);
        }else{
            $access_re = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET'));
            $access_result=json_decode($access_re,1);
            $access_token=$access_result['access_token'];
            $expire_time=$access_result['expires_in'];
            //加入缓存
            $redis->set($access_token_key,$access_token,$expire_time);
        }
        return $access_token;
    }
}