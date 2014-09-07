@if(!$orders->isEmpty())
<h2 class="sub-header">My Orders</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Order #</th>
                  <th>Status</th>
                  <th></th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($orders as $order)
                <tr>
                  <td>{{ $order->Description }}</td>
                  <td>Status: {{ $order->getStatus() }}</td>
                  <td><a href="">View</a></td>
                  </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
@endif