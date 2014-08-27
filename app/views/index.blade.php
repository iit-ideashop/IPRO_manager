@extends('layouts.master')

@section('content')
<div class="jumbotron">
    <h1>IPRO Management Application</h1>
    <p>Welcome to the IPRO Management application. This application is used by IPROs to manage their IPRO, make budget requests
    and orders. Please login below to use the application. If you are having issues logging in and accessing your IPRO please contact
    your instructor</p>
    <h6>* You will be asked to login to Google. Please login using your university credentials</h6>
    <p><a class="btn btn-primary" href="{{ URL::to('/authenticate') }}"><span class="glyphicon glyphicon-lock"></span> Go to IPRO Management</a></p>
</div>
@stop
@section('override_content_div')
    <div class=" col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 main">
@stop



