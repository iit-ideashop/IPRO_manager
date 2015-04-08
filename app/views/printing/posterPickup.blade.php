@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')
    @include("printing.printingNavigation")
    <div class="row">
        <div class="col-xs-6">
            <p>Please enter/scan the ID of the student attempting to pickup a poster</p>
            {{ Form::open(array('route'=>'printing')) }}
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

    <table class="table table-striped table-hover">
        <tr>
            <th> </th>
            <th>Document</th>
            <th>Type</th>
            <th>Size</th>
            <th>Dimensions</th>
            <th>Uploader</th>
            <th>Project</th>
        </tr>
        @foreach($files as $file)
            <tr id="file-tr-{{ $file->id }}" onclick="selectCheckbox('{{ $file->id }}');">
                <td><input type="checkbox" id="file-checkbox-{{ $file->id }}"></td>
                <td><a href="{{URL::route("printing.viewfile", $file->id)}}" target="_blank">{{ $file->original_filename }}</a>
                    @if($file->override)
                        <i class="fa fa-exclamation-circle text-danger" ></i>
                    @endif
                </td>
                <td>{{ $file->file_type }}</td>
                <td>{{ $file->size }}</td>
                <td>{{ $file->dimensions }}</td>
                <td>{{ User::getFullNameWithId($file->UserID) }}</td>
                <td>{{ Project::getProjectUID($file->ProjectID) }}</td>
            </tr>
        @endforeach
    </table>
    <i class="fa fa-exclamation-circle text-danger" ></i> - Signifies a non standard size file
@stop

@section('javascript_bottom')
<script>
    function selectCheckbox(fileid){
        //Make it easier to select rows
        var checkBox = $("#file-checkbox-"+fileid);
        checkBox.prop("checked", !checkBox.prop("checked"));
    }
</script>
@stop



