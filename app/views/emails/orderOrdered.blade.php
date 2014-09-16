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
                <h1>Hi {{ $person->FirstName }} {{ $person->LastName}}!</h1>
                                                    <p class="lead">Your order has been received and will be processed by our staff within the next 24 hours.</p>
                                                    <p>Here are the details of your order.</p>                         </td>
              <td class="expander"></td>
            </tr>
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

              </td>
              <td class="expander"></td>
            </tr>
          </table>
        </td></tr></table>
@stop