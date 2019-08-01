<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>竞猜结果</title>
</head>
<body>
<center>
    <h1>竞猜结果</h1>
    <table>
            @foreach($guess as $v)
            <tr>
                <td>{{ $v->name }}{{ $v->title }} </td>
            </tr>
            @endforeach
        </table>
    </center>
</body>
</html>