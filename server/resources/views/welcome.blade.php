<body>
<ul>
    @foreach ($notes as $note)
        <li>{{$note->title}} {{$note->description}}</li>
    @endforeach
</ul>
</body>
