@extends('emails.layout')
@section('content')

    <h3>Hey {{ $person->FirstName }},</h3>

    <p>
        We've received your order and need additional information to determine whether or not it can be purchased using
        IPRO funds. Please send us an email at <a href="mailt:ipro@iit.edu">ipro@iit.edu</a> and include your order
        number #{{ $order->id }}.
    </p>

    <table class="tableGray" width="100%" cellpadding="3px" cellspacing="3px">
        <tr>
            <td><b>Item Name</b></td>
            <td><b>Link</b></td>
            <td><b>Part Number</b></td>
            <td><b>Cost</b></td>
            <td><b>Quantity</b></td>
            <td><b>Shipping</b></td>
            <td><b>Total Cost</b></td>
        </tr>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->Name }}</td>
                <td><a href="{{ $item->Link}}">Link</a></td>
                <td>{{ $item->PartNumber }}</td>
                <td>${{ number_format($item->Cost,2) }}</td>
                <td>{{ $item->Quantity }}</td>
                <td>${{ number_format($item->Shipping,2) }}</td>
                <td>${{ number_format($item->TotalCost,2) }}</td>
            </tr>
        @endforeach

    </table>
    <p>-The IPRO Ordering Robot</p>
@stop