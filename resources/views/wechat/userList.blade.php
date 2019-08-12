<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>wechat-user-list</title>
</head>
<body>
<center>
    <a href="{{url('wechat/get_user_list')}}">刷新粉丝列表</a> |
    <a href="{{url('wechat/label_list')}}">公众号标签列表</a>
    <h1>粉丝列表</h1>
    <form action="{{url('wechat/label_do')}}" method="post">
        @csrf
        <input type="hidden" name="tagid" value="{{$tag_id}}">
        <table border="1">
            <tr>
                <td>选择</td>
                <td>ID</td>
                <td>openid</td>
                <td>添加时间</td>
                <td>是否关注</td>
                <td>操作</td>
            </tr>
            @foreach($openid_info as $v)
                <tr>
                    <td>
                        <input type="checkbox" name="id_list[]" value="{{$v->id}}">
                    </td>
                    <td>{{$v->id}}</td>
                    <td>{{$v->openid}}</td>
                    <td>{{date('Y-m-d H:i:s',$v->add_time)}}</td>
                    @if($v->subscribe==1)
                    <td>未关注</td>
                    @else
                    <td>已关注</td>
                    @endif
                    <td>
                        <a href="{{url('wechat/get_user_list')}}?id={{$v->id}}">详情</a> |
                        <a href="{{url('wechat/label_user_list')}}?openid={{$v->openid}}">获取标签</a>
                    </td>
                </tr>
            @endforeach
        </table>
        <input type="submit" value="提交">
    </form>


</center>
<script type="text/javascript">
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({});
    });
</script>
</body>
</html>