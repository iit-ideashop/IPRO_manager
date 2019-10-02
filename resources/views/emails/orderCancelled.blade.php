@extends('emails.layout')
@section('content')
<table class="container">
  <tr>
    <td>

      <table class="row">
        <tr>
          <td class="wrapper last">

            <table class="twelve columns">
              <tr>
                <td>
                  <p class="lead RobotText">Hey {{ $person->FirstName }}>
                    It seems that certain items from your order have been denied. These items are
                    listed below. If you weren't the one who requested this and would still like to purchase these
                    items, please <a href="mailto:ipro@iit.edu">contact us</a> and include your order
                    number #{{ $order->id }}.
                  </p>
                 </td>
                <td class="expander"></td>
              </tr>
              @if (!empty($comment))
                <tr>
                  <td>
                    <b>Comment from IPRO: </b>{{ $comment }}
                  </td>
                </tr>
              @endif
            </table>

          </td>
        </tr>
      </table>

      <table class="row callout">
        <tr>
          <td class="wrapper last">

            <table class="twelve columns">
              <tr>
                <td class="panel">
                  <p>Order Name: {{ $order->Description }}</p>
                  <p>Order Total: ${{ number_format($order->OrderTotal,2)}}</p>
                  <p>Order Time: {{ date('D F jS Y, g:i a',strtotime($order->created_at)) }}</p>
                </td>

                <td class="expander"></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>


        <table class="row footer">
        <tr>
          <td class="wrapper last">

            <table class="twelve columns">
              <tr>
                <td class="left-text-pad">
                  <table width="100%">
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

                </td>
                <td class="expander"></td>
              </tr>
            </table>
          </td></tr></table>
        @stop