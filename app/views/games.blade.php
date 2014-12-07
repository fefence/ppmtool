@extends('layout')

@section('content')
@foreach($data as $c => $games)

    <table class="table">
        <thead>
            <tr>
                <th class="text-center" style="width: 65px;"><img src="/images/32/{{$c}}.png"></th>
                <th class="text-center" style="width: 10%;">game</th>
                <th style="text-align: right;">home</th>
                <th style="width: 5%; text-align: center">r</th>
                <th>away</th>
                <th class="text-center" style="width: 10%;">bsf</th>
                <th class="text-center" style="width: 10%;">bet</th>
                <th class="text-center" style="width: 10%;"><a href="/getodds/{{$c}}">odds</a></th>
                <th class="text-center" style="width: 10%;">income</th>
                <th class="text-center" style="width: 10%;"></th>
            </tr>
        </thead>
        <tbody>
        @foreach($games as $game)
        <tr id="{{$game['match']['id']}}">
            <td class="text-center">{{date('d M', strtotime($game['match']['date_time']))}}<br>{{date('H:i', strtotime($game['match']['date_time']))}}</td>
            <td class="text-center"><a href="/series/{{$game['series_id']}}">{{$game['game_type']['name']}}&nbsp;[{{$game['current_length']}}]</a></td>
            <td style="text-align: right;">{{$game['match']['home']}}</td>
            <?php
            if($game['match']['short_result'] == '-' && $game['match']['date_time'] <= date('Y-m-d H:i:s', time())) {
                $active_livescore = true;
            } else {
                $active_livescore = false;
            }
            ?>
            <td @if($active_livescore) class="livescoreResultTdActive" @else class="livescoreResultTdInactive" @endif>
            <div>
                <span @if($active_livescore) class="livescoreResultText" @endif>
                @if ($game['match']['short_result'] != '-')
                {{$game['match']['home_goals']}} : {{$game['match']['away_goals']}}
                @else
                -
                @endif
                </span>
            </div>
            </td>
            <td>{{$game['match']['away']}}</td>
            <td class="editable text-center" id="bsf_{{$game['id']}}">{{$game['bsf']}}</td>
            <td class="warning editable text-center" id="bet_{{$game['id']}}">{{$game['bet']}}</td>
            <td class="editable text-center" id="odds_{{$game['id']}}">{{$game['odds']}}</td>
            <td class="text-center"><span id="income_{{$game['id']}}">{{$game['income']}}</span><br>[<span id="profit_{{$game['id']}}">{{$game['profit']}}</span>]</td>
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
    var asInitVals = new Array();

    $(document).ready(function(){
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
        $("table tr .livescoreResultTdActive div .livescoreResultText").each(function() {
            var id =$(this).closest('tr').prop('id');
            var td = $(this);
            $.post( "/getres/" + id, function( data ) {
                td.html(data);
            });
        });
        setInterval(function() {
            $("table tr .livescoreResultTdActive div .livescoreResultText").each(function() {
                var id =$(this).closest('tr').prop('id');
                var td = $(this);
                $.post( "/getres/" + id, function( data ) {
                    td.html(data);
                });
            })

        }, 30000);
        setInterval(function() {
            $("table tr .livescoreResultTdActive div span span").each(function() {
                $(this).toggleClass('livescoreIndicator');
            })
        }, 1000);
    });
</script>
@stop
