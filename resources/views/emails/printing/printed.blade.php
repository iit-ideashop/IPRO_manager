@extends('emails.layout')
@section('TopSentence')
    IPRO Poster Submission
@stop
@section('content')

    <h3>Hey there {{$person->FirstName}}!</h3>
    <p>Our printer has printed your file and it is ready for pickup at the Idea Shop. The Idea Shop is located in the basement of the IIT Tower - Suite BC4-1. Take the elevators to the basement and turn right, can't miss it.</p>
    <p>Please be sure to bring an ID to pickup your poster. We will have stations in the Idea Shop with the supplies necessary to mount your poster to foam core.</p>
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
                <p>Project: {{ Project::getProjectUID($fileSubmission->ProjectID) }}</p>
                <p>Upload Time: {{ date('D F jS Y, g:i a',strtotime($fileSubmission->created_at)) }}</p>
            </td>
        </tr>
    </table>
    <p>Thanks!</p>
    <p>-The IPRO Manager Robot</p>
@stop
