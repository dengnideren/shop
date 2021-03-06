<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::any('add',function(){
    return view('member.add');
});
Route::any('mem/index',function(){
    return view('member.index');
});
Route::any('mem/update',function(){
    return view('member.update');
});
Route::any('goods/add',function(){
    return view('goods.add');
});
Route::any('goods/index',function(){
    return view('goods.index');
});
//定义restful风格路由
Route::resource('/app/member','App\MemberController');
Route::resource('/app/goods','App\GoodsController');
//调用接口
Route::get('wechat/ok','Home\WechatController@ok');
Route::any('ase','App\CeController@ase');
Route::any('openssl','App\MemberController@opensslDecrypt');
//自定义接口
Route::get('wechat/nice','Home\WechatController@nice');
Route::get('ceshi/login','Home\CeshiController@login');
Route::post('ceshi/dologin','Home\CeshiController@dologin');
Route::get('ceshi/add','Home\CeshiController@add');
Route::post('ceshi/doadd','Home\CeshiController@doadd');
Route::get('ceshi/index','Home\CeshiController@index');
Route::get('ceshi/delete','Home\CeshiController@delete');
Route::post('ceshi/update','Home\CeshiController@update');
Route::get('ceshi/edit','Home\CeshiController@edit');
Route::get('ceshi/sign','Home\CeshiController@sign');

//油价
Route::get('/wechat/oil','Home\WechatController@oil');
Route::get('/love/send','Home\LoveController@send');//表白添加视图
Route::post('/love/send_do','Home\LoveController@send_do');//表白添加视图
// 留言授权登录
Route::get('/message/login','Home\MessageController@login');  //微信授权登录
Route::get('/message/code','Home\MessageController@code');  //微信授权登录
Route::get('/message/send_message','Home\MessageController@send_message');  //微信留言
Route::post('/message/send_message_do','Home\MessageController@send_message_do');  //微信留言
//微信菜单
Route::get('/menu/menu_list','Home\MenuController@menu_list');
Route::get('/menu/del_menu','Home\MenuController@del_menu');  //完全删除菜单
Route::post('/menu/do_add_menu','Home\MenuController@do_add_menu');  //增加菜单
Route::get('/menu/display_menu','Home\MenuController@display_menu');  //菜单查询接口
Route::get('/menu/reload_menu','Home\MenuController@reload_menu');  //刷新菜单接口
//生成带参数的二维码
Route::get('/agent/user_list','Home\AgentController@user_list');
Route::get('/agent/create_qrcode','Home\AgentController@create_qrcode');
Route::post('/agent/signature','Home\AgentController@signature');  //分享签名
//用户推广用户列表
Route::get('/agent/agent_list','Home\AgentController@agent_list');
//用户列表
Route::any('/wechat/event','Home\WechatController@event');
Route::get('/wechat/get_user_list','Home\WechatController@get_user_list');
Route::get('/wechat/get_user_info','Home\WechatController@get_user_info');
Route::get('/wechat/get_access_token','Home\WechatController@get_access_token');
//用户详情
Route::get('/wechat/wechatadd','Home\WechatController@wechatadd');
Route::get('/wechat/code','Home\WechatController@code');
Route::get('/wechat/login','Home\WechatController@login');
Route::get('/wechat/register','Home\WechatController@register');
//模板推送
Route::get('/wechat/push_template','Home\WechatController@push_template');
//模板列表
Route::get('/wechat/template_list','Home\WechatController@template_list');
//模板删除
Route::get('/wechat/del_template','Home\WechatController@del_template');
//上传素材
Route::get('/wechat/upload_source','Home\WechatController@upload_source');
Route::post('wechat/do_upload','Home\WechatController@do_upload');
Route::get('/wechat/get_source','Home\WechatController@get_source');
Route::get('/wechat/get_video_source','Home\WechatController@get_video_source');
//删除素材
Route::get('/wechat/der','Home\WechatController@der');
//创建标签
Route::get('/wechat/label_add','Home\WechatController@label_add');
Route::get('/wechat/label_doadd','Home\WechatController@label_doadd');
//获取已创建标签
Route::get('/wechat/label_list','Home\WechatController@label_list');
//编辑标签
Route::get('/wechat/label_update','Home\WechatController@label_update');
Route::get('/wechat/label_edit','Home\WechatController@label_edit');
//删除标签
Route::get('/wechat/label_del','Home\WechatController@label_del');
//批量为用户打标签
Route::post('/wechat/label_do','Home\WechatController@label_do');
//获取标签下粉丝列表
Route::get('/wechat/label_openid','Home\WechatController@label_openid');
Route::get('/wechat/user_list','Home\WechatController@user_list');
//批量为用户取消标签
Route::get('/wechat/label_unset','Home\WechatController@label_unset');
//获取用户身上的标签列表
Route::get('/wechat/label_user_list','Home\WechatController@label_user_list');
//根据标签为用户推送消息
Route::get('/wechat/push_label_message','Home\WechatController@push_label_message');
//执行推送消息
Route::post('/wechat/push_label_message_do','Home\WechatController@push_label_message_do');


