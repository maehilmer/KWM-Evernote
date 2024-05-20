<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<h1>Lists</h1>
<ul>
    @foreach ($listoverview as $listview)
        <li><a href="listoverview/{{$listview->id}}">
                {{$listview->title}}</a></li>
    @endforeach
</ul>
</body>
</html>
