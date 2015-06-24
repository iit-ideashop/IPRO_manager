@extends('emails.layout')
@section('TopSentence')
    Armour R&D - IPRO Manager Update
@stop
@section('content')
    <h3>Hello {{$person->FirstName}},</h3>
    <p>You are receiving this email today because you have dropped {{$project->UID}}.</p>
    <p>Your access to this project in Armour R&D - IPRO Manager has been removed. If this is a mistake please contact us at <a href="mailto:ipro@iit.edu">ipro@iit.edu</a> so that we can investigate and correct our records.</p>
    <p>Thanks!</p>
    <p>-The IPRO Manager Robot</p>
@stop