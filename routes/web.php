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

Route::get('/', function () {
    return view('welcome');
});
//登录
Route::get('/home/login','Home\IndexController@login');
Route::post('/home/dologin','Home\IndexController@dologin');
//注册
Route::get('/home/register','Home\IndexController@register');
Route::post('/home/doregister','Home\IndexController@doregister');
//商品添加文件上传
Route::get('/admin/addgoods','Admin\GoodsController@addgoods');
Route::post('/admin/doaddgoods','Admin\GoodsController@doaddgoods');
//商品列表
Route::get('/admin/index','Admin\GoodsController@index');
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
//调用中间件
Route::group(['middleware'=>['Login']],function(){
    Route::get('student/create','StudentController@create');
});


