@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')
    @include("printing.printingNavigation")
    <h3>{{ $project->UID }} Print Submissions</h3>
    <table class="table table-condensed table-hover">
        <tr>
            <th>File Name</th>
            <th>Job ID</th>
            <th>File Type</th>
            <th>File Size</th>
            <th>File Dimensions</th>
            <th>Uploader</th>
            <th>Barcode #</th>
            <th>Status</th>
        </tr>
        @foreach($printSubmissions as $printSubmission)
        <tr>
            <td><a href="{{URL::route("printing.viewfile",array("fileid"=>$printSubmission->id))}}" target="_blank">{{ $printSubmission->original_filename }}</a></td>
            <td>Job-{{ $printSubmission->id }}</td>
            <td>{{ $printSubmission->file_type }}</td>
            <td>{{ $printSubmission->size }}</td>
            <td>{{ $printSubmission->dimensions }}</td>
            <td>{{ User::getFullNameWithId($printSubmission->UserID) }}</td>
            <td>
                @if($printSubmission->barcode != NULL)
                    {{ $printSubmission->barcode }}
                @else
                    Not Generated
                @endif
            </td>
            <td>{{ $printSubmission->getStatus() }}
            @if(($printSubmission->status == 6) &&($printSubmission->pickup_UserID != NULL))
                : {{ User::getFullNameWithId($printSubmission->pickup_UserID) }}
            @endif
            @if(($printSubmission->status == 7) &&($printSubmission->reject_comments != NULL))
                <br>{{ nl2br($printSubmission->reject_comments) }}
            @endif</td>
            <td></td>
        </tr>
        @endforeach
    </table>
@stop
