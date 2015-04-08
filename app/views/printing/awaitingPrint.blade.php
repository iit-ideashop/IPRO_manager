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
            <tr id="printed-td-1">
                <td><a href="{{URL::route("printing.downloadfile", $file->id)}}">Job-{{ $file->id }}.pdf</a>
                    @if($file->override)
                        <i class="fa fa-exclamation-circle text-danger" ></i>
                    @endif
                </td>
                <td>{{ $file->size }}</td>
                <td>{{ $file->dimensions }}</td>
                <td>{{ $file->file_type }}</td>
                <td>
                    <button class="btn btn-primary" id="printed-button-1" onclick="completePrint('1')"><i class="fa fa-print"></i> Printed</button>
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
        setInterval(function(){
            removeRow(jobid);
        },1000)
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

