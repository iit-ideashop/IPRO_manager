@extends('emails.layout')
@section('content')

                            <h3>Hi {{ $person->FirstName }},</h3>

<p>Your order has been received! Orders are generally processed within 24-48 hours. We will email you again once items from your order have been purchased or if we have any questions regarding your order. Keep in mind this email is a confirmation that we received your submission, we will email you once again once the items have been physically purchased.</p>
<p>Here are the details of your order.</p>

<table class="tableBlue" width="100%" cellpadding="3px" cellspacing="3px">
<tr>
<td>
<p>Project: {{ $project->UID }}</p>
<p>Order Name: {{ $order->Description }}</p>
<p>Order Total: ${{ number_format($order->OrderTotal,2)}}</p>
<p>Order Placed: {{ date('D F jS Y, g:i a',strtotime($order->created_at)) }}</p>
</td>
</tr>
</table>
<br>
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