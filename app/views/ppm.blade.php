@extends('layout')

@section('content')

<table class="table">
    <thead>
        <th style="width: 12%;"><a href="/settings" role="button" class="btn btn-default btn-xs">settings</a></th>
        @foreach($games as $game)
            <th class="text-center">{{$game->name}}</th>
        @endforeach
    </thead>
    <tbody>
    @foreach($data as $c => $d)
    <tr>
        <td><img src="/images/32/{{$c}}.png">&nbsp;{{$c}}<br/><a href="{{$d[1]['be']}}" target="_blank">[be]</a>&nbsp;<a href="{{$d[1]['ss']}}" target="_blank">[ss]</a>&nbsp;<a href="{{$d[1]['sc']}}" target="_blank">[sc]</a></td>
            @for($i = 1; $i < 11; $i ++)
                <td class="text-center hasTooltip @if($d[$i]['length'] >= $d[$i]['treshold']) bold-text @endif" title="<h6><img src='/images/16/{{$c}}.png'>[{{$games->get($i)['name']}}] <span class='text-primary' style='font-weight: bold;'>{{$d[$i]['odds']}}</span> ({{$d[$i]['c_odds']}})</h6>{{$d[$i]['top']}}<br/><br/>{{$d[$i]['curr']}}">{{$d[$i]['length']}}</td>
            @endfor
    </tr>
    @endforeach
    </tbody>
    <tfoot>
    <th style="width: 12%;"></th>
    @foreach($games as $game)
    <th class="text-center">{{$game->name}}</th>
    @endforeach
    </tfoot>
</table>

@stop
