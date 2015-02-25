@extends('emails.layout')
@section('TopSentence')
    IPRO Manager daily reminders
@stop
@section('content')

    <h3>Hey there!</h3>
    <p>There are some items that require your attention in the IPRO Manager application</p>
    @if($checks['open_orders'])
    <p>There are open orders that need to be ordered via the App</p>
    @endif
    @if($checks['open_budget_request'])
        <p>There are budget requests that require admin approval</p>
    @endif
    @if($checks['LabTagItems'])
        <p>There are items marked for purchase by the prototyping lab. Please check on these items.</p>
    @endif
    <p>-The IPRO Manager Robot</p>
@stop