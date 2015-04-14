@extends('emails.layout')
@section('TopSentence')
    Prototyping Lab Action Required!!
@stop
@section('content')

    <h3>Hey there Print Staff!!</h3>
    <p>There seems to be some items awaiting print in your print queue.</p>
    <p>Login to <a href="{{ URL::route("printing") }}">IPRO Manager</a> to process these jobs</p>
    <p>Below is a listing of the jobs awaiting your attention</p>
    <table class="tableGray" width="100%" cellpadding="3px" cellspacing="3px">
        <tr>
            <td><b>Job</b></td>
            <td><b>Job Type</b></td>
            <td><b>Job Size</b></td>
            <td><b>Job Dimensions</b></td>
        </tr>
        @foreach($files as $file)
            <tr>
                <td><a href="{{ URL::route("printing.downloadfile", $file->id) }}">Job-{{ $file->id }}.pdf</a></td>
                <td>{{ $file->file_type }}</td>
                <td>{{ $file->size }}</td>
                <td>{{ $file->dimensions }}</td>
            </tr>
        @endforeach
    </table>
    <p>Thanks!</p>
    <p>-The IPRO Manager automated robot</p>
@stop