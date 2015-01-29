@extends('layouts.master')
@include('layouts.dataTables')
@section('javascript_bottom')
<script>
$("#semester_selection").change(function(){
    var projectid = $("select#semester_selection option:selected").val();
   $(location).attr('href',"{{ URL::to('/admin/projects/')}}/"+projectid);
});
$(document).ready( function () {
    $('#projectListing').DataTable();
});
</script>
@stop
@section('content')

<div class="form-inline">
    <div class="form-group">`
<select class="form-control" id="semester_selection">
    @foreach ($semesters as $semester)
    <option value="{{ $semester->id }}">{{ $semester->Name }}</option>
    @endforeach
</select></div>
        <div class="form-group">
            <a class="btn btn-default" href="/admin/semesters">Semester Manager</a></div>
</div>

<h2 class="sub-header">Projects for {{ $activeSemester->Name }} <a href="/admin/projects/new" class="btn btn-default">New</a>  <a href="{{ URL::route('admin.projects.uploadCognos', $activeSemester->id) }}" class="btn btn-default">Upload a Cognos Report</a></h2>
<div class="table-responsive">
    <table class="table table-striped" id="projectListing">
        <thead>
            <tr>
                <th>UID</th>
                <th>Name</th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>UID</th>
                <th>Name</th>
                <th></th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($projects as $project)
            <tr>
                <td><a href="{{ URL::to('/admin/projects/overview/'.$project->id) }}">{{ $project->UID }}</a></td>
                <td>{{ $project->Name }}</td>
                <td>
                    <a class="btn btn-default" href="{{ URL::to('/admin/projects/edit/'.$project->id) }}">Edit</a>
                    <a class="btn btn-default" href="{{ URL::to('/admin/projects/delete/'.$project->id) }}">Delete</a>
                    <a class="btn btn-default" href="{{ URL::to('/admin/projects/enroll_users/'.$project->id) }}">Enroll Users</a>
                    <a class="btn btn-default" href="{{ URL::to('/admin/projects/overview/'.$project->id) }}">Overview</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
