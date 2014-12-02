@extends('layout')

@section('content')

<table class="table table-bordered">
    <thead>
        <th>country/game</th>
        @foreach($games as $game)
            <th>{{$game->name}}</th>
        @endforeach
    </thead>
    <tbody>
    @foreach($data as $c => $d)
    <tr>
        <td>{{$c}}</td>
        @foreach($d as $series)
        <td>{{$series['length']}}</td>
        @endforeach
    </tr>
    @endforeach
    </tbody>
</table>

@stop
