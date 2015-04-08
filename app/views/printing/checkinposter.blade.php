@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')
    @include("printing.printingNavigation")
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

