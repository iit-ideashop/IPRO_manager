@extends('layouts.master')
@section('content')
<h1>Confirm Package Pickup</h1>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">User information</h3>
    </div>
    <div class="panel-body">
        Name: {{$student->FirstName}} {{$student->LastName}}<br>
        Email: {{$student->Email}}<br>
    </div>
</div>
<ul class="list-group">
    <li class="list-group-item disabled">Items being picked up</li>
    @foreach($items as $item)
    <li class="list-group-item"><span class="glyphicon glyphicon-barcode"></span> {{$item->barcode}} | {{$item->Name}}
        @if($item->Returning)
        <span class="badge">Returning</span>
        @endif
    </li>
    @endforeach
</ul>

<p><b>By signing this pickup agreement you agree to the following:</b> </p>
<ul>
    <li>You have been authorized by your team to pickup these items</li>
    <li>You take full responsibility for these items. If any of these items are lost your student account will be billed and a hold will be placed on your account</li>
    <li>These items will be used for your IPRO</li>
    <li>Any items marked as "Returning" must be returned to the Ideashop at the end of the semester</li>
</ul>
@if($pickup->SignatureData != null)
<div class="row"><div class="col-xs-11">
    <img src="{{$pickup->SignatureData}}">
    </div></div>
@endif
@if($pickup->OverrideReason != null)
<div class="row">
    <div class="col-xs-11">
    <label for="overridedata">Override Data:</label>
    <textarea name="overridedata" disabled class="form-control" rows="3">
        {{$pickup->OverrideReason}}
    </textarea>
    </div>
</div>
@endif
<br>
<div class="row">
    <div class="col-xs-5">
        {{ Form::open(array("route"=>array("admin.order.pickup.redo",$pickup->id))) }}
        {{ Form::submit("Redo",array("class"=>"btn btn-danger pull-right")) }}
        {{ Form::close() }}
    </div>
    <div class="col-xs-5">
        {{ Form::open(array("route"=>array("admin.order.pickup.process",$pickup->id))) }}
        {{ Form::submit("Confirm",array("class"=>"btn btn-primary")) }}
        {{ Form::close() }}
    </div>
</div>
@stop