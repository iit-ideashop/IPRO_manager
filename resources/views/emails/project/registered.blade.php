@extends('emails.layout')
@section('TopSentence')
    Welcome to IPRO Manager
@stop
@section('content')

    <h3>Welcome to IPRO Manager {{$person->FirstName}},</h3>
    <p>You are receiving this email because you have been enrolled in {{$project->UID}}.</p>
    <p>The IPRO Program at IIT uses IPRO Manager to track all student purchasing, project budgets, and poster submissions.
        To purchase items, manage your budget, or submit your poster for printing, login to the application at <a href="https://ipromanager.ideashop.iit.edu/">https://ipromanager.ideashop.iit.edu/</a> using your university login credentials.
    </p>

    <p>Thanks!</p>
    <p>-The IPRO Manager Robot</p>
@stop