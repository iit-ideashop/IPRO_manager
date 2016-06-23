@extends('layouts.master')

@section('content')
<div class="jumbotron">
    <h1>IPRO Proposal System</h1>
    <p>The IPRO Proposal system is built into the IPRO Management application. Sign in with your university credentials or a Google account to access the proposal application.</p>
    <p><a class="btn btn-primary" href="{{ URL::to('/authenticate') }}"><span class="glyphicon glyphicon-lock"></span> Go to IPRO Proposals</a></p>
</div>
@stop
@section('override_content_div')
    <div class=" col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 main">
@stop



