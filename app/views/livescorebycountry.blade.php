@extends('layout')

@section('content')
@if($no_info)
<h5>No matches for today.</h5>
@else
<div class="container">
    <div class="row">
        <?php
        $url = '/list';
        $from = date('Y-m-d', strtotime($fromdate));
//        $to = date('Y-m-d', strtotime($todate));
        if ($from == date('Y-m-d', time())) {
            $url == '/list';
        } else {
            $url = '/list/'.$from.'/'.$from;
        }
        ?>
        <p><a href="{{$url}}" role="button" class="btn btn-default">list</a></p>
    </div>
</div>
@foreach($matches as $league => $matches)
<div class="container">
    <div class="row">
        <table class="table">
            <thead>
                <th><img src="/images/32/{{$league}}.png"></th>
                <th style="width: 70px;"></th>
                <th style="width: 60px;"></th>
                <th></th>
            </thead>
            @foreach($matches as $m)
                <tr id="{{$m->id}}">
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
                    <td style="text-align: center;">
                        <span class="score scoreFinished" id="home_goals">{{$m->home_goals}}</span><span class="scoreSeparator">:</span><span id='away_goals' class="score scoreFinished">{{$m->away_goals}}</span>
                    </td>
                    @elseif($active_livescore)
                    <td style="text-align: center;" class="livescoreResultTdActive" id="{{$m->id}}">
                        <span class="score scoreRunning" id="home_goals">&nbsp;</span><span class="scoreSeparator" id="scoreSeparator">:</span><span id='away_goals' class="score scoreRunning">&nbsp;</span>
                    </td>
                    @else
                    <td style="text-align: center;">
                        <span class="score scoreNotStarted" id="home_goals">-</span><span class="scoreSeparator">:</span><span id='away_goals' class="score scoreNotStarted">-</span>
                    </td>
                    @endif
                    <td style="text-align: left;">{{$m->away}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endforeach
@endif
<script type="text/javascript">

    var asInitVals = new Array();

    $(document).ready(function () {
        $("table tr .livescoreResultTdActive").each(function() {
            var id =$(this).closest('tr').prop('id');
            var td_span1 = $(this).find("#home_goals");
            var td_span2 = $(this).find("#away_goals");
            $.post( "/getres/" + id, function( data ) {
                td_span1.html(data[0]+"");
                td_span2.html(data[1]+"");
            });
        });
        setInterval(function() {
            $("table tr .livescoreResultTdActive").each(function() {
                var id =$(this).closest('tr').prop('id');
                var td_span1 = $(this).find("#home_goals");
                var td_span2 = $(this).find("#away_goals");
                $.post( "/getres/" + id, function( data ) {
                    td_span1.html(data[0]+"");
                    td_span2.html(data[1]+"");
                });
            })

        }, 30000);
        setInterval(function() {
            $("table tr .livescoreResultTdActive #scoreSeparator").each(function() {
                $(this).toggleClass('scoreSeparatorToggle');
            })
        }, 1000);
    });
</script>

@stop
