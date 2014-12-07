@extends('layout')

@section('content')
<table class="table">
    <thead>
    <tr>
        <th></th>
        <th>date</th>
        <th>home</th>
        <th>away</th>
        <th>res</th>
    </tr>
    </thead>
    <tbody>
    @foreach($matches as $d)
    <tr id="{{$d['match']->id}}">
        <td><img src="/images/16/{{$d['league']->country_alias}}.png">&nbsp;{{$d['league']->country_alias}}</td>
        <td>{{date('d M, H:i', strtotime($d['match']->date_time))}}</td>
        <td>{{$d['match']->home}}</td>
        <td>{{$d['match']->away}}</td>
        <?php
        if($d['match']->short_result == '-' && $d['match']->date_time <= date('Y-m-d H:i:s', time())) {
            $active_livescore = true;
        } else {
            $active_livescore = false;
        }
        ?>
        <td @if($active_livescore) class="livescoreResultTdActive" @else class="livescoreResultTdInactive" @endif>
        <div>
            <span @if($active_livescore) class="livescoreResultText" @endif>
            @if ($d['match']->short_result != '-')
            {{$d['match']->home_goals}} : {{$d['match']->away_goals}}
            @else
            -
            @endif
            </span>
        </div>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
<script type="text/javascript">

    var asInitVals = new Array();

    $(document).ready(function () {
        $("table tr .livescoreResultTdActive div .livescoreResultText").each(function() {
            var id =$(this).closest('tr').prop('id');
            var td = $(this);
            $.post( "/getres/" + id, function( data ) {
                td.html(data);
            });
        });
        setInterval(function() {
            $("table tr .livescoreResultTdActive div .livescoreResultText").each(function() {
                var id =$(this).closest('tr').prop('id');
                var td = $(this);
                $.post( "/getres/" + id, function( data ) {
                    td.html(data);
                });
            })

        }, 30000);
        setInterval(function() {
            $("table tr .livescoreResultTdActive div span span").each(function() {
                $(this).toggleClass('livescoreIndicator');
            })
        }, 1000);
    });
</script>

@stop