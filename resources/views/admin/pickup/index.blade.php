@extends('layouts.master')
@include('layouts.dataTables')
@section('content')
<h1 class="page-header">Pickup</h1>
    <div class="row">
        <div class="col-xs-6">
        <p>Please enter/scan the ID of the student attempting to pickup an order</p>
        {{ Form::open(array('route'=>'admin.order.pickup.search')) }}
        <div class="form-group">
            {{ Form::label('idnumber','ID Number') }}
            {{ Form::text('idnumber',null,array('class'=>'form-control','placeholder'=>'Enter CWID-A#')) }}
        </div>
        <div class="form-group">
            {{ Form::submit("View Items for Pickup",array('class'=>'form-control btn-primary')) }}
        </div>

        </div>
        <div class="col-xs-6">
            <p>Search for the user, use % for wildcard searches</p>
            <div class="form-group">
                {{ Form::label('email','Email') }}
                {{ Form::text('email',null,array('class'=>'form-control','placeholder'=>'Enter Email')) }}
            </div>
            <div class="form-group">
                {{ Form::label('firstName','First Name') }}
                {{ Form::text('firstName',null,array('class'=>'form-control','placeholder'=>'Enter first name')) }}
            </div>
            <div class="form-group">
                {{ Form::label('lastName','Last Name') }}
                {{ Form::text('lastName',null,array('class'=>'form-control','placeholder'=>'Enter last name')) }}
            </div>
            <div class="form-group">
                {{ Form::submit("Search",array('class'=>'form-control btn-primary')) }}
            </div>
            {{ Form::close() }}

        </div>
    </div>
@stop

@section('javascript_bottom')
    <script>
        $(document).on("ready",function(){
            $("#idnumber").focus();
        });
    </script>
@stop

