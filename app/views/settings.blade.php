@extends('layout')

@section('content')

<table class="table table-bordered">
    <thead>
    <th>country/game</th>
    @foreach($game_types as $game_type)
        <th>{{$game_type->name}}</th>
    @endforeach
    </thead>
    <tbody>
    @foreach($leagues as $league)
        <tr>
            <td><img src="/images/{{strtoupper($league->country)}}.png"> {{$league->country}}</td>
        @foreach($game_types as $game_type)
            <td>
                @if(array_key_exists($league->id, $data) && $data[$league->id] == $game_type->id)
                <a href="/settings/disable/{{$league->id}}/{{$game_type->id}}" role="button" class="btn btn-danger btn-xs">disable</a>
                @else
                <a href="/settings/enable/{{$league->id}}/{{$game_type->id}}" role="button" class="btn btn-success btn-xs">enable</a>
                @endif
            </td>
        @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
@stop
