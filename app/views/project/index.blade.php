@extends('layouts.master')
@include('layouts.dataTables')

@section('content')

            <div class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#project-nav-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand">{{ $class->UID }}</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="project-nav-collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="#">Dashboard</a></li>
                            <li><a href="#">Orders</a></li>
                            <li><a href="#">Roster</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Actions <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">New Order</a></li>
                                    <li><a href="#">New Budget Request</a></li>
                                    <li><a href="#"></a></li>
                                    <li class="dropdown-header">Instructor Actions</li>
                                    <li><a href="#">Group Management</a></li>
                                </ul>
                            </li>
                        </ul>

                        <div class="pull-right navbar-brand">Account: ${{ number_format($account->Balance,2) }}</div>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </div>






          @include('model_tables.orders')
          
                    <h2 class="sub-header">Budgets</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Terms</th>
                  <th>Amount</th>
                  
                  </tr>
              </thead>
              <tbody>
                  @foreach($budgets as $budget)
                <tr>
                  <td>{{ $budget->Terms }}</td>
                  <td>{{ $budget->Amount }}</td>
                  </tr>
                    @endforeach
                </tbody>
            </table>
          </div>

                    <h2 class="sub-header">Budget Requests</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Request</th>
                  <th>Requestor</th>
                  <th>Amount</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($budgetRequests as $budgetRequest)
                <tr>
                  <td>{{ $budgetRequest->Request }}</td>
                  <td>{{ User::find($budgetRequest->Requester)->FirstName }} {{ User::find($budgetRequest->Requester)->LastName}}</td>
                  <td>{{ $budgetRequest->Amount }}</td>
                  </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
          </div>
@stop

@section('javascript_bottom')
<script>
    $('#Orders').DataTable({
        "order": [[ 0, "desc" ]],
         "lengthMenu": [ 5, 10, 15, 25, 50 ],
         "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
            },

        ]
    } );
</script>
@stop
