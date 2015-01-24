@extends('layout')

@section('content')
@if($no_info)
<h5>No matches for today.</h5>
@else
        <?php
            $url = '/listbycountry';
            $from = date('Y-m-d', strtotime($fromdate." + 2 hours"));
            if ($from == date('Y-m-d', time())) {
                $url == '/listbycountry';
            } else {
                $url = '/listbycountry/'.$from.'/'.$from;
            }
            $m = null;
        ?>
        <p><a href="{{$url}}" role="button" class="btn btn-default">country</a></p>
        <table class="table">
            <tbody>
            @foreach($matches as $d)
            <tr id="{{$d['match']->id}}" @if($m != null && date('Y-m-d', strtotime($m->date_time)) != date('Y-m-d', strtotime($d['match']->date_time))) class="border-bottom-overnight" @endif>
                <td style="width: 50px;"><img src="/images/32/{{$d['league']->country_alias}}.png"></td>
                <td style="width: 50px;">{{date('H:i', strtotime($d['match']->date_time) - 3600)}}</td>
                <td style="text-align: right;" class="home redcard{{$d['match']->home_red}} right">{{$d['match']->home}}</td>
                @include('partials.live', ['match' => $d['match'], 'style' => 'style = "width: 60px; text-align: center;"'])
                <td class="away redcard{{$d['match']->away_red}} left">{{$d['match']->away}}</td>
                <td>
                    @foreach($d['settings'] as $s)
                    <a href="/play/{{date('Y-m-d', strtotime($d['match']->date_time))}}/{{date('Y-m-d', strtotime($d['match']->date_time))}}/#{{$d['league']->country_alias}}" role="button" class="btn btn-info btn-xs hasTooltip" title="{{$s->s}}"  @if($s->s == 0) disabled @endif>{{$s->game_type->name}}</a>
                    @endforeach
                </td>
                <td>@if(count($d['settings'])>0 && $d['match']->short_result != '-' && $d['match']->home_goals == 0 && $d['match']->away_goals == 0)<a href="/refund/{{$d['match']->id}}" role="button" class="btn btn-xs btn-warning" @if($d['refund'] <= 0) disabled @endif>refund</a>@endif</td>
            </tr>
            <?php
             $m = $d['match'];
            ?>
            @endforeach
            </tbody>
        </table>
@endif
@stop