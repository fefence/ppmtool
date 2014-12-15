@extends('layout')

@section('content')
<table class="table">
    <thead>
        <th style="width: 70px;"><a href="#" role="button" class="btn btn-default btn-xs">country</a></th>
        <th style="width: 60px;"></th>
        <th></th>
        <th style="width: 50px;"></th>
        <th></th>
    </thead>
    <tbody>
    @foreach($matches as $d)
    <tr id="{{$d['match']->id}}">
        <td style="text-align: center;"><img src="/images/32/{{$d['league']->country_alias}}.png"></td>
        <td>{{date('H:i', strtotime($d['match']->date_time))}}</td>
        <td style="text-align: right;">{{$d['match']->home}}</td>
        <?php
        if($d['match']->short_result == '-' && $d['match']->date_time <= date('Y-m-d H:i:s', time())) {
            $active_livescore = true;
        } else {
            $active_livescore = false;
        }
        ?>
        @if (!$active_livescore &&  $d['match']->short_result != '-')
        <td>
            <span class="score scoreFinished" id="home_goals">{{$d['match']->home_goals}}</span><span class="scoreSeparator">:</span><span id='away_goals' class="score scoreFinished">{{$d['match']->away_goals}}</span>
        </td>
        @elseif($active_livescore)
        <td class="livescoreResultTdActive" id="$d['match']->id}}">
            <span class="score scoreRunning" id="home_goals">&nbsp;</span><span class="scoreSeparator" id="scoreSeparator">:</span><span id='away_goals' class="score scoreRunning">&nbsp;</span>
        </td>
        @else
        <td>
            <span class="score scoreNotStarted" id="home_goals">-</span><span class="scoreSeparator">:</span><span id='away_goals' class="score scoreNotStarted">-</span>
        </td>
        @endif
        <td style="text-align: left;">{{$d['match']->away}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
<script type="text/javascript">

    var asInitVals = new Array();

    $(document).ready(function () {
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