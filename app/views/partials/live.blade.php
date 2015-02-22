<?php
if($match->short_result == '-' && $match->date_time <= date('Y-m-d H:i:s', time())) {
    $active_livescore = true;
} else {
    $active_livescore = false;
}
?>
@if (!$active_livescore &&  $match->short_result != '-')
<td @if(isset($style)) {{$style}} @endif>
    <span class="score scoreFinished" id="home_goals">{{$match->home_goals}}</span><span class="scoreSeparator">:</span><span id='away_goals' class="score scoreFinished">{{$match->away_goals}}</span>
</td>
@elseif($active_livescore)
<td @if(isset($style)) {{$style}} @endif class="livescoreResultTdActive" id="{{$match->id}}">
    <span class="score scoreRunning" id="home_goals">&nbsp;</span><span class="scoreSeparator" id="scoreSeparator">:</span><span id='away_goals' class="score scoreRunning">&nbsp;</span><p class="time"></p>
</td>
@else
<td @if(isset($style)) {{$style}} @endif>
    <span class="score scoreNotStarted" id="home_goals">-</span><span class="scoreSeparator">:</span><span id='away_goals' class="score scoreNotStarted">-</span>
</td>
@endif

<script>
    $("table tr .livescoreResultTdActive").each(function() {
        var id =$(this).closest('tr').prop('id');
        var td_span1 = $(this).find("#home_goals");
        var td_span2 = $(this).find("#away_goals");
        var td_span3 = $("table #"+id+" .home");
        var td_span4 = $("table #"+id+" .away");
        var td_span5 = $("table #"+id+" .time");
        $.post( "/getres/" + id, function( data ) {
            td_span1.html(data[0]+"");
            td_span2.html(data[1]+"");
            td_span3.addClass('redcard' + data[2]);
            td_span4.addClass('redcard' + data[3]);
            if(data[5] != 0 || data[5] != '0') {
                td_span5.html(data[4] + " " + data[5] + "'");
            } else if (data[4] == 'HT' || data[4] == 'FT'){
                td_span5.html(data[4]);
            } else {
                td_span5.html("");
            }
        });
    });
    setInterval(function() {
        $("table tr .livescoreResultTdActive").each(function() {
            var id =$(this).closest('tr').prop('id');
            var td_span1 = $(this).find("#home_goals");
            var td_span2 = $(this).find("#away_goals");
            var td_span3 = $("table #"+id+" .home");
            var td_span4 = $("table #"+id+" .away");
            var td_span5 = $("table #"+id+" .time");
            $.post( "/getres/" + id, function( data ) {
                td_span1.html(data[0]+"");
                td_span2.html(data[1]+"");
                td_span3.addClass('redcard' + data[2]);
                td_span4.addClass('redcard' + data[3]);
                if(data[5] != 0 || data[5] != '0') {
                    td_span5.html(data[4] + " " + data[5] + "'");
                } else if (data[4] == 'HT' || data[4] == 'FT'){
                    td_span5.html(data[4]);
                } else {
                    td_span5.html("");
                }
            });
        })

    }, 30000);
    setInterval(function() {
        $("table tr .livescoreResultTdActive #scoreSeparator").each(function() {
            $(this).toggleClass('scoreSeparatorToggle');
        })
    }, 1000);
</script>