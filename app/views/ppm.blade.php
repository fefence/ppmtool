@extends('layout')

@section('content')

<table class="table">
    <thead>
        <th style="width: 75px;"></th>
        @foreach($games as $game)
            <th class="text-center">{{$game->name}}</th>
        @endforeach
    </thead>
    <tbody>
    @foreach($data as $c => $d)
    <tr>
        <td><img src="/images/32/{{$c}}.png">&nbsp;{{$c}}</td>
        @foreach($d as $series)
            @for($i = 1; $i < 11; $i ++)
                <td class="text-center">{{$series[$i]['length']}}</td>
            @endfor
        @endforeach
    </tr>
    @endforeach
    </tbody>
</table>

@stop
