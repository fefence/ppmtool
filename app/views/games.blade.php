@extends('layout')

@section('content')

@if ($no_info)
<h5>No matches for today and/or no leagues selected.</h5>
@endif
@foreach($data as $c => $games)

<div id="{{$c}}">
    <table class="table" border="0">
        <thead>
        <tr>
            <th class="text-center" style="width: 65px;"><a href="{{$games['league']->be}}" target="_blank"><img src="/images/32/{{$c}}.png"></a></th>
            <th class="text-center" style="width: 10%;">type</th>
            <th style="text-align: right;">home</th>
            <th style="width: 55px; text-align: center"></th>
            <th>away</th>
            <th class="text-center" style="width: 8%;">bsf</th>
            <th class="text-center" style="width: 8%;">bet</th>
            @if(Session::get('message') != null && Session::get('message')==$c)
            <th id="flash" class="text-center bg-success text-success" style="width: 8%; padding: 0px;"><a href="/play/odds/{{$c}}">odds</a></th>
            @else
            <th class="text-center" style="width: 8%;"><a href="/play/odds/{{$c}}">odds</a></th>
            @endif
            <th class="text-center" style="width: 10%;">income</th>
            <th class="text-center" style="width: 8%;"><a href="/confirmall/{{$c}}" role="button" class="btn btn-xs @if(isset($games['disabled']) && $games['disabled'] == '') btn-warning @else btn-default @endif" style="width: 100%" {{$games['disabled']}} >all</a></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($games['games']))
        @foreach($games['games'] as $game)
        <tr id="{{$game['match']['id']}}">
            <td class="text-center">{{date('d M', strtotime($game['match']['date_time']))}}<br>{{date('H:i', strtotime($game['match']['date_time']))}}</td>
            <td class="text-center"><a href="/series/{{$game['series_id']}}">{{$game['game_type']['name']}}</a>&nbsp;[{{$game['current_length']}}]</td>
            <td style="text-align: right; position: relative;" class="home redcard{{$game['match']['home_red']}} right">{{$game['match']['home']}}</td>
            @include('partials.live', ['match' => $game['match']])
            <td class="away redcard{{$game['match']['away_red']}} left">{{$game['match']['away']}}</td>
            <td class="editablecolor1 editable text-center" id="bsf_{{$game['id']}}_game">@if($game['bsf'] != 0.00) {{$game['bsf']}}@endif</td>
            <td class="warning editable text-center" id="bet_{{$game['id']}}_game">@if($game['bet'] != 0.00) {{$game['bet']}}@endif</td>
            @if ($game['odds'] == 0.00 || $game['odds'] == -1.00)
            <td class="editable text-center bg-danger text-danger" id="odds_{{$game['id']}}_game"></td>
            @else
            <td class="editablecolor1 editable text-center" id="odds_{{$game['id']}}_game">{{$game['odds']}}</td>
            @endif
            <td class="text-center"><span id="income_{{$game['id']}}_game">{{$game['income']}}</span><br>[<span id="profit_{{$game['id']}}_game">{{$game['profit']}}</span>]</td>
            <td>@if($game['short_result'] == '-')
                <a role="button" @if ($count[$game['id']]['count'] != 0) class="btn btn-default btn-xs" @else class="btn btn-primary btn-xs" @endif style="width: 100%" href="/confirm/{{$game['id']}}/false" style="font-size: 130%;">+&nbsp({{ $count[$game['id']]['count'] }})</a>
                @elseif($count[$game['id']]['endseries'])
                <a role="button" class="btn btn-success btn-xs" style="width: 100%" disabled href="/confirm/{{$game['id']}}/false">+&nbsp({{ $count[$game['id']]['count'] }})</a>
                @else
                <a role="button" class="btn btn-default btn-xs" style="width: 100%" disabled href="/confirm/{{$game['id']}}/false">+&nbsp({{ $count[$game['id']]['count'] }})</a>
                @endif
            </td>
        </tr>

        @endforeach
        @endif
        @if(isset($games['placeholders']))
        @foreach($games['placeholders'] as $game)
        <tr id="{{$game['match']['id']}}">
            <td class="text-center">{{date('d M', strtotime($game['match']['date_time']))}}<br>{{date('H:i', strtotime($game['match']['date_time']))}}</td>
            <td class="text-center"><a href="/series/{{$game['series_id']}}">{{$game['game_type']['name']}}</a></td>
            <td style="text-align: right;"><em>{{$game['match']['home']}}</em></td>
            <?php
            if($game['match']['short_result'] == '-' && $game['match']['date_time'] <= date('Y-m-d H:i:s', time())) {
                $active_livescore = true;
            } else {
                $active_livescore = false;
            }
            ?>
            @if (!$active_livescore &&  $game['match']['short_result'] != '-')
            <td>
                <span class="score scoreFinished" id="home_goals">{{$game['match']['home_goals']}}</span><span class="scoreSeparator">:</span><span id='away_goals' class="score scoreFinished">{{$game['match']['away_goals']}}</span>
            </td>
            @elseif($active_livescore)
            <td class="livescoreResultTdActive" id="{{$game['match']['id']}}">
                <span class="score scoreRunning" id="home_goals">&nbsp;</span><span class="scoreSeparator" id="scoreSeparator">:</span><span id='away_goals' class="score scoreRunning">&nbsp;</span>
            </td>
            @else
            <td>
                <span class="score scoreNotStarted" id="home_goals">-</span><span class="scoreSeparator">:</span><span id='away_goals' class="score scoreNotStarted">-</span>
            </td>
            @endif

            <td><em>{{$game['match']['away']}}</em></td>
            <td class="editablecolor1 editable text-center" id="bsf_{{$game['id']}}_pl">@if($game['bsf'] != 0.00) {{$game['bsf']}}@endif</td>
            <td class="warning editable text-center" id="bet_{{$game['id']}}_pl">@if($game['bet'] != 0.00) {{$game['bet']}}@endif</td>
            @if ($game['odds'] == 0.00 || $game['odds'] == -1.00)
            <td class="editable text-center bg-danger text-danger" id="odds_{{$game['id']}}_pl"></td>
            @else
            <td class="editablecolor1 editable text-center" id="odds_{{$game['id']}}_pl">{{$game['odds']}}</td>
            @endif
            <td class="text-center"><span id="income_{{$game['id']}}_pl">{{$game['income']}}</span><br>[<span id="profit_{{$game['id']}}_pl">{{$game['profit']}}</span>]</td>
            <td>@if($game['short_result'] == '-')
                <a role="button" @if ($count_pl[$game['id']] != 0) class="btn btn-default btn-xs" @else class="btn btn-primary btn-xs" @endif style="width: 100%" href="/confirm/{{$game['id']}}/true" style="font-size: 130%;">+&nbsp({{ $count_pl[$game['id']] }})</a>
                @else
                <a role="button" class="btn btn-default btn-xs" style="width: 100%" disabled href="/confirm/{{$game['id']}}/true">+&nbsp({{ $count_pl[$game['id']] }})</a>
                @endif
            </td>
        </tr>

        @endforeach
        @endif
        </tbody>
    </table>
