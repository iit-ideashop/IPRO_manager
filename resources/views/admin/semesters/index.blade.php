@extends('layouts.master')
@include('layouts.dataTables')
@section('content')

<h1 class="page-header">Semesters  <a class="btn btn-default" href="{{ URL::to('/admin/semesters/new'); }}">New</a></h1>
<div class="table-responsive">
    <table class="table table-striped" id="semesterListing">
        <thead>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Semester Start</th>
                <th>Semester End</th>
                <th>Created By</th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>Name</th>
                <th>Semester Start</th>
                <th>Semester End</th>
                <th>Created By</th>
                <th></th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($semesters as $semester)
            <tr>
                <td>{{ $semester->id }}</th>
                <td>{{ $semester->Name }}
                    @if($semester->Active)
                    [Active]
                    @endif

                </td>
                <td>{{ date('D F jS, Y',strtotime($semester->ActiveStart)) }}</td>
                <td>{{ date('D F jS, Y',strtotime($semester->ActiveEnd)) }}</td>
                <td>{{ User::getFullNameWithId($semester->modifiedBy) }}</td>
                <td><a class="btn btn-default" href="{{ URL::to('/admin/semesters/edit/'.$semester->id)}}">Edit<a> 
                    
                    
                    @if(!$semester->Active)
                    <a class="btn btn-default" data-toggle="modal" data-target="#Delete{{ $semester->id }}Modal">Delete<a> 
                        <a class="btn btn-default" href="{{ URL::to('/admin/semesters/makeActive/'.$semester->id) }}">Make Active<a>
                    @endif
                </td>
            </tr>
        @endforeach
</tbody>
</table>
</div>
{{-- MODAL DATA --}}
@foreach($semesters as $semester)
@if(!$semester->Active)
<div class="modal fade" id="Delete{{ $semester->id}}Modal" tabindex="-1" role="dialog" aria-labelledby="Delete{{ $semester->id}}ModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Delete "{{ $semester->Name }}" Semester</h4>
      </div>
      <div class="modal-body">
          <h1>YOU ARE ABOUT TO DELETE</h1>
          <h1>"{{ $semester->Name }}" Semester</h1>
          <a class="btn btn-danger" href="{{ URL::to('/admin/semesters/delete/'.$semester->id)}}">CLICK HERE TO DELETE</a>
          <p>If this semester has any data associated with it such as projects, accounts, budgets, etc. the delete will fail and you will need to delete the associated data before deleting the semester</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel Semester Deletion</button>
        
      </div>
    </div>
  </div>
</div>
@endif
@endforeach

@stop
                                            
@section('javascript_bottom')
<script>
$(document).ready( function () {
    $('#semesterListing').DataTable({
         "order": [[ 0, "desc" ]],
         "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
            }
        ]
    } );
} );
</script>
@stop

