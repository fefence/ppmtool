@extends('layout')

@section('content')
@foreach($data as $c => $games)

    <table class="table table-bordered">
        <thead>
            <th style="width: 110px;"><img src="/images/{{$c}}.png"></th>
            <th>game</th>
            <th>home</th>
            <th>res</th>
            <th>away</th>
            <th>l</th>
            <th style="width: 55px">bsf</th>
            <th>bet</th>
            <th>odds</th>
            <th>income</th>
            <th>profit</th>
            <th></th>
        </thead>
        <tbody>
        @foreach($games as $game)
        <tr>
            <td>{{date('d M, H:i', strtotime($game['match']['date_time']))}}</td>
            <td><a href="/series/{{$game['series_id']}}">{{$game['game_type']['name']}}</a></td>
            <td>{{$game['match']['home']}}</td>
            <td class="success">
                @if ($game['match']['short_result'] != '-')
                {{$game['match']['home_goals']}}:{{$game['match']['away_goals']}}
                @else
                -
                @endif
            </td>
            <td>{{$game['match']['away']}}</td>
            <td>{{$game['current_length']}}</td>
            <td><span class="editable" id="bsf_{{$game['id']}}">{{$game['bsf']}}</span></td>
            <td class="editable" id="bet_{{$game['id']}}">{{$game['bet']}}</td>
            <td class="editable" id="odds_{{$game['id']}}">{{$game['odds']}}</td>
            <td id="income_{{$game['id']}}">{{$game['income']}}</td>
            <td id="profit_{{$game['id']}}">{{$game['profit']}}</td>
            <td>@if($game['short_result'] == '-')
                <a role="button" @if ($count[$game['id']] != 0) class="btn btn-default btn-xs" @else class="btn btn-primary btn-xs" @endif style="width: 50px" href="/play/confirm/{{$game['id']}}" style="font-size: 130%;">+&nbsp({{ $count[$game['id']] }})</a>
                @elseif ($d->resultShort == 'D')
                <a role="button" class="btn btn-success btn-xs" style="width: 50px" disabled href="/play/confirm/{{$game['id']}}">+&nbsp({{ $count[$game['id']] }})</a>
                @else
                <a role="button" class="btn btn-default btn-xs" style="width: 50px" disabled href="/play/confirm/{{$game['id']}}">+&nbsp({{ $count[$game['id']] }})</a>
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endforeach
<script>
    $(document).ready(function(){
        $('table').on('click', function(){

        });
        $(".editable").editable("/play/save", {
            callback : function(value) {
                var arr = value.split('*');
//                alert(value);
                $('#bsf_'+arr[0]).text(arr[1]);
                $('#bet_'+arr[0]).text(arr[2]);
                $('#odds_'+arr[0]).text(arr[3]);
                $('#income_'+arr[0]).text(arr[4]);
                $('#profit_'+arr[0]).text(arr[5]);
            }
        });
    });
</script>
@stop
