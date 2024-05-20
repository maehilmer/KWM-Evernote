<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<h1>To Dos</h1>
<ul>
    @foreach ($todos as $todo)
        <li><a href="todos/{{$todo->id}}">
                {{$todo->title}}</a></li>
    @endforeach
</ul>
</body>
</html>
