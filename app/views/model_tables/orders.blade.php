<h2 class="sub-header">Orders  <a href="{{ URL::route('project.order.new',$class->id)}}" class="btn btn-default">New</a></h2>
          <div class="table-responsive">
            <table class="table table-striped" id="Orders">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Order #</th>
                  <th>Order Name</th>
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
                  <td><a class="btn btn-default" href="{{ URL::route('project.order.view',array('projectid'=>$class->id, 'orderid'=>$order->id)) }}">View</a></td>
                  </tr>
                    @endforeach
                </tbody>
            </table>
          </div>