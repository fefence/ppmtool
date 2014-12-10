@extends('layout')

@section('content')

@foreach($leagues as $l)
<a href={{URL::to('/')."/stats/".$l->country}}><img src="/images/32/{{$l->country_alias}}.png"></a>
@endforeach

<ul class="nav nav-tabs" id="myTab" style="border: none">
    <li class="active"><a href="#1">PPM 1X2</a></li>
    <li><a href="#2">PPM 0-0</a></li>
    <li><a href="#3">PPM 1-1</a></li>
    <li><a href="#4">PPM 2-2</a></li>
    <li><a href="#5">PPM 0-1</a></li>
    <li><a href="#6">PPM 0-2</a></li>
    <li><a href="#7">PPM 1-0</a></li>
    <li><a href="#8">PPM 2-0</a></li>
    <li><a href="#9">PPM 1-2</a></li>
    <li><a href="#10">PPM 2-1</a></li>
</ul>
<div id='content' class="tab-content">

    @foreach($data as $i => $stats)
    <div class="tab-pane @if($i == 1) active @endif" id="{{$i}}">
        <table class="table">
            <thead>
                <th style="width: 10%;"></th>
                <th></th>
                <th style="width: 15%;"></th>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td>
                <span>Top 25 series:
                            @foreach($stats['all'] as $l)
                                {{$l}},&nbsp;
                            @endforeach
                        </span></td>
                    <td></td>
                </tr>
                @foreach($stats as $season => $el)
                @if($season != 'all')

                <tr>
                    <td>{{$season}}</td>
                    <td>
                        @foreach($el['stats'] as $s)
                        {{ $s->length}}
                        <?php
                        $d = ['team' => '', 'match' => $s]
                        ?>
                        @include('partials.square', array('data' => $d))
    <!--                    {{$s->short_result}}-->
                        @endforeach
                    </td>
                    <td>
                        @foreach($el['longest'] as $l)
                        {{$l}},&nbsp;
                        @endforeach
                    </td>
                </tr>
                @endif

                @endforeach
            </table>
        </tbody>
    </div>
    @endforeach
</div>
<script type="text/javascript">
    $('#myTab a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })
</script>
@stop
