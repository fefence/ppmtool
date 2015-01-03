@extends('layout')

@section('content')
@if($no_info)
<h5 xmlns="http://www.w3.org/1999/html">No matches for today.</h5>
@else
        <?php
            $url = '/listbycountry';
            $from = date('Y-m-d', strtotime($fromdate));
            if ($from == date('Y-m-d', time())) {
                $url == '/listbycountry';
            } else {
                $url = '/listbycountry/'.$from.'/'.$from;
            }
        ?>
        <p><a href="{{$url}}" role="button" class="btn btn-default">country</a></p>
        <table class="table">
            <tbody>
            @foreach($matches as $d)
            <tr id="{{$d['match']->id}}">
                <td style="width: 50px;"><img src="/images/32/{{$d['league']->country_alias}}.png"></td>
                <td style="width: 50px;">{{date('H:i', strtotime($d['match']->date_time))}}</td>
                <td style="text-align: right;" class="home redcard{{$d['match']->home_red}} right">{{$d['match']->home}}</td>
                <?php
                if($d['match']->short_result == '-' && $d['match']->date_time <= date('Y-m-d H:i:s', time())) {
                    $active_livescore = true;
                } else {
                    $active_livescore = false;
                }
                ?>
                @if (!$active_livescore &&  $d['match']->short_result != '-')
                <td style="width: 60px; text-align: center;">
                    <span class="score scoreFinished" id="home_goals">{{$d['match']->home_goals}}</span><span class="scoreSeparator">:</span><span id='away_goals' class="score scoreFinished">{{$d['match']->away_goals}}</span>
                </td>
                @elseif($active_livescore)
                <td style="width: 60px; text-align: center;" class="livescoreResultTdActive" id="{{$d['match']->id}}">
                    <span class="score scoreRunning" id="home_goals">&nbsp;</span><span class="scoreSeparator" id="scoreSeparator">:</span><span id='away_goals' class="score scoreRunning">&nbsp;</span>
                </td>
                @else
                <td style="width: 60px; text-align: center;">
                    <span class="score scoreNotStarted" id="home_goals">-</span><span class="scoreSeparator">:</span><span id='away_goals' class="score scoreNotStarted">-</span>
                </td>
                @endif
                <td class="away redcard{{$d['match']->away_red}} left">{{$d['match']->away}}</td>
                <td>
                    @foreach($d['settings'] as $s)
                    <a href="/play/{{date('Y-m-d', strtotime($d['match']->date_time))}}/{{date('Y-m-d', strtotime($d['match']->date_time))}}/#{{$d['league']->country_alias}}" role="button" class="btn btn-info btn-xs hasTooltip" title="{{$s->s}}">{{$s->game_type->name}}</a>
                    @endforeach
                </td>
                <td>@if(count($d['settings'])>0)<a href="/refund/{{$d['match']->id}}" role="button" class="btn btn-xs btn-warning" @if($d['refund'] <= 0) disabled @endif>refund</a>@endif</td>
            </tr>
            @endforeach
            </tbody>
        </table>
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
                td_span3.addClass('redcard' + data[2]);
                td_span4.addClass('redcard' + data[3]);
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
                    td_span3.addClass('redcard' + data[2]);
                    td_span4.addClass('redcard' + data[3]);
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