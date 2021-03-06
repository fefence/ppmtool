@extends('layout')

@section('content')

<table class="table">
    <thead>
    <th style="width: 12%;"><a href="/series" role="button" class="btn btn-default btn-xs active">series</a></th>
    @foreach($game_types as $game_type)
        <th class="text-center">{{$game_type->name}}</th>
    @endforeach
    </thead>
    <tbody>
    @foreach($leagues as $league)
        <tr>
            <td><a href="{{$league->be}}" target="_blank"><img src="/images/32/{{$league->country_alias}}.png"></a>&nbsp;{{$league->country_alias}}<br><a href="{{$league->ss}}" target="_blank">[ss]</a>&nbsp;<a href="{{$league->sc}}" target="_blank">[sc]</a></a></td>
        @foreach($game_types as $game_type)
            <td class="text-center">
                @if(array_key_exists($league->id, $data) && in_array($game_type->id, $data[$league->id]))
                <a href="/settings/disable/{{$league->id}}/{{$game_type->id}}" role="button" class="btn btn-danger btn-xs">{{$game_type->name}}</a>
                @else
                <a href="/settings/enable/{{$league->id}}/{{$game_type->id}}" role="button" class="btn btn-success btn-xs">{{$game_type->name}}</a>
                @endif
            </td>
        @endforeach
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <th style="width: 12%;"></th>
    @foreach($game_types as $game_type)
    <th class="text-center">{{$game_type->name}}</th>
    @endforeach
    </tfoot>
</table>
@stop
