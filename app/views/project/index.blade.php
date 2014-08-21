@extends('layouts.master')

@section('content')
            <h1 class="page-header">{{ $class->UID }}<div class="pull-right">Account: ${{ number_format($account->Balance,2) }} </div></h1>

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