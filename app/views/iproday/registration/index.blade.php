@extends('layouts.registration')
@section('content')
<div class="page-header">
  <h1>IPRO Day open registrations</h1>
</div>
@foreach($iprodays as $iproday)
 <div class="panel panel-default">
  <div class="panel-body">
      IPRO Day on {{date('m/d/Y',strtotime($iproday->eventDate)) }}<div class='pull-right'><a class="btn btn-default" href="/iproday/registration/{{ $iproday->id}}">Register</a></div>
  </div>
</div>
@endforeach

@stop