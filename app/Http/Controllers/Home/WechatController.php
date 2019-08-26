<?php

namespace App\Http\Controllers\home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Tools\Wechat;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class WechatController extends Controller
{
    public $request;
    public $wechat;
    public $redis;
    public function __construct(Request $request,Wechat $wechat)
    {
        $this->request = $request;
        $this->wechat = $wechat;
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1','6379');
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
        //echo "<pre>";print_r($xml);
        \Log::Info(json_encode($xml));  //输出收到的信息
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents(storage_path('logs/wx_event.log'),$log_str,FILE_APPEND);
        if($xml['MsgType'] == 'event'){
            if($xml['Event'] == 'subscribe'){ //关注
                if(!empty($xml['EventKey'])){
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
                $message = '欢迎使用本公司提供的油价查询功能';
                $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                echo $xml_str;
            }elseif($xml['Event'] == 'location_select'){
                $message = $xml['SendLocationInfo']->Label;
                \Log::Info($message);
                $xml_str = '<xml><ToUserName><![CDATA[otAUQ1UtX-nKATwQMq5euKLME2fg]]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                echo $xml_str;
            }elseif($xml['Event'] == 'CLICK'){
                if($xml['EventKey'] == 'my_biaobai'){
                    $biaobai_info = DB::connection('mysql')->table('biaobai')->where(['from_user'=>$xml['FromUserName']])->get()->toArray();
                    $message = '';
                    foreach($biaobai_info as $v){
                        $message .= $v->content."\n";
                    }
                    $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                    echo $xml_str;
                }
            }
        }elseif($xml['MsgType'] == 'text'){
            $preg_result = preg_match('/.*?油价/',$xml['Content']);
            if($preg_result){
                //查询油价
                $city = substr($xml['Content'],0,-6);
                // dd($city);
                $price_info = file_get_contents('http://shopdemo.18022480300.com/price/api');
                $price_arr = json_decode($price_info,1);
                $support_arr = [];
                foreach($price_arr['result'] as $v){
                    $support_arr[] = $v['city'];
                }
                if(!in_array($city,$support_arr)){
                    $message = '查询城市不支持！';
                    $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                    echo $xml_str;
                    die();
                }
                foreach($price_arr['result'] as $v){
                    if($city == $v['city']){
                        $this->redis->incr($city);
                        $find_num = $this->redis->get($city);
                        //缓存操作
                        if($find_num > 10){
                            if($this->redis->exists($city.'信息')){
                                //存在
                                $v_info = $this->redis->get($city.'信息');
                                $v = json_decode($v_info,1);
                            }else{
                                $this->redis->set($city.'信息',json_encode($v));
                            }
                        }
                        //$message = $city.'目前油价：'."\n";
                        $message = $city.'目前油价：'."\n".'92h：'.$v['92h']."\n".'95h：'.$v['95h']."\n".'98h：'.$v['98h']."\n".'0h：'.$v['0h'];
                        $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                        echo $xml_str;
                        die();
                    }
                }
            }
            /*$message = '你好!';
            $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
            echo $xml_str;*/
        }
        //echo $_GET['echostr'];  //第一次访问
    }
    public function get_user_info()
    {
        $access_token=$this->wechat->get_access_token();
        $info=$this->get_user_list();
        // return $info;
        // // dd($id);
        $openid="oSfq3tw5hR-WgJzH314-p0CVqjFA";
        // dd($openid);
        // foreach($info['data']['openid'] as $v){
        $wechat=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
            $user_info=json_decode($wechat,1);
        // dd($user_info);
        $state= ['1'=>'已关注','0'=>'未关注'];
        $sta= ['1'=>'男','2'=>'女'];
        $data=DB::table('wechat_openid')->insert([
            'openid'=>$user_info['openid'],
            'subscribe'=>$state[$user_info['subscribe']],
            'nickname'=>$user_info['nickname'],
            'language'=>$user_info['language'],
            'city'=>$user_info['city'],
            'province'=>$user_info['province'],
            'country'=>$user_info['country'],
            'sex'=>$sta[$user_info['sex']],
            'headimgurl'=>$user_info['headimgurl'],
            'add_time'=>time(),
        ]);

        dd($data);
        // dd($user_info);
    }
    //用户列表
    public function get_user_list()
    {
        $access_token=$this->wechat->get_access_token();
        // dd($access_token);
        // 获取关注用户列表
        $wechat=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/get?access_token={$access_token}&next_openid=");
        $user_info=json_decode($wechat,1);
        // $id=$user_info['data']['openid'];
        // dd($openid);
        // return $user_info;
        $data=DB::table('wechat_openid')->get()->toarray();
        // dd($data);
        return view('home/wechat',['wechat'=>$data]);
        // return $user_info;
    }
    //用户详情
    public function wechatadd(Request $request)
    {
        $data=$request->all();
        // dd($data);
        $where=[
            'id'=>$data['id']
        ];
        $res=DB::table('wechat_openid')->where($where)->get()->toarray();
        return view('home/wechatadd',['wechat'=>$res]);
    }
    //注册登录
    public function code(Request $request)
    {
        $appid="wx4dbcb6925b134ec2";
        $appsecret="03d94930a4cbca1987e966c66e5b6a50";
        $req=$request->all();
        dd($req);
        $code=$req['code'];
        //获取access_token
        $url=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET')."&code=".$code."&grant_type=authorization_code");
        // dd($url);
        $result=json_decode($url,1);
        // dd($result);
        $access_token=$result['access_token'];
        $openid=$result['openid'];
        //获取用户基本信息
        $wechat_user_info = $this->wechat->wechat_user_info($openid);
        //去user_openid 表查 是否有数据 openid = $openid
        $user_openid = DB::connection('mysql')->table("user_wechat")->where(['openid'=>$openid])->first();
        if(!empty($user_openid)){
            //有数据 在网站有用户 user表有数据[ 登陆 ]
            $user_info = DB::connection('mysql')->table("user")->where(['id'=>$user_openid->uid])->first();
            $request->session()->put('username',$user_info['name']);
            $openid_info = DB::connection('mysql')->table("wechat_openid")->select('openid')->limit(10)->get()->toArray();
            foreach($openid_info as $v){
                $this->wechat->push_template($v->openid);
            }
            header('Location:get_user_list');
        }else{
            //没有数据 注册信息  insert user  user_openid   生成新用户
            DB::connection("mysql")->beginTransaction();
            $user_result = DB::connection('mysql')->table('user')->insertGetId([
                'password' => '',
                'name' => $wechat_user_info['nickname'],
                'reg_time' => time()
            ]);
            $openid_result = DB::connection('mysql')->table('user_wechat')->insert([
                'uid'=>$user_result,
                'openid' => $openid,
            ]);
            DB::connection('mysql')->commit();
            //登陆操作
            $user_info = DB::connection('mysql')->table("user")->where(['id'=>$user_openid->uid])->first();
            $request->session()->put('username',$user_info['name']);
            $openid_info = DB::connection('mysql')->table("wechat_openid")->select('openid')->limit(10)->get()->toArray();
            foreach($openid_info as $v){
                $this->wechat->push_template($v->openid);
            }
            header('Location:get_user_list');
        }
    }
    public function login(Request $request)
    {
        $appid="wx4dbcb6925b134ec2";
        $appsecret="03d94930a4cbca1987e966c66e5b6a50";
        $redirect="http://www.shop.com/wechat/code";
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WECHAT_APPID')."&redirect_uri=".urlencode($redirect)."&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
        header('Location:'.$url);
    }
    /**
     * 推送模板消息
     */
    public function push_template()
    {
        $openid_info = DB::connection('mysql')->table("wechat_openid")->select('openid')->limit(10)->get()->toArray();
        foreach($openid_info as $v){
            $this->wechat->push_template($v->openid);
        }
    }
    //模板列表
    public function template_list()
    {
        $url='https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token='.$this->wechat->get_access_token();
        // dd($url);
        $re=file_get_contents($url);
        // dd($re);
        $data=json_decode($re,1);
        dd($data);

    }
    //模板删除
    public function del_template()
    {
        $url='https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token='.$this->wechat->get_access_token();
        $data=[
            'template_id'=>'j3cDY5_BHbFeUjL5zcVX7zgAJB6KeQJXoguVcHtUU7I',
        ];
        // dd($data);
        $re=$this->wechat->post($url,json_encode($data));
        // dd($re);
    }
    /**
     * 我的素材
     */
    public function upload_source()
    {
       $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$this->wechat->get_access_token();
       $data = ['type'=>'image','offset'=>0,'count'=>20];
       $re = $this->wechat->post($url,json_encode($data));
       // echo '<pre>';
       // dd($re);
       print_r(json_decode($re,1));
        return view('Wechat.uploadSource');
    }
     public function get_voice_source()
    {
        $media_id = 'UKml31rzRRlr8lYfWgAno9mGe-meph0BKmVtZugAHQTqZIxOhUoBvCnqfJMRMKTG';
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->wechat->get_access_token().'&media_id='.$media_id;
        // echo $url;echo '</br>';
        //保存图片
        $client = new Client();
        $response = $client->get($url);
        //$h = $response->getHeaders();
        //echo '<pre>';print_r($h);echo '</pre>';die;
        //获取文件名
        $file_info = $response->getHeader('Content-disposition');
        $file_name = substr(rtrim($file_info[0],'"'),-20);
        //$wx_image_path = 'wx/images/'.$file_name;
        //保存图片
        $path = 'wechat/voice/'.$file_name;
        $re = Storage::disk('local')->put($path, $response->getBody());
        echo env('APP_URL').'/storage/'.$path;
        dd($re);
    }
    public function get_video_source(){
        $media_id = 'goXBTwdaZIHEG0R1qW87flgRrMjBowpwW5OVPsiOGnYX7xeG0CIbaFMWzOAc22WR'; //视频
        $url = 'http://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->wechat->get_access_token().'&media_id='.$media_id;
        $client = new Client();
        $response = $client->get($url);
        // echo $response->getBody();
        // dd();
        $video_url = json_decode($response->getBody(),1)['video_url'];
        $file_name = explode('/',parse_url($video_url)['path'])[2];
        // var_dump(parse_url($video_url));
        // echo $file_name;
        // dd();
        //设置超时参数
        $opts=array(
            "http"=>array(
                "method"=>"GET",
                "timeout"=>3  //单位秒
            ),
        );
        //创建数据流上下文
        $context = stream_context_create($opts);
        //$url请求的地址，例如：
        $read = file_get_contents($video_url,false, $context);
        $re = file_put_contents('./storage/wechat/video/'.$file_name,$read);
        var_dump($re);
        die();
    }
    public function get_source()
    {
        $media_id = 'R2Z_JIZw0Nn0u_IPfIlISCGnmo0XqbELRTnB9G_1DIU3VQPVvdtHo6KaRX9DKR6D'; //图片
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->wechat->get_access_token().'&media_id='.$media_id;
        // echo $url;echo '</br>';
        //保存图片
        $client = new Client();
        $response = $client->get($url);
        //$h = $response->getHeaders();
        //echo '<pre>';print_r($h);echo '</pre>';die;
        //获取文件名
        $file_info = $response->getHeader('Content-disposition');
        $file_name = substr(rtrim($file_info[0],'"'),-20);
        //$wx_image_path = 'wx/images/'.$file_name;
        //保存图片
        $path = 'wechat/image/'.$file_name;
        $re = Storage::disk('local')->put($path, $response->getBody());
        echo env('APP_URL').'/storage/'.$path;
        dd($re);
        //return $file_name;
    }
    /**
     * 上传资源
     * @param Request $request
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function do_upload(Request $request)
    {
        $upload_type = $request['up_type'];
        $re = '';
        if($request->hasFile('image')){
            //图片类型
            $re = $this->wechat->upload_source($upload_type,'image');
        }elseif($request->hasFile('voice')){
            //音频类型
            //保存文件
            $re = $this->wechat->upload_source($upload_type,'voice');
        }elseif($request->hasFile('video')){
            //视频
            //保存文件
            $re = $this->wechat->upload_source($upload_type,'video','视频标题','视频描述');
        }elseif($request->hasFile('thumb')){
            //缩略图
            $path = $request->file('thumb')->store('wechat/thumb');
        }
        echo $re;
        dd();
    }
    //删除素材
    public function der()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/del_material?access_token='.$this->wechat->get_access_token();
        $data=[
            'media_id'=>'bAYOpFn8CZE-Zbq_VFUDlugbRZ249EnPWSGpbQC4tRE'
        ];
        $re = $this->wechat->post($url,json_encode($data));
        dd($re);
    }

    //创建标签
    public function label_doadd(Request $request)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.$this->wechat->get_access_token();
        $data = [
            'tag' => ['name'=>$request->all()['name']]
        ];
        $json=json_encode($data,JSON_UNESCAPED_UNICODE);
        // dd($json);
        // dd($data);
        $re = $this->wechat->post($url,$json);
        if($re){
            echo ("<script>alert('创建标签成功');location='/wechat/label_list';</script>");
         }else{
            echo ("<script>alert('创建标签失败');location='/wechat/label_list';</script>");
        }
        // dd($re);
    }
    public function label_add()
    {
        return view('wechat.addTag');
    }
    //标签列表
    public function label_list()
    {
        $data = $this->wechat->wechat_tag_list();
        // dd($data);
        return view('wechat/tagList',['info'=>$data['tags']]);
    }
    // 编辑标签视图
    public function label_update(Request $request){
        $tag_id = $request->all()['tag_id'];
        // dd($tag_id);
        return view('wechat.upd_label',['tag_id'=>$tag_id]);
    }
    // 编辑标签
    public function label_edit(Request $request){
        $data = $request->all();
        $tag_id = $data['tag_id'];
        $name = $data['name'];
        $url = "https://api.weixin.qq.com/cgi-bin/tags/update?access_token=".$this->wechat->get_access_token();
        $data = [
            'tag'=>['id'=>$tag_id,'name'=>$name]
        ];
        $re = $this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        // dd($re);
        if($re){
            echo ("<script>alert('修改成功');location='/wechat/label_list';</script>");
         }else{
            echo ("<script>alert('修改失败');location='/wechat/label_list';</script>");
        }

    }
    //删除标签
    public function label_del(Request $request)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/delete?access_token='.$this->wechat->get_access_token();
        $data = [
            'tag' => ['id' => $request->all()['id']]
        ];
        $re = $this->wechat->post($url,json_encode($data));
        $result = json_decode($re,1);
        if($re){
            echo ("<script>alert('删除成功');location='/wechat/label_list';</script>");
         }else{
            echo ("<script>alert('删除失败');location='/wechat/label_list';</script>");
        }
        // dd($result);
    }
    //批量为用户打标签
    public function label_do(Request $request)
    {
        $openid_info =DB::table('wechat_openid')->whereIn('id',$request->all()['id_list'])->select(['openid'])->get()->toArray();
        // dd($openid_info['openid']);
        $openid_list=[];
        // dd($openid_list);
        foreach($openid_info as $v){
            $openid_list[] = $v->openid;
        }
        // dd($openid_list);
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token='.$this->wechat->get_access_token();
        $tag_id = $request->all()['tagid'];
        // dd($tag_id);
        $data = [
            'openid_list'=>$openid_list,
            'tagid'=>$request->all()['tagid'],
        ];
        // dd($data);
        $re = $this->wechat->post($url,json_encode($data));
        // dd($re);
        if($re){
            echo ("<script>alert('打标签成功');location='/wechat/label_list';</script>");
         }else{
            echo ("<script>alert('打标签失败');location='/wechat/label_list';</script>");
        }
    }
    //批量为用户取消标签
    public function label_unset(Request $request)
    {
        // 获取用户的tagid和openid
        $tagid = $request->all()['tag_id'];
        $openid = $request->all()['openid'];
        $url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token=".$this->wechat->get_access_token();
        if(!is_array($openid)){
            $openid_list = [$openid];
        }else{
            $openid = $openid;
        }
        // dd($openid_list);
        $data = [
            'openid_list' => $openid_list,
            'tagid' => $tagid
        ];
        $re = $this->wechat->post($url,json_encode($data));
        // dd(json_decode($re,1));
        if($re){
            echo ("<script>alert('删除标签成功');location='/wechat/label_list';</script>");
         }else{
            echo ("<script>alert('删除标签失败');location='/wechat/label_list';</script>");
        }
    }
    //获取标签下粉丝列表
    public function label_openid(Request $request)
    {
        $id = $request->all()['id'];
        $url = 'https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token='.$this->wechat->get_access_token();
        // 获取openid
        // $openid = $this->wechat->openid();
        $data = [
                "tagid"=> $id,
                "next_openid"=>''
            ];
        $re = $this->wechat->post($url,json_encode($data));
        $info = json_decode($re,1);
        return $info;
        dd($info);
    }
    //粉丝列表
    public function user_list(Request $request)
    {
        $tag_id = !empty($request->all()['tag_id'])?$request->all()['tag_id']:'';
        // dd($tag_id);
        $openid_info = DB::table('wechat_openid')->get()->toarray();
        // dd($openid_info);
        return view('wechat.userList',['openid_info'=>$openid_info,'tag_id'=>$tag_id]);
    }
    //获取用户身上的标签列表
    public function label_user_list(Request $request)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token=".$this->wechat->get_access_token();
        $openid = ['openid'=>$request->all()['openid']];
        $re = $this->wechat->post($url,json_encode($openid));
        // 用户标签列表
        $user_mark_info = json_decode($re,1);
        // dd($user_mark_info);
        $mark_info = $this->wechat->wechat_tag_list();
        // dd($mark_info);
        // dd($openid);
        $mark_arr = $mark_info['tags'];
        // dd($mark_arr);
        foreach($mark_arr as $v){
            foreach($user_mark_info['tagid_list'] as $vo){
                if($vo == $v['id']){
                    echo $v['name']."<a href='".env('APP_URL').'/wechat/label_unset'.'?tag_id='.$v['id'].'&openid='.$request->all()['openid']."'>删除</a><br/>";
                }
            }
        }
    }
    // 根据标签为用户推送消息
    public function push_label_message(Request $request){
        $data = $this->wechat->mark_user($request->all()['tag_id']);
        $tag_id = $request->all()['tag_id'];
        // dd($data);
        if($data['count'] == 0){
            // return false;
            echo ("<script>alert('粉丝为空');location='/wechat/label_list';</script>");die;
        }
        $openid = json_encode($data['data']['openid']);//转换为json形式
        return view('wechat.pushMarkMessage',['openid'=>$openid,'tag_id'=>$tag_id]);
    }
    // 执行推送消息
    public function push_label_message_do(Request $request){
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$this->wechat->get_access_token();
        $tag_id = $request->all()['tag_id'];
        // 接收推送类型
        $push_type = $request->all()['push_type'];
        // 根据推送类型进行判断
        if($push_type == 1){
            // 文本消息
            $data = [
                "filter" => ["is_to_all"=>false,"tag_id"=>$tag_id],
               "text" => ['content' => $request->all()['content']],
                "msgtype"=> "text"
            ];
        }elseif($push_type == 2){
            // 图文消息
            $data = [
                'filter' => ['is_to_all'=>false,'tag_id'=>$tag_id],
                'image' => ['media_id' => $request->all()['media_id']],
                'msgtype' => 'image'
            ];
        }

        $re = $this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        dd(json_decode($re,1));
    }
}
