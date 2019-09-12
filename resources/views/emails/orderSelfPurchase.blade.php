@extends('emails.layout')
@section('content')

    <h3>Hey {{ $person->FirstName }},</h3>

    <p>
        Since the item you requested is not from one of our regular vendors, you are approved to purchase the item
        yourself and submit the receipt for reimbursement. You can find a guide for requesting reimbursement
        <a href="https://ipro.iit.edu/wp-content/uploads/2019/05/SPRING-2019-Reference-Guide-3.pdf">here</a>.
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