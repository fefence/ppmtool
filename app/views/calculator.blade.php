@extends('layout')

@section('content')
    @foreach($game_types as $game_type)
        <a href="/calculator/{{$country}}/{{$game_type->id}}/odds" role="button" class="btn btn-info btn-xs @if(Session::get('game') == $game_type->name) active @endif">{{$game_type->name}}</a>
    @endforeach
    <table class="table">
        <thead>
        <tr>
            <th>date</th>
            <th>type</th>
            <th>home</th>
            <th>away</th>
            <th>bsf</th>
            <th>bet</th>
            <th>odds</th>
            <th>income</th>
        </tr>
        </thead>
        <?php
        $i = 0;
        ?>
        @foreach($matches as $match)

            <tr id="{{$i}}">
                <td class="text-center">{{date('d M', strtotime($match->date_time))}}<br>{{date('H:i', strtotime($match->date_time))}}</td>
                <td>{{Session::get('game')}}</td>
                <td>{{$match->home}}</td>
                <td>{{$match->away}}</td>
                <td id="{{$i.'_bsf'}}" class="editablecolor1 editable text-center">{{Session::get($i."_bsf")}}</td>
                <td id="{{$i.'_bet'}}" class="editable">{{Session::get($i."_bet")}}</td>
                <td id="{{$i.'_odds'}}" class="editable warning">{{Session::get($i."_odds")}}</td>
                <td>{{Session::get($i."_income")}}<br>[{{Session::get($i."_profit")}}]</td>
            </tr>
        <?php
            $i = $i+1;
        ?>
        @endforeach
    </table>
<script>
    var asInitVals = new Array();

    $(document).ready(function(){

       $(".editable").editable("/calculator/calculate", {
            height : '20',
            width : '100%',
            select : 'true',
            placeholder: '',
            type: 'number',
            callback : function(value) {
                location.reload();
            }
        });
    });

</script>
@stop
