@extends('layouts.master')
@include('layouts.dataTables')
@section('content')

<h1 class="page-header">Orders
<a href="{{ URL::route('admin.order.pickup') }}" class="btn btn-default">Pickup</a>


</h1>
<div class="panel-group" id="filter">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#filter" href="#filters">
          Order Filters
        </a>
      </h4>
    </div>
    <div id="filters" class="panel-collapse collapse">
      <div class="panel-body">
      Fitlers would go here.
      <ul>
          <li>Filter by IPRO</li>
          <li>Filter by Status</li>
          <li>Show all for this semester</li>
          <li>Show all for a previous semester</li>
          <li>Filter by person</li>
      </ul>
      </div>
    </div>
  </div>
</div>






<div class="table-responsive">
    <table class="table table-striped" id="orderListing">
        <thead>
            <tr>
                <th>Order Number</th>
                <th>IPRO</th>
                <th>Description</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Order Number</th>
                <th>IPRO</th>
                <th>Description</th>
                <th>Status</th>
                <th></th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ Project::getProjectUID($order->ClassID) }}</th>
                <td>{{ $order->Description }}</td>
                <td>{{ $order->getStatus() }}</td>
                <td><a class="btn btn-default" href="{{ URL::to('/admin/orders/'.$order->id)}}">Manage</a>
                    </td>
            </tr>
        @endforeach
</tbody>
</table>
</div>

@stop
                                            
@section('javascript_bottom')
<script>
$(document).ready( function () {
    $('#orderListing').DataTable({
         "order": [[ 0, "desc" ]]
    } );
} );
</script>
@stop

