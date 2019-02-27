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
            <th></th>
        </tr>
        @foreach($students as $student)
            <tr>
                <td><input type="checkbox" name="remove-{{$student->id}}"></td>
                <td>{{$student->FirstName}} {{$student->LastName}}</td>
                <td>{{$student->Email}}</td>
                <td>
                    {{Form::open(['action' => ['AdminProjectController@doRemoveUser', $project->id, $student->id], 'method' => 'delete' ])}}
                    <button type="submit" class="btn btn-xs">Remove</button>
                    {{Form::close()}}
                </td>
            </tr>
        @endforeach
    </table>

    <div id="newStudentInput"></div>

    <form id="enrollUsersForm" action="{{ action('AdminProjectController@doSingleEnroll', ['id' => $project->id]) }}" method="post">
        {{ csrf_field() }}

        <div class="form-group">
            {{ Form::label('cwid','CWID (A-Number)', array('class'=>'control-label')) }}
            {{ Form::text('cwid',null,array('class'=>'form-control','placeholder'=>'A20312345')) }}
        </div>

        <div class="form-group">
            {{ Form::label('email','Email', array('class'=>'control-label')) }}
            {{ Form::text('email',null,array('class'=>'form-control','placeholder'=>'jsmith@hawk.iit.edu')) }}
        </div>

        <input type="submit" class="btn" value="Submit">
    </form>


@stop
@section('javascript_bottom')
<script>
    /*
    var data = [["", ""]];

    var container = $("#newStudentInput");
    var table = container.handsontable({
        data: data,
        colHeaders: ["CWID", "Email"],
        colWidths: [100, 250],
        manualColumnResize: true,
        manualRowResize: true,
        preventOverflow: 'horizontal',
        minSpareRows: 1
    });

    $("#enrollUsersForm").css("display", "none");
    var submitButton = document.createElement("input");
    submitButton.type = "submit";
    submitButton.classList = "btn";
    submitButton.value = "Submit";
    submitButton.onclick = function() {
        var data = table.data().handsontable.getData();
        if (data.filter(function(a) { return a[0] && a[1] }).length == 0) { return; }
        var form = document.createElement("form");
        form.action = "{{ action('AdminProjectController@doMultiEnroll', ['id' => $project->id]) }}";
        form.method = "POST";
        var input = document.createElement("input");
        input.name = "data";
        input.value = JSON.stringify(data.map(function (a) { return { "cwid": a[0], "email": a[1] }; }));
        form.appendChild(input);
        var csrf = document.createElement("input");
        csrf.name = "_token";
        csrf.value = "{{ csrf_token() }}";
        form.appendChild(csrf);
        form.submit();
    };
    container.after(submitButton);
    */
</script>
@stop
