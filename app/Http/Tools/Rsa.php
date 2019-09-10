<?php
namespace  App\Http\Tools;
//phpinfo();die;
class Rsa{
    private static $_privkey = '';
    private static $_pubkey = '';
    private static $_isbase64 = false;
    /**
     * 初始化key值
     * @param  string  $privkey  私钥
     * @param  string  $pubkey   公钥
     * @param  boolean $isbase64 是否base64编码
     * @return null
     */
    public  function init($privkey, $pubkey, $isbase64=false){
        self::$_privkey = $privkey;
        self::$_pubkey = $pubkey;
        self::$_isbase64 = $isbase64;
    }
    /**
     * 私钥加密
     * @param  string $data 原文
     * @return string       密文
     */
    public  function priv_encode($data){
        $outval = '';

        $res = openssl_pkey_get_private(self::$_privkey);

        openssl_private_encrypt($data, $outval, $res);
        if(self::$_isbase64){
            $outval = base64_encode($outval);
        }
        return $outval;
    }
    /**
     * 公钥解密
     * @param  string $data 密文
     * @return string       原文
     */
    public  function pub_decode($data){
        $outval = '';
        if(self::$_isbase64){
            $data = base64_decode($data);
        }
        $res = openssl_pkey_get_public(self::$_pubkey);
        openssl_public_decrypt($data, $outval, $res);
        return $outval;
    }
    /**
     * 公钥加密
     * @param  string $data 原文
     * @return string       密文
     */
    public  function pub_encode($data){
        $outval = '';
        $res = openssl_pkey_get_public(self::$_pubkey);
        openssl_public_encrypt($data, $outval, $res);
        if(self::$_isbase64){
            $outval = base64_encode($outval);
        }
        return $outval;
    }
    /**
     * 私钥解密
     * @param  string $data 密文
     * @return string       原文
     */
    public  function priv_decode($data){
        $outval = '';
        if(self::$_isbase64){
            $data = base64_decode($data);
        }
        $res = openssl_pkey_get_private(self::$_privkey);
        openssl_private_decrypt($data, $outval, $res);
        return $outval;
    }
    /**
     * 创建一组公钥私钥
     * @return array 公钥私钥数组
     */
    public function new_rsa_key(){
        $res = openssl_pkey_new();
        openssl_pkey_export($res, $privkey);
        $d= openssl_pkey_get_details($res);
        $pubkey = $d['key'];
        return array(
            'privkey' => $privkey,
            'pubkey'  => $pubkey
        );
    }
}
 
 

 
// //举个粒子
// $Rsa = new Rsa();
// // $keys = $Rsa->new_rsa_key(); //生成完key之后应该记录下key值，这里省略
// // p($keys);
// // 私钥
// $privkey = "-----BEGIN RSA PRIVATE KEY-----
// MIICXQIBAAKBgQDLKeBX7bk5l3fF8+TDLtn2K9pFsdGu/IZLXvYCTMti+EEccSnK
// uo+/GcUxvfE0M5d52/1huKv6o1O9eBTfN4Y+eChjO/RiwNQEfpJxZoDMXwP++Ab5
// aWnyH8+a3ODcA50KJwApzD1W5f/XKIHFhgssehDcgyk3Ft+ic9sWeBNDdQIDAQAB
// AoGAMl5XeHU/jr+2uiUVei6LazTEELNdQPzqbpVEeJ2BbzANNHf53IEUUlSZRxPI
// USDZVSTtVOTr/l+vyzGSOyUQ6sqsBtc3P7rgppzn4mYmEktVB9CvhDGnJepmT6KW
// B8Ub2Tda06zz1gyJFwAv5W3i3brPHdeoX+sDpSXIMcwRvQECQQDxWm3S6KXAMiTl
// +tUVEW89dfDbVYsK8c3A8cbJQPCB/DAmejV7pO+KOtvsOsbLO5ODrju+6s0/PVeU
// /6RULTKNAkEA134lwtIm2Qw7pfoQT8n77WHjq6PeBxELAslANdHsVtd0vIVGwKh9
// iWlqg6ML35yeKr6BZn5TWkmt66E4F32OiQJAP8FDgZMzNbIJTTcVUptoZzPgjA/s
// ytdVIsK7YC5nWe7kSUW/GwpWvI0Pyzc7jDQQo5hC8AvWa/4oRJPDNPk7gQJBAMnY
// 3G/3PvIxljGq4NqL/NFmzsX4UUFXQnQSpHWFM5ZIbI0lXZeaezRaLK4u7DFBV5n8
// JeMmUtcQirtaoNrH/dkCQQC/gJ2NsKNoIzNanJNfNNeOtTCgVftRJe+3y3i9BBRm
// QTzCl5yBrvwNuDLTOphBxQu72GseYP0Hre9j4qa56yod
// -----END RSA PRIVATE KEY-----";//$keys['privkey'];
// //公钥
// $pubkey  = "-----BEGIN PUBLIC KEY-----
// MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDLKeBX7bk5l3fF8+TDLtn2K9pF
// sdGu/IZLXvYCTMti+EEccSnKuo+/GcUxvfE0M5d52/1huKv6o1O9eBTfN4Y+eChj
// O/RiwNQEfpJxZoDMXwP++Ab5aWnyH8+a3ODcA50KJwApzD1W5f/XKIHFhgssehDc
// gyk3Ft+ic9sWeBNDdQIDAQAB
// -----END PUBLIC KEY-----";//$keys['pubkey'];
// //echo $privkey;die;
// //初始化rsaobject
// $Rsa->init($privkey, $pubkey,TRUE);
 
// //原文
// $data = '你妈妈让你回家吃饭了';
 
// //私钥加密示例
// $encode = $Rsa->priv_encode($data);
// p($encode);
// $ret = $Rsa->pub_decode($encode);
// p($ret);
 
// //公钥加密示例
// $encode = $Rsa->pub_encode($data);
 
// p($encode);
// $ret = $Rsa->priv_decode($encode);
// p($ret);
 
 
 
// function p($str){
//     echo '<pre>';
//     print_r($str);
//     echo '</pre>';
// }
