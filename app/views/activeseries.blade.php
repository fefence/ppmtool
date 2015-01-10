@extends('layout')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>type</th>
                <th>bsf</th>
            </tr>
        </thead>
        <tbody>
        @foreach($games as $game)
        <tr>
            <td><img src="/images/32/{{$game->country_alias}}.png"></td>
            <td><a href="/series/{{$game->series_id}}">{{$game->name}}&nbsp;({{$count[$game->id]}})</a></td>
            <td>{{$game->sum}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
@stop
