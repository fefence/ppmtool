@extends('layout')

@section('content')
@if($no_info)
<h5>No matches for today.</h5>
@else
        <?php
        $url = '/list';
        $from = date('Y-m-d', strtotime($fromdate." + 2 hours"));
//        $to = date('Y-m-d', strtotime($todate));
        if ($from == date('Y-m-d', time())) {
            $url == '/list';
        } else {
            $url = '/list/'.$from.'/'.$from;
        }
        ?>
        <p><a href="{{$url}}" role="button" class="btn btn-default">list</a></p>

@foreach($matches as $league => $matches)
<?php
$first = true;
?>
        <table class="table">
<!--            <thead>-->
<!--                <th style="width: 50px;"><img src="/images/32/{{$league}}.png"></th>-->
<!--                <th></th>-->
<!--                <th style="width: 60px;"></th>-->
<!--                <th></th>-->
<!--                <th></th>-->
<!--            </thead>-->

            @foreach($matches as $m)
                <tr id="{{$m->id}}">
                    @if (!isset($first) || $first)
                        <th style="width: 50px; border-right: 1px solid #dddddd;" rowspan="{{count($matches)}}"><a href="{{$league_links[$league]}}" target="_blank"><img src="/images/32/{{$league}}.png"></a></th>
                    @endif
                    <?php
                    $first = false;
                    ?>
                    <td>{{date('H:i', strtotime($m->date_time))}}</td>
                    <td style="text-align: right;" class="home redcard{{$m->home_red}} right">{{$m->home}}</td>
                    @include('partials.live', ['match' => $m, 'style' => 'style="text-align: center;"'])
                    <td style="text-align: left;" class="away redcard{{$m->away_red}} left">{{$m->away}}</td>
                    <td>
                        @foreach($settings[$m->id]['settings'] as $s)
                        <a href="/play/{{date('Y-m-d', strtotime($m->date_time))}}/{{date('Y-m-d', strtotime($m->date_time))}}/#{{$league}}" role="button" class="btn btn-info btn-xs hasTooltip" title="{{$s->s}}" @if($s->s == 0) disabled @endif>{{$s->game_type->name}}</a>
                        @endforeach
                    </td>
                    <td>@if(count($settings[$m->id]['settings'])>0 && $m->short_result != '-' && $m->home_goals == 0 && $m->away_goals == 0)<a href="/refund/{{$m->id}}" role="button" class="btn btn-xs btn-warning" @if($settings[$m->id]['refund'] <= 0) disabled @endif>refund</a>@endif</td>

                </tr>
            @endforeach
        </table>
@endforeach
@endif
@stop
