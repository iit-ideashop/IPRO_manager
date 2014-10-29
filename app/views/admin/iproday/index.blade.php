@extends('layouts.master')
@include('layouts.dataTables')
@section('content')
<select name="iprodays">
    @foreach($iprodays as $iprod)
    <option>{{ $iprod->eventDate}}</option>
    @endforeach
</select>
<div class="table-responsive">
<table class="table table-striped" id="regListing">
    <thead>
        <th>RegID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Organization</th>
        <th>Attendee Type</th>
    </thead>
    @foreach($reg_data as $reg)
    <tr>
        <td>{{$reg->id}}</td>
        <td>{{$reg->firstName}}</td>
        <td>{{$reg->lastName}}</td>
        <td>{{$reg->organization}}</td>
        <td>{{$reg->type}}</td>
    </tr>
    @endforeach
    
</table>
</div>
@stop

@section('javascript_bottom')
<script>
$(document).ready( function () {
    $('#regListing').DataTable();
} );
</script>
@stop