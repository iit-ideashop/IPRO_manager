@extends('emails.layout')
@section('content')

                            <h3>Hey {{ $person->FirstName }},</h3>

    <p>Thanks for picking up your items! </p>
    <p>Below are the items you picked up from us.</p>


<table class="tableGray" width="100%" cellpadding="3px" cellspacing="3px">
 <tr>
  <td><b>Item Name</b></td>
  <td><b>Link</b></td>
  <td><b>Part Number</b></td>
  <td><b>Cost</b></td>
  <td><b>Quantity</b></td>
  <td><b>Total Cost</b></td>
 </tr>
@foreach($items as $item)
<tr>
    <td>{{ $item->Name }}</td>
    <td><a href="{{ $item->Link}}">Link</a></td>
    <td>{{ $item->PartNumber }}</td>
    <td>${{ number_format($item->Cost,2) }}</td>
    <td>{{ $item->Quantity }}</td>
    <td>${{ number_format($item->TotalCost,2) }}</td>
</tr>
@endforeach

</table>
<p>-The IPRO Ordering Robot</p>
@stop