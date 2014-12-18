<a href="#" type="button" data-toggle="tooltip" data-placement="top" title="<strong>@if($match->short_result == '-')-:-@else{{$match->home_goals}}:{{$match->away_goals}}@endif</strong>&nbsp;({{$match->home}}&nbsp;-&nbsp;{{$match->away}})<br/>{{ date("d.m.Y",strtotime($match->date_time)) }}" class="btn hasTooltip
        @if($match->short_result == '-')
            {{"btn-warning"}}
            btn-xs">O
        @else
            {{"btn-primary"}}
            btn-xs">X
        @endif
</a>
