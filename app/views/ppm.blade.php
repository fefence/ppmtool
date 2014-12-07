@extends('layout')

@section('content')

<table class="table">
    <thead>
        <th></th>
        @foreach($games as $game)
            <th class="text-center">{{$game->name}}</th>
        @endforeach
    </thead>
    <tbody>
    @foreach($data as $c => $d)
    <tr>
        <td><img src="/images/{{$c}}.png"></td>
        @foreach($d as $series)
        <td class="text-center">{{$series['length']}}</td>
        @endforeach
    </tr>
    @endforeach
    </tbody>
</table>

@stop
