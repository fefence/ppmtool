@extends('layout')

@section('content')
<a href="/list" role="button" class="btn btn-default btn-xs">list</a>
@foreach($matches as $league => $matches)
    <table class="table">
        <thead>
            <th><img src="/images/32/{{$league}}.png"></th>
            <th style="width: 70px;"></th>
            <th style="width: 60px;"></th>
            <th></th>
            <th style="width: 50px;"></th>
        </thead>
        @foreach($matches as $m)
            <tr>
                <td>{{date('H:i', strtotime($m->date_time))}}</td>
                <td style="text-align: right;">{{$m->home}}</td>
                <?php
                if($m->short_result == '-' && $m->date_time <= date('Y-m-d H:i:s', time())) {
                    $active_livescore = true;
                } else {
                    $active_livescore = false;
                }
                ?>
                @if (!$active_livescore &&  $m->short_result != '-')
                <td style="width: 50px;">
                    <span class="score scoreFinished" id="home_goals">{{$m->home_goals}}</span><span class="scoreSeparator">:</span><span id='away_goals' class="score scoreFinished">{{$m->away_goals}}</span>
                </td>
                @elseif($active_livescore)
                <td style="width: 50px;" class="livescoreResultTdActive" id="$d['match']->id}}">
                    <span class="score scoreRunning" id="home_goals">&nbsp;</span><span class="scoreSeparator" id="scoreSeparator">:</span><span id='away_goals' class="score scoreRunning">&nbsp;</span>
                </td>
                @else
                <td style="width: 50px;">
                    <span class="score scoreNotStarted" id="home_goals">-</span><span class="scoreSeparator">:</span><span id='away_goals' class="score scoreNotStarted">-</span>
                </td>
                @endif
                <td style="text-align: left;">{{$m->away}}</td>
            </tr>
        @endforeach
    </table>

@endforeach
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
