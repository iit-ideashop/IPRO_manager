@extends('layouts.master')
@include('layouts.fontawesome')
@include('layouts.autocomplete')
@section('content')
    @include("admin.iproday.iprodayNavigation")
    <h3>Enter Peoples Choice vote <div class="pull-right"><a class="btn btn-default" href="{{ URL::route("admin.iproday.peopleschoice.terminal") }}" target="_blank">Open Peoples Choice Terminal</a></div></h3>
    <div class="row">
        <div class="col-xs-6">
            {{ Form::open(array('route'=>'admin.iproday.peopleschoice.add')) }}
            <div class="form-group">
                {{ Form::label('firstName','First Name') }}
                {{ Form::text('firstName',null,array('class'=>'form-control','placeholder'=>'John',"tabindex"=>"1")) }}
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        {{ Form::label('idtype','Identification Type') }}
                        <div class="radio">
                            <label>
                                <input type="radio" name="idtype" id="idtype" value="stateid" checked tabindex="3">
                                State ID
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="idtype" id="idtype" value="driverslicense" tabindex="4">
                                Drivers License
                            </label>
                        </div>
                    </div>

                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        {{ HTML::decode(Form::label('idnumber','Identification Number <br> Format: (Last 4 ID - Address Digits - ZipCode)')) }}
                        {{ Form::text('idnumber',null,array('class'=>'form-control','placeholder'=>'1234-4567-60616',"tabindex"=>"5")) }}
                    </div>
                </div>
            </div>



        </div>
        <div class="col-xs-6">
            <div class="form-group">
                {{ Form::label('lastName','Last Name') }}
                {{ Form::text('lastName',null,array('class'=>'form-control','placeholder'=>'Smith',"tabindex"=>"2")) }}
            </div><br>
            <div class="form-group">
                {{ Form::label('projectid','Project') }}
                <input type="text" id="project-listing" class="form-control" tabindex="6">
                {{ Form::hidden('projectid',null,array("id"=>"projectid")) }}
            </div>


        </div>
    </div>
    <div class="row">
        <div class="col-xs-offset-6 col-xs-6">
            <div class="form-group">
                {{ Form::submit("Add Peoples Choice Vote",array("class"=>"form-control btn-primary","tabindex"=>"7")) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
<h3>Recent Peoples Choice votes (Last 20)</h3>
    <table class="table table-striped table-hover">
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>ID #</th>
            <th>Project</th>
            <th>Vote time</th>
        </tr>
        @foreach($votes as $vote)
        <tr>
            <td>{{ $vote->FirstName }}</td>
            <td>{{ $vote->LastName }}</td>
            <td>{{ $vote->idnumber }}</td>
            <td>{{ Project::getProjectUID($vote->ProjectID) }}</td>
            <td>{{ date('D F jS Y, g:i a',strtotime($vote->updated_at)) }}</td>
        </tr>
            @endforeach
    </table>
@stop

@section('javascript_bottom')
<script>
    var projectListing = [
        @foreach($projects as $project)
        {value: "{{ $project->UID }} {{$project->Name}}", data:{{$project->id}} },
        @endforeach
        ];
    $(document).on("ready",function(){

        //setup the autocompelte
        $("#project-listing").autocomplete({
            lookup: projectListing,
            onSelect: function (suggestion) {
                $("#projectid").val(suggestion.data);
            }
        });

    });
</script>
@stop



