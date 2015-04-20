@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')
    @include("admin.iproday.iprodaynavigation")
<<<<<<< Updated upstream
    <h3>Enter Peoples Choice vote <div class="pull-right"><a class="btn btn-default" href="{{ URL::route("admin.iproday.peopleschoice.terminal") }}">Open Peoples Choice Terminal</a></div></h3>
=======
    <h3>Enter Peoples Choice vote <div class="pull-right"><a class="btn btn-default" href="{{ URL::route("admin.iproday.peopleschoice.terminal") }}" target="_blank">Open Peoples Choice Terminal</a></div></h3>
>>>>>>> Stashed changes
    <div class="row">
        <div class="col-xs-6">
            {{ Form::open(array('route'=>'printing.pickup.search')) }}
            <div class="form-group">
                {{ Form::label('firstName','First Name') }}
                {{ Form::text('firstName',null,array('class'=>'form-control','placeholder'=>'John')) }}
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        {{ Form::label('idtype','Identification Type') }}
                        <div class="radio">
                            <label>
                                <input type="radio" name="idtype" id="idtype" value="stateid" checked>
                                State ID
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="idtype" id="idtype" value="driverslicense">
                                Drivers License
                            </label>
                        </div>
                    </div>

                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        {{ HTML::decode(Form::label('idnumber','Identification Number <br> Format: (Last 4 ID - Address Digits - ZipCode)')) }}
                        {{ Form::text('idnumber',null,array('class'=>'form-control','placeholder'=>'1234-4567-60616')) }}
                    </div>
                </div>
            </div>



        </div>
        <div class="col-xs-6">
            <div class="form-group">
                {{ Form::label('lastName','Last Name') }}
                {{ Form::text('lastName',null,array('class'=>'form-control','placeholder'=>'Smith')) }}
            </div>


        </div>
    </div>
    <div class="row">
        <div class="col-xs-offset-6 col-xs-6">
            <div class="form-group">
                {{ Form::submit("Add Peoples Choice Vote",array("class"=>"form-control btn-primary")) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
<h3>Recent Peoples Choice votes</h3>
    <table class="table table-striped table-hover">
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>ID #</th>
            <th>Project</th>
            <th> </th>
        </tr>
    </table>
@stop

@section('javascript_bottom')
<script>
    $(document).on("ready",function(){
        $("#idnumber").focus();
    });
</script>
@stop



