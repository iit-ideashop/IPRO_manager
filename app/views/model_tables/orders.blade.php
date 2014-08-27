<h2 class="sub-header">Orders  <a href="{{ URL::to('project/'.$selected_class.'/orders/new')}}" class="btn btn-default">New</a></h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Order #</th>
                  <th>Order Name</th>
                  <th>Status</th>
                  <th></th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($orders as $order)
                <tr>
                  <td>Order: {{ $order->id }}</td>
                  <td>{{ $order->Description }}</td>
                  <td>Status: {{ $order->getStatus() }}</td>
                  <td><a class="btn btn-default" href="{{ URL::to('/project/'.$selected_class.'/orders/'.$order->id) }}">View</a></td>
                  </tr>
                    @endforeach
                </tbody>
            </table>
          </div>