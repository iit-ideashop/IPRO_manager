@extends('emails.layout')
@section('TopSentence')
    Welcome to IPRO Manager
@stop
@section('content')

    <h3>Hey there!</h3>
    <p>An advanced script was recently run by {{$person->FirstName}} {{$person->LastName}} Below is the log dump.</p>
    <p>IPRO Manager – {{$reportType}} --  {{$extraData}} -- <?php echo date("n/j/Y g:i a",time()); ?> </p>
    <p>-- Begin Log data --</p>
        @foreach($logdata as $log)
        <p>{{$log}}</p>
        @endforeach
    <p>-- End Log data --</p>
    <p>-The IPRO Manager Robot</p>
@stop