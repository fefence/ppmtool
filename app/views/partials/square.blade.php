<a href="#" type="button" data-toggle="tooltip" data-placement="top" title="<strong>{{$match->home_goals}}:{{$match->away_goals}}</strong>&nbsp;({{$match->home}}&nbsp;-&nbsp;{{$match->away}})<br/>{{ date("d.m.Y",strtotime($match->date_time)) }}" class="btn hasTooltip
        @if($match->short_result == '-' || $match->state == 'Canceled')
            {{"btn-warning"}}
            btn-xs w25">O
        @else
            {{"btn-primary"}}
            btn-xs w25">X
        @endif
</a>
