@extends('layout')

@section('content')

<table class="table">
    <thead>
        <th style="width: 75px;"><a href="/settings" role="button" class="btn btn-default btn-xs">settings</a></th>
        @foreach($games as $game)
            <th class="text-center">{{$game->name}}</th>
        @endforeach
    </thead>
    <tbody>
    @foreach($data as $c => $d)
    <tr>
        <td><img src="/images/32/{{$c}}.png">&nbsp;{{$c}}</td>
            @for($i = 1; $i < 11; $i ++)
                <td class="text-center">{{$d[$i]['length']}}</td>
            @endfor
    </tr>
    @endforeach
    </tbody>
</table>

@stop
