@extends('layouts.master')
@include('layouts.dataTables')

@section('content')
    @include('project.projectNavigation')
   <table class="table table-striped table-condensed" id="roster">
       <thead>
       <tr>
           <th>First Name</th>
           <th>Last Name</th>
           <th>Email</th>
       </tr>
       </thead>
       <tbody>
       @foreach($students as $student)
            <tr>
                <td>{{$student->FirstName}}</td>
                <td>{{$student->LastName}}</td>
                <td><a href="mailto:{{$student->Email}}">{{$student->Email}}</a></td>
            </tr>
       @endforeach
       </tbody>
   </table>

@stop

@section('javascript_bottom')
    <script>
        $('#roster').DataTable({
            "order": [[1, "asc"]],
            "lengthMenu": [25, 50, 100]

        });
    </script>
@stop
