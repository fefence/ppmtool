
@if(array_get($data, 'team') == '')
    <a href="#" type="button" data-toggle="tooltip" data-placement="top" title="<strong>{{array_get($data, 'match')->home_goals}}:{{array_get($data, 'match')->away_goals}}</strong>&nbsp;({{array_get($data, 'match')->home}}&nbsp;-&nbsp;{{array_get($data, 'match')->away}})<br/>{{ date("d.m.Y",strtotime(array_get($data, 'match')->date_time)) }}" class="btn hasTooltip
    @if(isset($game_type))
        @if(array_get($data, 'match')->short_result == 'H' || array_get($data, 'match')->short_result == 'A')
           btn-xs">{{array_get($data, 'match')->short_result}}
        @elseif(array_get($data, 'match')->short_result == 'D')
            @if(($game_type == '0-0' && array_get($data, 'match')->home_goals == 0)|| ($game_type == '1-1' && array_get($data, 'match')->home_goals == 1)|| ($game_type == '2-2' && array_get($data, 'match')->home_goals == 2)))
            btn-xs">{{array_get($data, 'match')->home_goals}}-{{array_get($data, 'match')->away_goals}}
            @elseif($game_type == '1X2')
            btn-xs w25">X
            @else
           btn-xs w25">X
            @endif
        @elseif(array_get($data, 'match')->short_result == '-' || array_get($data, 'match')->state == 'Canceled')
            btn-info btn-xs w25">{{array_get($data, 'match')->short_result}}
        @endif
    @else
        @if(array_get($data, 'match')->short_result == 'H' || array_get($data, 'match')->short_result == 'A')
            {{"btn-info"}}
        @elseif(array_get($data, 'match')->short_result == 'D')
            {{"btn-info"}}
        @elseif(array_get($data, 'match')->short_result == '-' || array_get($data, 'match')->state == 'Canceled')
            {{"btn-info"}}
        @endif
        btn-xs w25">X
    @endif
    </a>
@else
<a href="#" type="button" data-toggle="tooltip" data-placement="top" title="<strong>{{array_get($data, 'match')->home_goals}}:{{array_get($data, 'match')->away_goals}}</strong>&nbsp;({{array_get($data, 'match')->home}}&nbsp;-&nbsp;{{array_get($data, 'match')->away}})<br/>{{ date("d.m.Y",strtotime(array_get($data, 'match')->date_time)) }}"
    @if(array_get($data, 'match')->short_result == 'D')
    {{'class="btn btn-xs w25 hasTooltip">X'}}
    @elseif((array_get($data, 'match')->short_result == 'H' && array_get($data, 'match')->home == array_get($data, 'team')) || (array_get($data, 'match')->short_result == 'A' && array_get($data, 'match')->away == array_get($data, 'team')))
    {{'class="btnbtn-xs w25 hasTooltip">X'}}
    @elseif((array_get($data, 'match')->short_result == 'A' && array_get($data, 'match')->home == array_get($data, 'team')) || (array_get($data, 'match')->short_result == 'H' && array_get($data, 'match')->away == array_get($data, 'team')))
    {{'class="btn btn-info btn-xs w25 hasTooltip">X'}}
    @else
    {{'class="btn btn-info btn-xs w25 hasTooltip">?'}}
    @endif
</a>
@endif