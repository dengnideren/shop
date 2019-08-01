<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>注册</title>
</head>
<body>
    <form action="">
        邮箱:<input type="text" name="email">
        密码:<input type="text" name="pwd">
        <div class="row">
        <div class="col-md-8">
            <input type="text" class="form-control {{$errors->has('captcha')?'parsley-error':''}}" name="captcha" placeholder="captcha">
        </div>
        <div class="col-md-4">
            <img src="{{captcha_src()}}" style="cursor: pointer" onclick="this.src='{{captcha_src()}}'+Math.random()">
        </div>
        @if($errors->has('captcha'))
            <div class="col-md-12">
                <p class="text-danger text-left"><strong>{{$errors->first('captcha')}}</strong></p>
            </div>
        @endif
</div>
    </form>
</body>
</html>