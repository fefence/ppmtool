@extends('layout')

@section('content')

<table class="table">
    <thead>
        <th style="width: 7%;"><a href="/settings" role="button" class="btn btn-default btn-xs">settings</a></th>
        @foreach($games as $game)
            <th class="text-center">{{$game->name}}</th>
        @endforeach
    </thead>
    <tbody>
    @foreach($data as $c => $d)
    <tr>
        <td><img src="/images/32/{{$c}}.png">&nbsp;{{$c}}<br/><a href="http://www.betexplorer.com/soccer/{{$d[1]['country']}}/{{$d[1]['name']}}">[be]</a>&nbsp;<a href="http://www.sportstats.com/soccer/{{$d[1]['country']}}/{{$d[1]['name']}}">[ss]</a></td>
            @for($i = 1; $i < 11; $i ++)
                <td class="text-center">{{$d[$i]['length']}}</td>
            @endfor
    </tr>
    @endforeach
    </tbody>
</table>

@stop
