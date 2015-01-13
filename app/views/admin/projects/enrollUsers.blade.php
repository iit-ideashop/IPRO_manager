@extends('layouts.master')
@include('layouts.handsontable')
@section('content')
    <h1>Enrolling users for {{$project->UID}}</h1>
    <h3>Current enrollment for {{$project->UID}}</h3>
    <table class="table table-bordered">
        <tr>
            <th>-</th>
            <th>Name</th>
            <th>Email Address</th>
        </tr>
        @foreach($students as $student)
            <tr>
                <td><input type="checkbox"></td>
                <td>{{$student->FirstName}} {{$student->LastName}}</td>
                <td>{{$student->Email}}</td>
            </tr>
        @endforeach
    </table>

    <div id="example"></div>




@stop
@section('javascript_bottom')
<script>
    var data = [
        ["First Name", "Last Name", "Email", "CWID"]
    ];

    var container = $("#example");
    container.handsontable({
        data: data,
        colHeaders: true,
        stretchH: 'all',
        width: 1000,
        height: 500,
        minSpareRows: 1
    });
</script>
@stop