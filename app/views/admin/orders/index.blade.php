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
    <div id="filters" class="panel-collapse collapse
    @if(Input::get('filters'))
    in
    @endif
    ">
      <div class="panel-body">
          {{ Form::open(array('method' => 'get')) }}
      <ul>
          <li>Filter by IPRO:
              <select name="ipro">
                  <option value="">Select an ipro below</option>
                @foreach($ipros as $ipro)

                  <option value="{{$ipro->id}}"
                      @if((Input::get('ipro')!= '') && (Input::get('ipro') == $ipro->id)){
                           selected="selected";
                      @endif
                          >{{$ipro->UID}}</option>
                    @endforeach
              </select></li>
          <li>Filter by Status: <select name="status">
                  <option value="">Select a status below</option>
                  @foreach($orderstatuses as $key => $value)
                      <option value="{{$value}}"
                              @if((Input::get('status')!= '') && (Input::get('status') == $value)){
                                selected="selected";
                              @endif
                      >{{$key}}</option>
                  @endforeach
              </select></li>
          <li>Show all for a previous semester:<select name="semester">
                  <option value="">Select a semester below</option>
                  @foreach($semesters as $key => $value)
                      <option value="{{$value}}"
                              @if((Input::get('semester')!= '') && (Input::get('semester') == $value)){
                                selected="selected";
                              @endif
                      >{{$key}}</option>
                  @endforeach
              </select> </li>
      </ul>
          {{Form::hidden('filters',true)}}
          {{ Form::submit("Filter",array("class"=>"btn btn-primary"))}}
          {{ Form::close() }}
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
        ordering:false,
    } );
} );
</script>
@stop

