@extends('emails.layout')
@section('content')

    <h3>Good News {{ $person->FirstName }},</h3>

    <p>
        My systems have been updated! Some items from your order have arrived and are ready for pickup at the IPRO
        concierge
        desk on the south side of the Kaplan Institute.
        Please be sure to bring a valid ID to pickup these items. I have listed the items below.
    </p>

    @if (!empty($comment))
        <p>
            <b>Comment from IPRO: </b> {{ $comment }}
        </p>
    @endif

    <table class="tableBlue" width="100%" cellpadding="3px" cellspacing="3px">
        <tr>
            <td>
                <p>Order Name: {{ $order->Description }}</p>
                <p>Order Total: ${{ number_format($order->OrderTotal,2)}}</p>
                <p>Order Placed: {{ date('D F jS Y, g:i a',strtotime($order->created_at)) }}</p>
            </td>
        </tr>
    </table>
    <br/>
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