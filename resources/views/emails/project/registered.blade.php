@extends('emails.layout')
@section('TopSentence')
    Welcome to IPRO Manager
@stop
@section('content')

    <h3>Welcome to IPRO Manager {{$person->FirstName}},</h3>
    <p>You are receiving this email because you have been enrolled in {{$project->UID}}.</p>
    <p>The IPRO Program at IIT uses IPRO manager to upload and print your IPRO Day posters.
        To upload a poster login to the application at <a href=ipromanager.ideashop.iit.edu>ipromanager.ideashop.iit.edu</a> using your university login credentials. We will email you when your poster is ready for pickup in the Idea Shop.</p>

    <p>Thanks!</p>
    <p>-The IPRO Manager Robot</p>
@stop
