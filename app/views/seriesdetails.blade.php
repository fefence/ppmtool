@extends('layout')

@section('content')
<table class="table" border="1">
    <tr>
        <td style="width: 48px; text-align: center;"><img src="/images/32/{{$data['league']->country_alias}}.png"></td>
        <td style="width: 48px; text-align: center;">{{$data['game_type']->name}}</td>
        <td style="width: 90px; text-align: center;"><span class="text-danger" style="font-weight: bold;">{{$data['avg_odds']}}</span> ({{$data['avg_count']}})</td>
        <td style="width: 170px; text-align: center;">
            @for($i = 0; $i < 4; $i ++)
                {{$data['longest'][$i]}},&nbsp;
            @endfor
            {{$data['longest'][4]}}
        </td>
        <td>
            @foreach($data['stats'] as $s)
            {{ $s->length}}
            @include('partials.square', array('match' => $s))
            @endforeach
        </td>

    </tr>
    <tr>
        <?php
        $i = 1;
        ?>
        <td colspan="5">
            @foreach($data['all'] as $l)
            {{$l}},&nbsp;
            @if($i % 5 == 0)
            &nbsp;
            &nbsp;
            @endif
            <?php
            $i++;
            ?>
            @endforeach
        </td>
    </tr>
</table>

@if($no_info)
<h5>No games confirmed for current series.</h5>
@else
<table class="table">
    <thead>
    <th>date</th>
    <th>type</th>
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
        <td class="bg-warning">{{$game['bet']}}</td>
        <td>{{$game['odds']}}</td>
        <td>{{$game['income']}} [{{$game['profit']}}]</td>
        <td>@if($game['short_result'] == '-')
            <a role="button" class="btn btn-danger btn-xs" style="width: 50px" href="/play/delete/{{$game['id']}}"
               style="font-size: 130%;">-</a>
            @else
            <a role="button" class="btn btn-default btn-xs" style="width: 50px" disabled
               href="/play/delete/{{$game['id']}}">-</a>
            @endif
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
@endif
@stop
