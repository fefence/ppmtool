@extends('layout')

@section('content')
@if($no_info)
<h5>No matches for today.</h5>
@else
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

@foreach($matches as $league => $matches)
<?php
$first = true;
?>
        <table class="table">
<!--            <thead>-->
<!--                <th style="width: 50px;"><img src="/images/32/{{$league}}.png"></th>-->
<!--                <th></th>-->
<!--                <th style="width: 60px;"></th>-->
<!--                <th></th>-->
<!--                <th></th>-->
<!--            </thead>-->

            @foreach($matches as $m)
                <tr id="{{$m->id}}">
                    @if (!isset($first) || $first)
                        <th style="width: 50px; border-right: 1px solid #dddddd;" rowspan="{{count($matches)}}"><img src="/images/32/{{$league}}.png"></th>
                    @endif
                    <?php
                    $first = false;
                    ?>
                    <td>{{date('H:i', strtotime($m->date_time))}}</td>
                    <td style="text-align: right;" class="home redcard{{$m->home_red}} right">{{$m->home}}</td>
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
                    <td style="text-align: left;" class="away redcard{{$m->away_red}} left">{{$m->away}}</td>
                    <td>
                        @foreach($settings[$m->id]['settings'] as $s)
                        <a href="#" role="button" class="btn btn-info btn-xs">{{$s->game_type->name}}</a>
                        @endforeach
                    </td>
                    <td>@if(count($settings[$m->id]['settings'])>0)<a href="/refund/{{$m->id}}" role="button" class="btn btn-xs btn-warning" @if($settings[$m->id]['refund'] <= 0) disabled @endif>refund</a>@endif</td>

                </tr>
            @endforeach
        </table>
@endforeach
@endif
<script type="text/javascript">

    var asInitVals = new Array();

    $(document).ready(function () {
        $("table tr .livescoreResultTdActive").each(function() {
            var id =$(this).closest('tr').prop('id');
            var td_span1 = $(this).find("#home_goals");
            var td_span2 = $(this).find("#away_goals");
            var td_span3 = $("table #"+id+" .home").find("#home_red");
            var td_span4 = $("table #"+id+" .away").find("#away_red");
            $.post( "/getres/" + id, function( data ) {
                td_span1.html(data[0]+"");
                td_span2.html(data[1]+"");
                var home = '';
                for(var i = 0; i < data[2]; i++) {
                    home = home + '<img src="/images/redcard.png">&nbsp;';
                }
                td_span3.html(home);
                var away = '';
                for(var i = 0; i < data[3]; i++) {
                    away = away + '<img src="/images/redcard.png">&nbsp;';
                }
                td_span4.html(away);
            });
        });
        setInterval(function() {
            $("table tr .livescoreResultTdActive").each(function() {
                var id =$(this).closest('tr').prop('id');
                var td_span1 = $(this).find("#home_goals");
                var td_span2 = $(this).find("#away_goals");
                var td_span3 = $("table #"+id+" .home").find("#home_red");
                var td_span4 = $("table #"+id+" .away").find("#away_red");
                $.post( "/getres/" + id, function( data ) {
                    td_span1.html(data[0]+"");
                    td_span2.html(data[1]+"");
                    var home = '';
                    for(var i = 0; i < data[2]; i++) {
                        home = home + '<img src="/images/redcard.png">&nbsp;';
                    }
                    td_span3.html(home);
                    var away = '';
                    for(var i = 0; i < data[3]; i++) {
                        away = away + '<img src="/images/redcard.png">&nbsp;';
                    }
                    td_span4.html(away);
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
