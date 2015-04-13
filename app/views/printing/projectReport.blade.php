@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')
    @include("printing.printingNavigation")

    <table class="table table-condensed table-hover">
        <tr>
            <th>Project UID</th>
            <th>Awaiting User Approval</th>
            <th>Awaiting Approval</th>
            <th>Awaiting Print</th>
            <th>Printed</th>
            <th>Awaiting Student Pickup</th>
            <th>Picked Up</th>
            <th>Total Printed</th>
            <th>Rejected</th>
        </tr>
        @foreach($projects as $project)
        <tr>
            <td>{{ $project->UID }}</td>
            <td>{{ $project->awaitUserPosterApproval }}</td>
            <td>{{ $project->awaitAdminPosterApproval }}</td>
            <td>{{ $project->awaitPrint }}</td>
            <td>{{ $project->printed }}</td>
            <td>{{ $project->awaitingPickup }}</td>
            <td>{{ $project->pickedUp }}</td>
            <td
                    @if($project->totalPosters == 0)
                        class="success"
                    @endif
                    >{{ $project->totalPosters }}</td>
            <td class="danger">{{ $project->postersRejected }}</td>

        </tr>
        @endforeach
    </table>
@stop
