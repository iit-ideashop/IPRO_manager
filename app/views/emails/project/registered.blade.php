@extends('emails.layout')
@section('TopSentence')
    Welcome to IPRO Manager
@stop
@section('content')

    <h3>Welcome to IPRO Manager {{$person->FirstName}},</h3>
    <p>You are receiving this email because you have been enrolled in {{$project->UID}}.</p>
    <p>The IPRO Program at IIT uses IPRO manager to track all student purchasing and project budgets.
        To purchase items or manage your budget login to the application at <a href="http://ipromanager.ideashop-iit.org">http://ipromanager.ideashop-iit.org</a> using your university login credentials. </p>

    <p>Thanks!</p>
    <p>-The IPRO Manager Robot</p>
@stop