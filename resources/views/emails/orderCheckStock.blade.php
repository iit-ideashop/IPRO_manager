@extends('emails.layout')
@section('content')

    <h3>Hey {{ $person->FirstName }},</h3>

    <p>
        We believe that the Idea Shop may have the below item or a comparable substitute. Please
        check with Idea Shop staff. If they have this item you can cancel this order. If they do
        not, please email us at <a href="mailto:ipro@iit.edu">ipro@iit.edu</a> and we can
        proceed with the order.
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