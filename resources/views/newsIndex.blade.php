<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>新闻列表</title>
</head>
<body>
    <center>
        <table border="1">
            <tr>
                <td>新闻id</td>
                <td>新闻标题</td>
                <td>新闻图片</td>
                <td>新闻作者</td>
                <td>添加时间</td>
                <td>操作</td>
            </tr>
            @foreach($news as $v)
            <tr>
                <td>{{ $v->nid }}</td>
                <td>{{ $v->title }}</td>
                <td><img src="{{$v->pic}}" alt=""></td>
                <td>{{ $v->name }}</td>
                <td>{{ date('Y-m-d H:i:s',$v->add_time) }}</td>
                <td>
                    <a href="{{url('news/delete')}}?id={{$v->nid}}">删除</a>
                    <a href="{{url('news/list')}}?id={{$v->nid}}">前往详情页</a>
                </td>
            </tr>
            @endforeach
        </table>
        {{ $news->links() }}
    </center>
</body>
</html>