@extends('emails.layout')
@section('TopSentence')
    Prototyping Lab Action Required!!
@stop
@section('content')

    <h3>Hey there Prototyping Lab!!</h3>
    <p>While you were setting things on fire and building potato launchers some items have changed status and now require your attention!!
        (For three low low payments of 19.87)</p>
    <p>Below is a listing of the items found within the app that require your attention.</p>
    <table class="tableGray" width="100%" cellpadding="3px" cellspacing="3px">
        <tr>
            <td><b>Order</b></td>
            <td><b>Item</b></td>
            <td><b>Part Number</b></td>
            <td><b>Cost</b></td>
            <td><b>Quantity</b></td>
            <td><b>Shipping</b></td>
            <td><b>Total Cost</b></td>
        </tr>
        @foreach($items as $item)
            <tr>
                <td><a href="{{ URL::route('admin.order.manage',$item->OrderID) }}">ORD:{{$item->OrderID}}</a></td>
                <td><a href="{{ $item->Link}}">{{$item->Name}}</a></td>
                <td>{{ $item->PartNumber }}</td>
                <td>${{ number_format($item->Cost,2) }}</td>
                <td>{{ $item->Quantity }}</td>
                <td>${{ number_format($item->Shipping,2) }}</td>
                <td>${{ number_format($item->TotalCost,2) }}</td>
            </tr>
        @endforeach
    </table>
    <p>-The Ghost of IPRO Manager Ordering</p>
@stop