@extends('layout')

@section('content')
@foreach($data as $c => $games)

    <table class="table table-bordered">
        <thead>
            <th style="width: 110px;"><img src="/images/{{$c}}.png"></th>
            <th>game</th>
            <th>home</th>
            <th>res</th>
            <th>away</th>
            <th>l</th>
            <th style="width: 55px">bsf</th>
            <th>bet</th>
            <th>odds</th>
            <th>income</th>
            <th>profit</th>
        </thead>
        <tbody>
        @foreach($games as $game)
        <tr>
            <td>{{date('d M, H:i', strtotime($game['match']['date_time']))}}</td>
            <td>{{$game['game_type']['name']}}</td>
            <td>{{$game['match']['home']}}</td>
            <td class="success">
                @if ($game['match']['short_result'] != '-')
                {{$game['match']['home_goals']}}:{{$game['match']['away_goals']}}
                @else
                -
                @endif
            </td>
            <td>{{$game['match']['away']}}</td>
            <td>{{$game['current_length']}}</td>
            <td class="editable" id="bsf">{{$game['bsf']}}</div></td>
            <td>{{$game['bet']}}</td>
            <td>{{$game['odds']}}</td>
            <td>{{$game['income']}}</td>
            <td>{{$game['profit']}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endforeach
<script>
    $(document).ready(function(){
        $(".editable").editable("#", {

        });
    });
</script>
@stop