//登录
Route::get('/login/login','Admin\LoginController@login');
Route::post('/admin/dologin','Admin\LoginController@dologin');
//新闻添加
Route::get('/news/create','Admin\NewsController@create');
Route::post('/news/save','Admin\NewsController@save');
//新闻列表
Route::get('/news/index','Admin\NewsController@index');
//新闻删除
Route::get('/news/delete','Admin\NewsController@delete');
//新闻详情页
Route::get('/news/list','Admin\NewsController@list');
Route::get('/news_info','Admin\NewsController@info');
//注册
Route::get('/register/reg','Admin\RegisterController@reg');
//车库
Route::get('/kaoshi/login','Admin\KaoshiController@login');
Route::post('dologin','Admin\KaoshiController@dologin');
Route::get('logout','Admin\KaoshiController@logout');
Route::middleware(['logindo'])->group(function(){
    Route::get('/car/index','Admin\KaoshiController@index');
    Route::get('addcar','Admin\KaoshiController@addcar');
    Route::post('doaddcar','Admin\KaoshiController@doaddcar');
    Route::get('addmenwei','Admin\KaoshiController@addmenwei');
    Route::post('doaddmenwei','Admin\KaoshiController@doaddmenwei');
    Route::get('admin','Admin\KaoshiController@admin');
    Route::get('carin','Admin\KaoshiController@carin');
    Route::post('docarin','Admin\KaoshiController@docarin');
    Route::get('carout','Admin\KaoshiController@carout');
    Route::post('docarout','Admin\KaoshiController@docarout');
    Route::get('detail','Admin\KaoshiController@detail');
    Route::get('info','Admin\KaoshiController@info');
});


Route::get('/guess','Admin\GuessController@guess');
Route::post('/guessdo','Admin\GuessController@guessdo');
Route::get('/guesslist','Admin\GuessController@guesslist');
Route::get('/guessadd','Admin\GuessController@guessadd');
Route::get('/guessadddo','Admin\GuessController@guessadddo');
Route::get('/guessend','Admin\GuessController@guessend');
Route::get('/guessenddo','Admin\GuessController@guessenddo');
Route::get('/guessbai','Admin\GuessController@guessbai');

// Route::get('/strator/question','Home\StratorController@question');
// Route::post('/strator/questionadd','Home\StratorController@questionadd');
// Route::post('/duoxuan','Home\StratorController@duoxuan');
// Route::post('/panduan','Home\StratorController@panduan');
// Route::get('/strator','Home\StratorController@strator');
// Route::post('/strator/stratordo','Home\StratorController@stratordo');
// Route::get('/strator/list','Home\StratorController@stratorlist');
// Route::get('/fly','Home\StratorController@fly');
// //添加
// Route::get('/admin/create','Admin\CheckController@create');
// //执行添加
// Route::post('/admin/save','Admin\CheckController@save');
// //列表
// Route::get('/index','Home\CheckoutController@index');
// Route::get('home/question','Home\CheckedController@question');
// Route::get('/', function () {
//     return view('welcome');
// });
// //支付宝支付
// Route::get('/pay','PayController@do_pay');
// //登录
// Route::get('/home/login','Home\LoginController@login');
// Route::post('/home/dologin','Home\LoginController@dologin');
// //注册
// Route::get('/home/register','Home\LoginController@register');
// Route::post('/home/doregister','Home\LoginController@doregister');
// //商品添加文件上传
// Route::get('/goods/addgoods','Admin\GoodsController@addgoods');
// Route::post('/goods/doaddgoods','Admin\GoodsController@doaddgoods');
// //商品列表
// Route::get('/goods/index','Admin\GoodsController@index');
// //商品删除
// Route::get('/goods/delete','Admin\GoodsController@delete');
// Route::post('/goods/update','Admin\GoodsController@update');
// //调用中间件
// Route::group(['middleware'=>['update']],function(){
//     Route::get('/goods/edit','Admin\GoodsController@edit');
// });
// //前台首页
// Route::get('/home/index','Home\IndexController@index');
// Route::get('/home/add','Home\IndexController@add');
// //前台详情页
// Route::get('/home/single','Home\IndexController@single');
// //购物车
// Route::get('/home/cart_do','Home\IndexController@cart_do');
// Route::get('/home/cart','Home\IndexController@cart');
// //确认订单
// Route::get('/home/listdo','Home\IndexController@listdo');
// //添加订单
// Route::get('/home/listadd','Home\IndexController@listadd');
// //订单列表
// Route::get('return_url','PayController@return_url');
// //学生列表
// Route::get('/student/index','StudentController@index');
// //添加视图
// Route::get('/student/create','StudentController@create');
// //添加处理
// Route::post('/student/save','StudentController@save');
// //删除
// Route::get('/student/delete','StudentController@delete');
// //修改视图
// Route::get('/student/edit','StudentController@edit');
// //处理修改
// Route::post('/student/update','StudentController@update');


