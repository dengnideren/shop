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

//登录
Route::get('/login/login','Admin\loginController@login');
Route::post('/admin/dologin','Admin\loginController@dologin');
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
Route::get('/kaoshi/login','Admin\kaoshiController@login');
Route::post('dologin','Admin\kaoshiController@dologin');
Route::get('logout','Admin\kaoshiController@logout');
Route::middleware(['logindo'])->group(function(){
    Route::get('/car/index','Admin\kaoshiController@index');
    Route::get('addcar','Admin\kaoshiController@addcar');
    Route::post('doaddcar','Admin\kaoshiController@doaddcar');
    Route::get('addmenwei','Admin\kaoshiController@addmenwei');
    Route::post('doaddmenwei','Admin\kaoshiController@doaddmenwei');
    Route::get('admin','Admin\kaoshiController@admin');
    Route::get('carin','Admin\kaoshiController@carin');
    Route::post('docarin','Admin\kaoshiController@docarin');
    Route::get('carout','Admin\kaoshiController@carout');
    Route::post('docarout','Admin\kaoshiController@docarout');
    Route::get('detail','Admin\kaoshiController@detail');
    Route::get('info','Admin\kaoshiController@info');
});


Route::get('/guess','Admin\GuessController@guess');
Route::post('/guessdo','Admin\GuessController@guessdo');
Route::get('/guesslist','Admin\GuessController@guesslist');
Route::get('/guessadd','Admin\GuessController@guessadd');
Route::get('/guessadddo','Admin\GuessController@guessadddo');
Route::get('/guessend','Admin\GuessController@guessend');
Route::get('/guessenddo','Admin\GuessController@guessenddo');
Route::get('/guessbai','Admin\GuessController@guessbai');

Route::get('/strator/question','Home\StratorController@question');
Route::post('/strator/questionadd','Home\StratorController@questionadd');
Route::post('/duoxuan','Home\StratorController@duoxuan');
Route::post('/panduan','Home\StratorController@panduan');
Route::get('/strator','Home\StratorController@strator');
Route::post('/strator/stratordo','Home\StratorController@stratordo');
Route::get('/strator/list','Home\StratorController@stratorlist');
Route::get('/fly','Home\StratorController@fly');
//添加
Route::get('/admin/create','Admin\CheckController@create');
//执行添加
Route::post('/admin/save','Admin\CheckController@save');
//列表
Route::get('/index','Home\CheckoutController@index');
Route::get('home/question','Home\CheckedController@question');
Route::get('/', function () {
    return view('welcome');
});
//支付宝支付
Route::get('/pay','PayController@do_pay');
//登录
Route::get('/home/login','Home\LoginController@login');
Route::post('/home/dologin','Home\LoginController@dologin');
//注册
Route::get('/home/register','Home\LoginController@register');
Route::post('/home/doregister','Home\LoginController@doregister');
//商品添加文件上传
Route::get('/goods/addgoods','Admin\GoodsController@addgoods');
Route::post('/goods/doaddgoods','Admin\GoodsController@doaddgoods');
//商品列表
Route::get('/goods/index','Admin\GoodsController@index');
//商品删除
Route::get('/goods/delete','Admin\GoodsController@delete');
Route::post('/goods/update','Admin\GoodsController@update');
//调用中间件
Route::group(['middleware'=>['update']],function(){
    Route::get('/goods/edit','Admin\GoodsController@edit');
});
//前台首页
Route::get('/home/index','Home\IndexController@index');
Route::get('/home/add','Home\IndexController@add');
//前台详情页
Route::get('/home/single','Home\IndexController@single');
//购物车
Route::get('/home/cart_do','Home\IndexController@cart_do');
Route::get('/home/cart','Home\IndexController@cart');
//确认订单
Route::get('/home/listdo','Home\IndexController@listdo');
//添加订单
Route::get('/home/listadd','Home\IndexController@listadd');
//订单列表
Route::get('return_url','PayController@return_url');
//学生列表
Route::get('/student/index','StudentController@index');
//添加视图
Route::get('/student/create','StudentController@create');
//添加处理
Route::post('/student/save','StudentController@save');
//删除
Route::get('/student/delete','StudentController@delete');
//修改视图
Route::get('/student/edit','StudentController@edit');
//处理修改
Route::post('/student/update','StudentController@update');