</div>
@endforeach
<script>
    var asInitVals = new Array();

    $(document).ready(function(){
        setTimeout(function () {
            $("#flash").removeClass('bg-success text-success', 1000)
        }, 1000);

        $(".editable").editable("/play/save", {
            height : '20',
            width : '100%',
            select : 'true',
            placeholder: '',
            type: 'number',
            callback : function(value) {
                var arr = value.split('*');
//                alert(value);
                var pl = arr[6];
                if (arr[1] != 0.00) {
                    $('#bsf_'+arr[0]+'_'+pl).text(arr[1]);
                } else {
                    $('#bsf_'+arr[0]+'_'+pl).text("");
                }
                if (arr[2] != 0.00){
                    $('#bet_'+arr[0]+'_'+pl).text(arr[2]);
                } else{
                    $('#bet_'+arr[0]+'_'+pl).text("");
                }
                if (arr[3] != 0.00 && arr[3] != -1){
                    $('#odds_'+arr[0]+'_'+pl).text(arr[3]);
                    $('#odds_'+arr[0]+'_'+pl).removeClass("bg-danger text-danger");
                    $('#odds_'+arr[0]+'_'+pl).addClass("editablecolor1");
                } else {
                    $('#odds_'+arr[0]+'_'+pl).text("");
                    $('#odds_'+arr[0]+'_'+pl).addClass("bg-danger text-danger");
                    $('#odds_'+arr[0]+'_'+pl).removeClass("editablecolor1");
                }
                $('#income_'+arr[0]+'_'+pl).text(arr[4]);
                $('#profit_'+arr[0]+'_'+pl).text(arr[5]);
            }
        });
    });

</script>
@stop
