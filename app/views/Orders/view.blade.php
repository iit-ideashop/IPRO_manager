@extends('layouts.master')
@include('layouts.dataTables')
@section('content')

<h1 class="page-header">Order Overview - {{ $order->Description }} 


</h1>
<div class="panel panel-default">
    <div class="panel-heading">Order Information</div>
  <div class="panel-body">
      <div class="row">
          <div class="col-sm-6">User: {{ $order_user->FirstName }} {{ $order_user->LastName }}</div>
          <div class="col-sm-6">Total: ${{ number_format($order->OrderTotal,2) }}</div>
      </div>
      <div class="row">
          <div class="col-sm-6">Email: {{ $order_user->Email}}</div>
          <div class="col-sm-6">Status: {{$order->getStatus() }}</div>
      </div>
    <div class="row">
          <div class="col-sm-6">Project: {{ $project->UID }}</div>
          <div class="col-sm-6">Created: {{ date('D F jS Y, g:i a',strtotime($order->created_at)) }}</div>
      </div>
      
      <div class="row">
          <div class="col-sm-6">Project Name: {{ $project->Name }}</div>
          <div class="col-sm-6">Last Modified: {{ date('D F jS Y, g:i a',strtotime($order->updated_at)) }}</div>
      </div>
      @if($order->Phone != '')
      <div class="row">
          <div class="col-sm-6">Contact: {{ $order->Phone }}</div>
          <div class="col-sm-6"></div>
      </div>
      @endif
      
  </div>
</div>



<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">Order Items</div>
 <div class="panel-body">
 <div class="table-responsive">
    <table class="table table-striped" id="itemListing">
        <thead>
            <tr>
                
                <th>id</th>
                <th></th>
                <th>Name</th>
                <th>Link</th>
                <th>Part Number</th>
                <th>Justification</th>
                <th>Cost</th>
                <th>Quantity</th>
                <th>Total Cost</th>
                <th>Returning</th>
                <th>Status</th>
                <th>Updated</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th></th>
                <th>Name</th>
                <th>Link</th>
                <th>Part Number</th>
                <th>Justification</th>
                <th>Cost</th>
                <th>Quantity</th>
                <th>Total Cost</th>
                <th>Returning</th>
                <th>Status</th>
                <th>Updated</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td><input type="checkbox" name="{{ $item->Name}}" id="item-{{ $item->id }}" value="{{ $item->id}}"></th>
                <td><a href="{{$item->Link}}" target="_blank">{{ $item->Name}}</a></td>
                <td>{{ $item->Link }}</td>
                <td>{{ $item->PartNumber }}</td>
                <td>{{ $item->Justification }}</td>
                <td>${{ number_format($item->Cost,2) }}</td>
                <td>{{ $item->Quantity}}</td>
                <td>${{ number_format($item->TotalCost,2) }}</td>
                @if($item->Returning)
                <td>Yes</td>
                @else
                <td>No</td>
                @endif
                <td>{{ $item->getStatus() }}</td>
                <td>{{ date('D F jS Y, g:i a',strtotime($item->updated_at))}}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
 </div>
 </div>
</div>
@stop
                                            
@section('javascript_bottom')
<script>
$(document).ready( function () {
    $('#itemListing').DataTable({
         "order": [[ 0, "asc" ]],
         "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
            },
            {
                "targets": [ 3 ],
                "visible": false,
            }
            
        ]
    } );
} );
</script>
@stop

