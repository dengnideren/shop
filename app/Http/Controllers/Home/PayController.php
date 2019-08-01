<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pay;
use DB;
class PayController extends Controller
{
    public $app_id;
    public $gate_way;
    public $notify_url;
    public $return_url;
    public $rsaPrivateKeyFilePath = '';  //路径
    public $aliPubKey = '';  //路径
    public $privateKey;
    public $publicKey ;
    public function __construct()
    {
        $this->app_id = env('ALI_APP_ID');
        $this->privateKey = env('ALI_PRIVATE_KEY');
        $this->publicKey = env('ALI_PUBLIC_KEY');
        $this->gate_way = 'https://openapi.alipaydev.com/gateway.do';
        $this->notify_url = env('APP_URL').'/notify_url';
        $this->return_url = env('APP_URL').'/return_url';
    }

    public function do_pay(){
        $oid = time().mt_rand(1000,1111);  //订单编号
        //dd($oid);
        //$this->ali_pay($oid);
        $order=[
            'out_trade_no'=>$oid,
            'total_amout'=>10,
            'subject'=>'test sybject',
        ];
        $this->ali_pay($order);
        // return Pay::alipay()->web($order);
    }
    public function notify_url()
    {
        $post_json=file_get_contents("php://input");
        \Log::Info($post_json);
        $post=json_encode($post_json);
        //业务处理
        if($post['trade_status']=='TRADE_SUCCESS'){
            //修改订单状态
            //清理购物车
        }
    }
    public function rsaSign($params) {
        return $this->sign($this->getSignContent($params));
    }
    protected function sign($data) {
    	if($this->checkEmpty($this->rsaPrivateKeyFilePath)){
    		$priKey=$this->privateKey;
			$res = "-----BEGIN RSA PRIVATE KEY-----\n" .
				wordwrap($priKey, 64, "\n", true) .
				"\n-----END RSA PRIVATE KEY-----";
    	}else{
    		$priKey = file_get_contents($this->rsaPrivateKeyFilePath);
            $res = openssl_get_privatekey($priKey);
    	}

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
            openssl_free_key($res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }
    public function getSignContent($params) {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, 'UTF-8');
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }



    /**
     * 根据订单号支付
     * [ali_pay description]
     * @param  [type] $oid [description]
     * @return [type]      [description]
     */
    public function ali_pay($order){

        //业务参数
        $bizcont = [
            'subject'           => $order['subject'],
            'out_trade_no'      => $order['out_trade_no'],
            'total_amount'      => $order['total_amout'],
            'product_code'      => 'FAST_INSTANT_TRADE_PAY',
        ];
        //公共参数
        $data = [
            'app_id'   => $this->app_id,
            'method'   => 'alipay.trade.page.pay',
            'format'   => 'JSON',
            'charset'   => 'utf-8',
            'sign_type'   => 'RSA2',
            'timestamp'   => date('Y-m-d H:i:s'),
            'version'   => '1.0',
            'notify_url'   => $this->notify_url,        //异步通知地址
            'return_url'   => $this->return_url,        // 同步通知地址
            'biz_content'   => json_encode($bizcont),
        ];
        //签名
        $sign = $this->rsaSign($data);
        $data['sign'] = $sign;
        $param_str = '?';
        foreach($data as $k=>$v){
            $param_str .= $k.'='.urlencode($v) . '&';
        }
        $url = rtrim($param_str,'&');
        $url = $this->gate_way . $url;
        //dd($url);
        header("Location:".$url);
    }
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }
    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {
        if (!empty($data)) {
            $fileType = 'UTF-8';
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }
        return $data;
    }
    /**
     * 支付宝同步通知回调
     */
    public function aliReturn()
    {
        header('Refresh:2;url=/order_list');
        echo "<h2>订单： ".$_GET['out_trade_no'] . ' 支付成功，正在跳转</h2>;location="/home/cart_do"';
    }
    /**
     * 支付宝异步通知
     */
    public function aliNotify()
    {
        $data = json_encode($_POST);
        $log_str = '>>>> '.date('Y-m-d H:i:s') . $data . "<<<<\n\n";
        //记录日志
        file_put_contents(storage_path('logs/alipay.log'),$log_str,FILE_APPEND);
        //验签
        $res = $this->verify($_POST);
        $log_str = '>>>> ' . date('Y-m-d H:i:s');
        if($res){
            //记录日志 验签失败
            $log_str .= " Sign Failed!<<<<< \n\n";
            file_put_contents(storage_path('logs/alipay.log'),$log_str,FILE_APPEND);
        }else{
            $log_str .= " Sign OK!<<<<< \n\n";
            file_put_contents(storage_path('logs/alipay.log'),$log_str,FILE_APPEND);
            //验证订单交易状态
            if($_POST['trade_status']=='TRADE_SUCCESS'){

            }
        }
        echo 'success';
    }
    //验签
    function verify($params) {
        $sign = $params['sign'];
        if($this->checkEmpty($this->aliPubKey)){
            $pubKey= $this->publicKey;
            $res = "-----BEGIN PUBLIC KEY-----\n" .
                wordwrap($pubKey, 64, "\n", true) .
                "\n-----END PUBLIC KEY-----";
        }else {
            //读取公钥文件
            $pubKey = file_get_contents($this->aliPubKey);
            //转换为openssl格式密钥
            $res = openssl_get_publickey($pubKey);
        }


        ($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');
        //调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify($this->getSignContent($params), base64_decode($sign), $res, OPENSSL_ALGO_SHA256);

        if(!$this->checkEmpty($this->aliPubKey)){
            openssl_free_key($res);
        }
        return $result;
    }
    public function return_url(Request $request)
    {
        // $data=DB::table('order')->get();
        // // $res=DB::table('')
        // return view('home/order',['data'=>$data]);
        $uid = session('id');
        // dd($uid);
        $order_info =DB::table('order')->where(['uid'=>$uid])->orderBy('add_time','desc')->paginate(5);
         // dd($order_info);
         // $order_info=get_object_vars($order_info);
        $order = $order_info->toArray()['data'];
        // dd($order);
        // 状态
        $state_list = [1=>'待支付',2=>'已支付','3'=>'已过期',4=>'用户删除'];
        // 商品总价
        $data =DB::table('cart')->where(['uid'=>$uid])->get();
        $data=get_object_vars($data);
        $total = 0;
        foreach ($data as $key => $v) {
            $total += $v['goods_price'];
        }
        // dd($total);
        // 十分钟取消了订单
        // $order=get_object_vars($order);
        foreach($order as $k=>$v){
            $order[$k]['end_time'] = date('Y/m/d H:i:s',$v['add_time'] + 10 * 60);
            $order[$k]['order_state'] = $state_list[$v['state']];
            $order[$k]['pay_money'] =  $total;
        }
        // dd($order);
        return view('home/order',['order_info'=>$order_info,'order'=>$order,'total'=>$total]);
    }
}
