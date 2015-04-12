@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')
    @include("printing.printingNavigation")

    <table>
        <tr>
            <th>Project UID</th>
            <th>Prints</th>
        </tr>
        @foreach($projects as $project)
        <tr>
            <td>{{ $project->UID }}</td>
            <td>{{ $project->postersSubmitted }}</td>
        </tr>
        @endforeach
    </table>
@stop
