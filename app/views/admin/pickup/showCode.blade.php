@extends('layouts.master')
@include('layouts.dataTables')
@section('content')
<h1 class="page-header">User Approval Required</h1>
    <div class="row">
        <div class="col-xs-offset-1 col-xs-5">
            <h5>Use the iPad to have the user sign the pickup contract.</h5>
            <h3>Code: {{ $pickup->RetreiveCode }}</h3>
            <h5>Click the button below once the contract has been signed</h5>
            <button id="pickupCompleted" class="btn btn-primary">Completed</button>
        </div>
        <div class="col-xs-5">
            <h5>Override the pickup contract</h5>
            <p>Reason for override: </p>
            <textarea class="form-control" rows="3"></textarea><br>
            <p><button class="btn btn-primary">Override</button></p>
        </div>
    </div>
@stop
@section('javascript_bottom')

@stop