@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')
    @include("printing.printingNavigation")
    <table class="table table-striped table-hover">
        <tr>
            <th>Document</th>
            <th>Type</th>
            <th>Uploader</th>
            <th>Project</th>
            <th>Dimensions</th>
            <th>Approve/Deny</th>
        </tr>
        @foreach($files as $file)
            <tr>
                <td><a href="{{URL::route("printing.downloadfile", $file->id)}}">{{ $file->original_filename }}</a>
                    @if($file->override)
                        <i class="fa fa-exclamation-circle text-danger" ></i>
                    @endif
                </td>
                <td>{{ $file->file_type }}</td>
                <td>{{ User::getFullNameWithId($file->UserID) }}</td>
                <td>{{ Project::getProjectUID($file->ProjectID) }}</td>
                <td>{{ $file->dimensions }}</td>
                <td>
                    <button class="btn btn-danger" id="denyFile-{{$file->id}}" onclick="denyFile({{ $file->id }});">Deny</button>
                    <button onclick="approveFile({{ $file->id }});" id="approveFile-{{$file->id}}" class="btn btn-success">Approve</button>
                </td>
            </tr>
        @endforeach
    </table>
@stop

@section('javascript_bottom')
<script>
    approveFile(file_id){


    }


</script>
@stop

