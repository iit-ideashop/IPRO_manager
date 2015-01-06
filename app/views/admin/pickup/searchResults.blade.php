@extends('layouts.master')
@include('layouts.dataTables')
@section('content')
<h1 class="page-header">Search Results</h1>
    <table class="table table-bordered">
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th></th>
        </tr>
        @foreach($students as $student)
        <tr>
            <td>{{$student->FirstName}}</td>
            <td>{{$student->LastName}}</td>
            <td>{{$student->Email}}</td>
            <td><a class="btn btn-default" href="{{URL::action('AdminPickupController@viewItems',array('userid' => $student->id)) }}">Select</a></td>
        </tr>
        @endforeach
    </table>
@stop