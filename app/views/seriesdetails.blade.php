@extends('layout')

@section('content')
<table class="table table-bordered">
    <thead>
    <th>date</th>
    <th>game</th>
    <th>home</th>
    <th>res</th>
    <th>away</th>
    <th style="width: 55px">bsf</th>
    <th>bet</th>
    <th>odds</th>
    <th>income [profit]</th>
    <th></th>
    </thead>
    <tbody>
    @foreach($games as $game)
    <tr>
        <td>{{date('d M, H:i', strtotime($game['match']['date_time']))}}</td>
        <td>{{$game['game_type']['name']}} [{{$game['current_length']}}]</td>
        <td>{{$game['match']['home']}}</td>
        <td>
            @if ($game['match']['short_result'] != '-')
            {{$game['match']['home_goals']}}:{{$game['match']['away_goals']}}
            @else
            -
            @endif
        </td>
        <td>{{$game['match']['away']}}</td>
        <td>{{$game['bsf']}}</span></td>
        <td>{{$game['bet']}}</td>
        <td>{{$game['odds']}}</td>
        <td>{{$game['income']}} [{{$game['profit']}}]</td>
        <td>@if($game['short_result'] == '-')
            <a role="button" class="btn btn-danger btn-xs" style="width: 50px" href="/play/delete/{{$game['id']}}" style="font-size: 130%;">-</a>
            @else
            <a role="button" class="btn btn-default btn-xs" style="width: 50px" disabled href="/play/delete/{{$game['id']}}">-</a>
            @endif
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
@stop
