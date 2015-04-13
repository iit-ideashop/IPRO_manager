@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')
    @include("printing.printingNavigation")
    <div class="row">
        <div class="col-xs-6">
            <p>Please enter/scan the ID of the student attempting to pickup a poster</p>
            {{ Form::open(array('route'=>'printing.pickup.search')) }}
            <div class="form-group">
                {{ Form::label('idnumber','ID Number') }}
                {{ Form::text('idnumber',null,array('class'=>'form-control','placeholder'=>'Enter CWID-A#')) }}
            </div>
            <div class="form-group">
                {{ Form::submit("View Items for Pickup",array('class'=>'form-control btn-primary')) }}
            </div>

        </div>
        <div class="col-xs-6">
            <p>Search for the user. Search uses wildcards to search.</p>
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
<h3>Posters awaiting Pickup</h3>
    <table class="table table-striped table-hover">
        <tr>
            <th>Document</th>
            <th>Type</th>
            <th>Size</th>
            <th>Dimensions</th>
            <th>Uploader</th>
            <th>Project</th>
            <th>Submitted</th>
            <th> </th>
        </tr>
        @foreach($files as $file)
            <tr id="file-tr-{{ $file->id }}">
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
                <td>{{ date('D F jS Y, g:i a' ,strtotime($file->created_at)) }}</td>
                @if($file->barcode != NULL)
                    <td><a href="{{ URL::route("printing.printBarcode", $file->id) }}" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-barcode"></i> Reprint Label</a></td>
                @else
                    <td><a href="{{ URL::route("printing.printBarcode", $file->id) }}" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-barcode"></i> Print Label</a></td>
                @endif
            </tr>
        @endforeach
    </table>
    <i class="fa fa-exclamation-circle text-danger" ></i> - Signifies a non standard size file
@stop

@section('javascript_bottom')
<script>
    $(document).on("ready",function(){
        $("#idnumber").focus();
    });
</script>
@stop



