@extends('emails.layout')
@section('TopSentence')
    IPRO Poster Submission
@stop
@section('content')

    <h3>Hey there {{$person->FirstName}}!</h3>
    <p>Someone from your team has picked up your poster. Please contact them if you need the poster.</p>
    <p>Please find a reference to the file below.</p>
    <table class="tableBlue" width="100%" cellpadding="3px" cellspacing="3px">
        <tr>
            <td>
                <p>File Name: {{ $fileSubmission->original_filename }}</p>
                <p>File Type: {{ $fileSubmission->file_type }}</p>
                <p>File Size: {{ $fileSubmission->size }}</p>
                <p>File Dimensions: {{ $fileSubmission->dimensions }}</p>
                @if($fileSubmission->override)
                    <p>Your file submission did not match our file dimension standards. Your file may have been altered by the Printer.</p>
                @endif
                <p>Uploader: {{ User::getFullNameWithId($fileSubmission->UserID) }}</p>
                <p>Picked up by: {{User::getFullNameWithId($fileSubmission->pickup_UserID)}}</p>
                <p>Project: {{ Project::getProjectUID($fileSubmission->ProjectID) }}</p>
                <p>Upload Time: {{ date('D F jS Y, g:i a',strtotime($fileSubmission->created_at)) }}</p>
            </td>
        </tr>
    </table>
    <p>Thanks!</p>
    <p>-The IPRO Manager Robot</p>
@stop