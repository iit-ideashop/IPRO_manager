@extends('layouts.master')
@include('layouts.fontawesome')
@include('layouts.treetable')
@section('content')
    <h2 class="sub-header">Budget Report</h2>
    <table id="budget-report" class="treetable">
        <thead>
        <tr>
            <th>UID</th>
            <th>Name</th>
            <th>Enrolled Students</th>
            <th>Teambuilding Budget</th>
            <th>Money Allocated</th>
            <th>Money Spent</th>
        </tr>
        </thead>
        <tbody>
        @foreach($projects as $project)
            @if($project->ParentClass == NULL)
            <tr data-tt-id="{{ $project->id }}" id="project-{{ $project->id }}"
            @if($project->ParentClass != NULL)
                data-tt-parent-id="{{ $project->ParentClass }}"
                    @endif>
                <td>{{ $project->UID }}</td>
                <td>{{ $project->Name }}</td>
                <td>{{ $project->enrollment }}</td>
                <td>
                    @if($project->ParentClass == NULL)
                        ${{$project->teambuilding}}
                    @else
                        -
                    @endif
                </td>
                <td>${{ $project->moneyAllocated }}</td>
                <td>${{ $project->moneySpent }}</td>
            </tr>
            @endif
        @endforeach
        </tbody>
    </table>
@stop
@section("javascript_bottom")
    <script>

        @foreach($projects as $project)
            @if($project->ParentClass != NULL)
                $("#project-"+{{ $project->ParentClass }}).after('<tr data-tt-id="{{ $project->id }}" data-tt-parent-id="{{$project->ParentClass}}" id="project-{{ $project->id }}">'+
                '<td>{{ $project->UID }}</td>' +
                '<td>{{ $project->Name }}</td>' +
                '<td>{{ $project->enrollment }}</td>' +
                '<td> - </td>' +
                '<td>${{ $project->moneyAllocated }}</td>' +
                '<td>${{ $project->moneySpent }}</td>' +
                '</tr>');
            @endif
        @endforeach
         $("#budget-report").treetable({ expandable: true });
    </script>
@stop