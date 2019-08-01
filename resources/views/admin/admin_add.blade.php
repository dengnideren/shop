<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
@extends('layout.common')
@section('body')
<form class="layui-form" action="{{url('User/do_add')}}" method="post">
@csrf
  <div class="layui-form-item">
    <label class="layui-form-label">用户名</label>
    <div class="layui-input-inline">
      <input type="text" name="name" required lay-verify="required" placeholder="请输入用户名" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-mid layui-word-aux">请输入用户名</div>
  </div>


  <div class="layui-form-item">
    <label class="layui-form-label">密码框</label>
    <div class="layui-input-inline">
      <input type="password" name="password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-mid layui-word-aux">请输入密码</div>
  </div>

  <div class="layui-form-item">
    <label class="layui-form-label">请添加管理员权限</label>
    <div class="layui-input-inline">
      <input type="text" name="state"   autocomplete="off" class="layui-input">
    </div>
  </div>


  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
      <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
  </div>
</form>

@endsection

</body>
</html>