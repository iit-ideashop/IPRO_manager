@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')
    @include("printing.printingNavigation")
    <table class="table table-striped table-hover">
        <tr>
            <th>Job</th>
            <th>Size</th>
            <th>Dimensions</th>
            <th>Type</th>
            <th> </th>
        </tr>
        @foreach($files as $file)
            <tr id="printed-td-{{ $file->id }}">
                <td><a href="{{URL::route("printing.downloadfile",array("fileid"=>$file->id, "naming"=>"job")) }}">Job-{{ $file->id }}.pdf</a>
                    @if($file->override)
                        <i class="fa fa-exclamation-circle text-danger" ></i>
                    @endif
                </td>
                <td>{{ $file->size }}</td>
                <td>{{ $file->dimensions }}</td>
                <td>{{ $file->file_type }}</td>
                <td>
                    <button class="btn btn-primary" id="printed-button-{{ $file->id }}" onclick="completePrint('{{ $file->id }}')"><i class="fa fa-print"></i> Printed</button>
                </td>
            </tr>
        @endforeach
    </table>
    <i class="fa fa-exclamation-circle text-danger" ></i> - Signifies a non standard size file
@stop

@section('javascript_bottom')
<script>
    function completePrint(jobid){
        $("#printed-button-"+jobid).html('<i class="fa fa-print fa-spin"></i> Printed');
        callPrintAPI(jobid,"Printed");
    }

    function callPrintAPI(fileid,action){
        var fd = new FormData();
        fd.append("fileid",fileid);
        fd.append("action",action);
        $.ajax({
            url: '{{ URL::route('printing.api.markPrinted') }}',
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
                    removeRow(fileid);
                }
                else if(data.error){
                    console.log(data);
                    console.log("Your request could not be processed at this time");
                }
            },
            error: function(data, textStatus){
                console.log(data);
                console.log(textStatus);
            }
        });
    }

    function removeRow(jobid){
        $("#printed-button-"+jobid).html('<i class="fa  fa-thumbs-o-up"></i> Printed!!!');
        $("#printed-button-"+jobid).removeClass("btn-primary");
        $("#printed-button-"+jobid).addClass("btn-success");
        setInterval(function(){
            $("#printed-td-"+jobid).fadeOut(1500);
        },1000);
    }
</script>
@stop

