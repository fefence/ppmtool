@extends('layout')

@section('content')

@foreach($leagues as $l)
<a href={{URL::to('/')."/stats/".$l->country}}><img src="/images/48/{{$l->country_alias}}.png"></a>
@endforeach

<ul class="nav nav-tabs" id="myTab" style="border: none">
    <li class="active"><a href="#1">1X2</a></li>
    <li><a href="#2">0-0</a></li>
    <li><a href="#3">1-1</a></li>
    <li><a href="#4">2-2</a></li>
    <li><a href="#5">0-1</a></li>
    <li><a href="#6">0-2</a></li>
    <li><a href="#7">1-0</a></li>
    <li><a href="#8">2-0</a></li>
    <li><a href="#9">1-2</a></li>
    <li><a href="#10">2-1</a></li>
</ul>
<div id='content' class="tab-content">

    @foreach($data as $i => $stats)
    <div class="tab-pane @if($i == 1) active @endif" id="{{$i}}">
        <table class="table">
            <thead>
            <th style="width: 9%;"></th>
            <th>
                <span>Top 25:
                            @foreach($stats['all'] as $l)
                                {{$l}},&nbsp;
                            @endforeach
                        </span></th>
            <th style="width: 9%"></th>
            </thead>
            <tbody>
                @foreach($stats as $season => $el)
                @if($season != 'all')
                <tr>
                    <td>{{$season}}</td>
                    <td>
                        @foreach($el['stats'] as $s)
                        {{ $s->length}}
                        @include('partials.square', array('match' => $s))
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
