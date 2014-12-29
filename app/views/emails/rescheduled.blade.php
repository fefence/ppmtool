<html>
<head>

</head>
<body>
    @foreach($body as $alias => $matches)
        [{{$alias}}]<br/>
        @foreach($matches as $match)
            {{$match['match']->home}} - {{$match['match']->away}} was {{$match['old']}} and now it is {{$match['match']->date_time}}<br/>
        @endforeach
    @endforeach
</body>
</html>