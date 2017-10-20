@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')
    @include("printing.printingNavigation")

    <table class="table table-condensed table-hover">
        <tr>
            <th>Project UID</th>
            <th>Awaiting User Approval ({{ $printReportTotals["awaitUserPosterApproval"] }})</th>
            <th>Awaiting Approval ({{ $printReportTotals["awaitAdminPosterApproval"] }})</th>
            <th>Awaiting Print ({{ $printReportTotals["awaitPrint"] }})</th>
            <th>Printed ({{ $printReportTotals["printed"] }})</th>
            <th>Awaiting Student Pickup ({{ $printReportTotals["awaitingPickup"] }})</th>
            <th>Picked Up ({{ $printReportTotals["pickedUp"] }})</th>
            <th>Total Printed ({{ $printReportTotals["totalPosters"] }})</th>
            <th>Rejected ({{ $printReportTotals["postersRejected"] }})</th>
        </tr>
        @foreach($projects as $project)
        <tr>
            <td><a href="{{ URL::route("printing.projectReport",$project->id) }}">{{ $project->UID }}</td>
            <td
            @if($project->awaitUserPosterApproval > 0)
                class="warning"
                    @endif
                    >{{ $project->awaitUserPosterApproval }}</td>
            <td>{{ $project->awaitAdminPosterApproval }}</td>
            <td>{{ $project->awaitPrint }}</td>
            <td>{{ $project->printed }}</td>
            <td>{{ $project->awaitingPickup }}</td>
            <td>{{ $project->pickedUp }}</td>
            <td
                    @if($project->totalPosters != 0)
                        class="success"
                    @endif
                    >{{ $project->totalPosters }}</td>
            <td
                    @if($project->postersRejected > 0)
                        class="danger"
                    @endif
            >{{ $project->postersRejected }}</td>

        </tr>
        @endforeach
    </table>
@stop
