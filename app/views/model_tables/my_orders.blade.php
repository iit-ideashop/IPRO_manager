@if(!$orders->isEmpty())
<h2 class="sub-header">My Orders</h2>
          <div class="table-responsive">
            <table width="100%" class="table table-striped" id="myOrders">
              <thead>
                <tr>
                    <th>ID</th>
                  <th>Order #</th>
                  <th>Description</th>
                  <th>Status</th>
                  <th></th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($orders as $order)
                <tr>
                  <td>{{ $order->id }}</td>
                  <td>Order: {{ $order->id }}</td>
                  <td>{{ $order->Description }}</td>
                  <td>Status: {{ $order->getStatus() }}</td>
                  <td><a href="{{URL::route("project.order.view",array($order->ClassID, $order->id))}}">View</a></td>
                  </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
@endif


