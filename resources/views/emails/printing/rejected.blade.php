@extends('emails.layout')
@section('TopSentence')
    IPRO Poster Submission
@stop
@section('content')

    <h3>Sorry {{$person->FirstName}},</h3>
    <p>Your PDF submission was rejected by one of our admins. Here is the reason for your submission being rejected:</p>
    <p>{{ nl2br($fileSubmission->reject_comments) }}</p>
    <p>The details of your submission can be found below. Please resubmit your file once you make the appropriate corrections.</p>
    <table class="tableBlue" width="100%" cellpadding="3px" cellspacing="3px">
        <tr>
            <td>
                <p>File Name: {{ $fileSubmission->original_filename }}</p>
                <p>File Type: {{ $fileSubmission->file_type }}</p>
                <p>File Size: {{ $fileSubmission->size }}</p>
                <p>File Dimensions: {{ $fileSubmission->dimensions }}</p>
                @if($fileSubmission->override)
                    <p>Your file submission did not match our file dimension standards. Your file may be rejected for not following the standards outlined by the Printer.</p>
                @endif
                <p>Uploader: {{ User::getFullNameWithId($fileSubmission->UserID) }}</p>
                <p>Project: {{ Project::getProjectUID($fileSubmission->ProjectID) }}</p>
                <p>Upload Time: {{ date('D F jS Y, g:i a',strtotime($fileSubmission->created_at)) }}</p>
            </td>
        </tr>
    </table>
    <p>Thanks!</p>
    <p>-The IPRO Manager Robot</p>
@stop