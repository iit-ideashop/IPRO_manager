@extends('layouts.master')
@include('layouts.fontawesome')
@include('layouts.autocomplete')
@section('content')
    @include("admin.iproday.iprodayNavigation")
<h3>Similar Votes placed</h3>
    <table class="table table-striped table-hover">
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>ID #</th>
            <th>ID Type</th>
            <th>Project</th>
            <th>Vote time</th>
        </tr>
        @foreach($userDuplicateCheck as $duplicate)
        <tr>
            <td>{{ $duplicate->FirstName }}</td>
            <td>{{ $duplicate->LastName }}</td>
            <td>{{ $duplicate->idnumber }}</td>
            <td>{{ $duplicate->IDType }}</td>
            <td>{{ Project::getProjectUID($duplicate->ProjectID) }}</td>
            <td>{{ date('D F jS Y, g:i a',strtotime($duplicate->updated_at)) }}</td>
        </tr>
            @endforeach
    </table>
    <h3>Class enrollment</h3>
    <table class="table table-striped table-hover">
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
        </tr>
        @foreach($enrollment as $enrolled)
            <tr>
                <td>{{ $enrolled->FirstName }}</td>
                <td>{{ $enrolled->LastName }}</td>
            </tr>
        @endforeach
    </table>
    {{ Form::open(array("route"=>"admin.iproday.peoplesChoice.confirmAdd")) }}
    {{ Form::hidden("firstName", $firstName) }}
    {{ Form::hidden("lastName",$lastName) }}
    {{ Form::hidden("idnumber", $idnumber) }}
    {{ Form::hidden("idtype",$idtype) }}
    {{ Form::hidden("projectid",$projectid) }}

    <div class="pull-right">
        <a href="{{ URL::route("admin.iproday.peopleschoice") }}" class="btn btn-danger">Reject Vote</a>
        {{ Form::submit("Accept Vote",array("class"=>"btn btn-primary")) }}
    </div>
    {{ Form::close() }}
@stop

@section('javascript_bottom')
<script>

</script>
@stop



