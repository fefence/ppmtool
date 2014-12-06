@extends('layout')

@section('content')
    <table class="table table-bordered">
        <thead>
            <th style="width: 40px;"><img src="/images/AUS.png"></th>
            <th>home</th>
            <th>away</th>
            <th>l</th>
            <th>res</th>
            <th>game</th>
            <th style="width: 55px">bsf</th>
            <th>bet</th>
            <th>odds</th>
            <th>income</th>
            <th>profit</th>
        </thead>
        <tbody>
        @foreach($data as $game)
        <tr>
            <td></td>
            <td>{{$game->match->home}}</td>
            <td>{{$game->match->away}}</td>
            <td>{{$game->current_length}}</td>
            <td>
                @if ($game->match->short_result != '-')
                {{$game->match->home_goals}}:{{$game->match->away_goals}}
                @else
                -
                @endif
            </td>
            <td>{{$game->game_type->name}}</td>
            <td class="editable" id="bsf">{{$game->bsf}}</div></td>
            <td>{{$game->bet}}</td>
            <td>{{$game->odds}}</td>
            <td>{{$game->income}}</td>
            <td>{{$game->profit}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

<table class="table table-bordered">
    <thead>
    <th style="width: 40px;"><img src="/images/AUS.png"></th>
    <th>home</th>
    <th>away</th>
    <th>l</th>
    <th>res</th>
    <th>game</th>
    <th style="width: 55px">bsf</th>
    <th>bet</th>
    <th>odds</th>
    <th>income</th>
    <th>profit</th>
    </thead>
    <tbody>
    @foreach($data as $game)
    <tr>
        <td></td>
        <td>{{$game->match->home}}</td>
        <td>{{$game->match->away}}</td>
        <td>{{$game->current_length}}</td>
        <td>
            @if ($game->match->short_result != '-')
            {{$game->match->home_goals}}:{{$game->match->away_goals}}
            @else
            -
            @endif
        </td>
        <td>{{$game->game_type->name}}</td>
        <td class="editable" id="bsf">{{$game->bsf}}</div></td>
        <td>{{$game->bet}}</td>
        <td>{{$game->odds}}</td>
        <td>{{$game->income}}</td>
        <td>{{$game->profit}}</td>
    </tr>
    @endforeach
    </tbody>
</table>

<table class="table table-bordered">
    <thead>
    <th style="width: 40px;"><img src="/images/AUS.png"></th>
    <th>home</th>
    <th>away</th>
    <th>l</th>
    <th>res</th>
    <th>game</th>
    <th style="width: 55px">bsf</th>
    <th>bet</th>
    <th>odds</th>
    <th>income</th>
    <th>profit</th>
    </thead>
    <tbody>
    @foreach($data as $game)
    <tr>
        <td></td>
        <td>{{$game->match->home}}</td>
        <td>{{$game->match->away}}</td>
        <td>{{$game->current_length}}</td>
        <td>
            @if ($game->match->short_result != '-')
            {{$game->match->home_goals}}:{{$game->match->away_goals}}
            @else
            -
            @endif
        </td>
        <td>{{$game->game_type->name}}</td>
        <td class="editable" id="bsf">{{$game->bsf}}</div></td>
        <td>{{$game->bet}}</td>
        <td>{{$game->odds}}</td>
        <td>{{$game->income}}</td>
        <td>{{$game->profit}}</td>
    </tr>
    @endforeach
    </tbody>
</table>

<table class="table table-bordered">
    <thead>
    <th style="width: 40px;"><img src="/images/AUS.png"></th>
    <th>home</th>
    <th>away</th>
    <th>l</th>
    <th>res</th>
    <th>game</th>
    <th style="width: 55px">bsf</th>
    <th>bet</th>
    <th>odds</th>
    <th>income</th>
    <th>profit</th>
    </thead>
    <tbody>
    @foreach($data as $game)
    <tr>
        <td></td>
        <td>{{$game->match->home}}</td>
        <td>{{$game->match->away}}</td>
        <td>{{$game->current_length}}</td>
        <td>
            @if ($game->match->short_result != '-')
            {{$game->match->home_goals}}:{{$game->match->away_goals}}
            @else
            -
            @endif
        </td>
        <td>{{$game->game_type->name}}</td>
        <td class="editable" id="bsf">{{$game->bsf}}</div></td>
        <td>{{$game->bet}}</td>
        <td>{{$game->odds}}</td>
        <td>{{$game->income}}</td>
        <td>{{$game->profit}}</td>
    </tr>
    @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function(){
        $(".editable").editable("#", {

        });
    });
</script>
@stop
