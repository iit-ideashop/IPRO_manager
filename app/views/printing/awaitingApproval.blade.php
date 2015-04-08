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
            <tr id="file-tr-{{ $file->id }}" filename="{{ $file->original_filename }}">
                <td><a href="{{URL::route("printing.viewfile", $file->id)}}" target="_blank">{{ $file->original_filename }}</a>
                    @if($file->override)
                        <i class="fa fa-exclamation-circle text-danger" ></i>
                    @endif
                </td>
                <td>{{ $file->file_type }}</td>
                <td>{{ User::getFullNameWithId($file->UserID) }}</td>
                <td>{{ Project::getProjectUID($file->ProjectID) }}</td>
                <td>{{ $file->dimensions }}</td>
                <td id="file-td-actions-{{ $file->id }}">
                    <button class="btn btn-danger" id="denyFile-{{$file->id}}" onclick="showDenyModal({{ $file->id }});">Deny</button>
                    <button onclick="approveFile('{{ $file->id }}');" id="approveFile-{{$file->id}}" class="btn btn-success">Approve</button>
                </td>
            </tr>
        @endforeach
    </table>
    <i class="fa fa-exclamation-circle text-danger" ></i> - Signifies a non standard size file

    <!-- Deny modal -->
    <div class="modal fade" id="denyModal" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="denyModalLabel">Reject a file submission</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file-rejecting">Rejected File</label>
                        <input type="text" class="form-control" id="file-rejecting" disabled="disabled">
                    </div>
                    <div class="form-group">
                        <label for="reject-reason">Reason for rejection</label>
                        <textarea class="form-control" rows="3" id="reject-reason"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" id="modal-reject-button" class="btn btn-danger" onclick="">Reject</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript_bottom')
<script>
    function approveFile(file_id){
        //disable the deny file button
        $("#denyFile-"+file_id).attr("disabled","disabled");
        $("#approveFile-"+file_id).html('<i class="fa fa-spin fa-cog"></i> Approving..');
        setInterval(function(){
            removeRow(file_id);
        },1000);
    }

    function showDenyModal(file_id){
        //Prepare the modal with data
        var filename = $("#file-tr-"+file_id).attr("filename");
        $("#file-rejecting").val(filename);
        $("#modal-reject-button").attr("onclick","denyFile("+file_id+")");
        $("#reject-reason").val("");
        $("#denyModal").modal("show");
    }

    function denyFile(file_id){
        $("#denyModal").modal("hide");
        $("#approveFile-"+file_id).attr("disabled","disabled");
        $("#denyFile-"+file_id).html('<i class="fa fa-spin fa-cog"></i> Rejecting..');
        setTimeout(function(){
            removeRow(file_id);
        },1000);
    }


    function removeRow(file_id){
        $("#file-td-actions-"+file_id).html('<i class="fa fa-thumbs-o-up"></i> Completed');
        setTimeout(function(){
            $("#file-tr-"+file_id).fadeOut(2000);
        },1500);
    }


</script>
@stop

