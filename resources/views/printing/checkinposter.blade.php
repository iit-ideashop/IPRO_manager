@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')
    @include("printing.printingNavigation")
    <table class="table table-striped table-hover">
        <tr>
            <th>Document</th>
            <th>Type</th>
            <th>Size</th>
            <th>Dimensions</th>
            <th>Uploader</th>
            <th>Project</th>
            <th> </th>
        </tr>
        @foreach($files as $file)
            <tr id="file-tr-{{ $file->id }}">
                <td><a href="{{URL::route("printing.viewfile", $file->id)}}" target="_blank">{{ $file->original_filename }}</a>
                    @if($file->override)
                        <i class="fa fa-exclamation-circle text-danger" ></i>
                    @endif
                    [ <a href="{{URL::route("printing.downloadfile",array("fileid"=>$file->id))}}">Download</a> ]
                </td>
                <td>{{ $file->file_type }}</td>
                <td>{{ $file->size }}</td>
                <td>{{ $file->dimensions }}</td>
                <td>{{ User::getFullNameWithId($file->UserID) }}</td>
                <td><a href="{{ URL::route("printing.projectReport", $file->ProjectID) }}">{{ Project::getProjectUID($file->ProjectID) }}</a></td>
                <td><a href="{{ URL::route("printing.printBarcode", $file->id) }}" target="_blank" class="btn btn-primary" onclick="checkInPoster('{{ $file->id }}')"><i class="glyphicon glyphicon-barcode"></i> Print Label</a></td>
            </tr>
        @endforeach
    </table>
    <i class="fa fa-exclamation-circle text-danger" ></i> - Signifies a non standard size file
@stop

@section('javascript_bottom')
<script>
    function checkInPoster(fileid){
        //Run ajax call to notify backend that poster is checked in
        var fd = new FormData();
        fd.append("fileid",fileid);
        $.ajax({
            url: '{{ URL::route('printing.api.receivePoster') }}',
            type: 'POST',
            data: fd,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(data){
                //check for success data
                if(data.success == "true"){
                    //Action has been completed
                    highlightRow(fileid);
                }
                else if(data.error){
                    console.log("Your request could not be processed at this time");
                    console.error(data.error);
                }
            },
            error: function(data, textStatus){
                console.log(data);
                console.log(textStatus);
            }
        });
    }

    function highlightRow(fileid){
        $("#file-tr-"+fileid).addClass("success");
    }
</script>
@stop

