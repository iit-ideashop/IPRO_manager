@extends('emails.layout')
@section('TopSentence')
    Welcome to Armour R&D - IPRO Manager
@stop
@section('content')

    <h3>Welcome to Armour R&D - IPRO Manager {{$person->FirstName}},</h3>
    <p>You are receiving this email because you have been enrolled in {{$project->UID}}.</p>
    <p>Armour R&D at IIT will be using IPRO manager to print student posters this semester.
        Login at <a href="http://armour.ideashop-iit.org">http://armour.ideashop-iit.org</a> using your university login credentials. </p>

    <p>Thanks!</p>
    <p>-The IPRO Manager Robot</p>
@stop