<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<h1>Labels</h1>
<ul>
    @foreach ($labels as $label)
        <li><a href="labels/{{$label->id}}">
                {{$label->name}}</a></li>
    @endforeach
</ul>
</body>
</html>
