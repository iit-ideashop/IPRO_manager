@extends('layouts.master')
@section('content')
<h1 class="page-header">User Approval Required</h1>
    <div class="row">
        <div class="col-xs-offset-1 col-xs-5">
            <h5>Use the iPad to have the user sign the pickup contract.</h5>
            <h3>Code: {{ $pickup->RetreiveCode }}</h3>
            <h5>Click the button below once the contract has been signed</h5>
            {{Form::open(array("route"=>array("admin.order.pickup.confirm", $pickup->id)))}}
            {{Form::submit("Completed",array("class"=>"btn btn-primary"))}}
            {{Form::close()}}
        </div>
        <div class="col-xs-5">
            <h5>Override the pickup contract</h5>
            <p>Reason for override: </p>
            {{ Form::open(array("route"=>array("admin.order.pickup.override", $pickup->id))) }}
            {{ Form::textarea("overrideText",null,array("class"=>"form-control","rows"=>"3")) }}<br />
            {{ Form::submit("Override User Approval",array("class"=>"btn btn-primary")) }}
            {{ Form::close() }}
        </div>
    </div>
@stop