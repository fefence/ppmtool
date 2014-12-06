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
            <th style="width: 80px">bsf</th>
            <th style="width: 80px;">bet</th>
            <th style="width: 60px;">odds</th>
            <th style="width: 120px;">income</th>
            <th></th>
        </thead>
        <tbody>
        @foreach($games as $game)
        <tr>
            <td>{{date('d M, H:i', strtotime($game['match']['date_time']))}}</td>
            <td><a href="/series/{{$game['series_id']}}">{{$game['game_type']['name']}} [{{$game['current_length']}}]</a></td>
            <td>{{$game['match']['home']}}</td>
            <td class="success">
                @if ($game['match']['short_result'] != '-')
                {{$game['match']['home_goals']}}:{{$game['match']['away_goals']}}
                @else
                -
                @endif
            </td>
            <td>{{$game['match']['away']}}</td>
            <td class="editable" id="bsf_{{$game['id']}}">{{$game['bsf']}}</td>
            <td class="editable" id="bet_{{$game['id']}}">{{$game['bet']}}</td>
            <td class="editable" id="odds_{{$game['id']}}">{{$game['odds']}}</td>
            <td><span id="income_{{$game['id']}}">{{$game['income']}}</span> <span id="profit_{{$game['id']}}">[{{$game['profit']}}]</span></td>
            <td>@if($game['short_result'] == '-')
                <a role="button" @if ($count[$game['id']] != 0) class="btn btn-default btn-xs" @else class="btn btn-primary btn-xs" @endif style="width: 100%" href="/play/confirm/{{$game['id']}}" style="font-size: 130%;">+&nbsp({{ $count[$game['id']] }})</a>
                @else
                <a role="button" class="btn btn-default btn-xs" style="width: 100%" disabled href="/play/confirm/{{$game['id']}}">+&nbsp({{ $count[$game['id']] }})</a>
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
            height : '20',
            width : '100%',
            select : 'true',
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
