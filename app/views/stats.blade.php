@extends('layout')

@section('content')

@foreach($leagues as $l)
<a href={{URL::to('/')."/stats/".$l->country}}><img src="/images/48/{{$l->country_alias}}.png"></a>
@endforeach

@stop