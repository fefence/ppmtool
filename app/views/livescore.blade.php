@extends('layout')

@section('content')
<div class="container">
    <div class="row">
        <p><a href="/list/country" role="button" class="btn btn-default">country</a></p>
    </div>
</div>
<div class="container">
    <div class="row">
        <table class="table">
            <tbody>
            @foreach($matches as $d)
            <tr id="{{$d['match']->id}}">
                <td style="width: 70px; text-align: center;"><img src="/images/32/{{$d['league']->country_alias}}.png"></td>
                <td style="width: 60px;">{{date('H:i', strtotime($d['match']->date_time))}}</td>
                <td style="text-align: right;">{{$d['match']->home}}</td>
                <?php
                if($d['match']->short_result == '-' && $d['match']->date_time <= date('Y-m-d H:i:s', time())) {
                    $active_livescore = true;
                } else {
                    $active_livescore = false;
                }
                ?>
                @if (!$active_livescore &&  $d['match']->short_result != '-')
                <td style="width: 50px;">
                    <span class="score scoreFinished" id="home_goals">{{$d['match']->home_goals}}</span><span class="scoreSeparator">:</span><span id='away_goals' class="score scoreFinished">{{$d['match']->away_goals}}</span>
                </td>
                @elseif($active_livescore)
                <td style="width: 50px;" class="livescoreResultTdActive" id="{{$d['match']->id}}">
                    <span class="score scoreRunning" id="home_goals">&nbsp;</span><span class="scoreSeparator" id="scoreSeparator">:</span><span id='away_goals' class="score scoreRunning">&nbsp;</span>
                </td>
                @else
                <td style="width: 50px;">
                    <span class="score scoreNotStarted" id="home_goals">-</span><span class="scoreSeparator">:</span><span id='away_goals' class="score scoreNotStarted">-</span>
                </td>
                @endif
                <td style="text-align: left;">{{$d['match']->away}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
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