<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<h1>Notes</h1>
<ul>
    @foreach ($notes as $note)
        <li><a href="notes/{{$note->id}}">
                {{$note->title}}</a></li>
    @endforeach
</ul>
</body>
</html>
