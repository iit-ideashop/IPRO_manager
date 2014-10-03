@extends('layouts.master')
@section('content')
<h3 class="page-header">Pending Budget Requests</h3>
<table class="table table-striped">
    <tr>
        <th>Account</th>
        <th>Requestor</th>
        <th>Amount</th>
        <th></th>       
    </tr>
    @foreach($budgetRequests as $budgetRequest)
    <tr>
        <td>{{ Project::getProjectUID(Account::lookupProjectID($budgetRequest->AccountID)) }}</td>
        <td>{{ User::getFullNameWithId($budgetRequest->Requester) }}</td>
        <td>${{ number_format($budgetRequest->Amount, 2) }}</td>
        <td>
            
            <a href="" class="btn btn-default">View</a>
            <div class="btn-group">
                <button type="button" class="btn btn-success" data-toggle="dropdown">Approve</button>
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Partial Approval</a></li>
                    <li><a href="#">Full Approval</a></li>
                </ul>
            </div>
            <a href="" class="btn btn-danger">Deny</a>
        </td>
    </tr>
    @endforeach
</table>
<h3 class="page-header">Recently Approved Budget Requests(Last 5)</h3>
<table class="table table-striped">
    <tr>
        <th>Project</th>
        <th>Requester</th>
        <th>Amount</th>
        
        <th></th>
    </tr>
    @foreach($approvedBudgets as $approvedBudget)
    <tr>
        <td>{{ Project::getProjectUID(Account::lookupProjectID($approvedBudget->AccountID)) }}</td>
        <td>{{ User::getFullNameWithId($approvedBudget->Requester)}}</td>
        <td>${{ number_format($approvedBudget->Amount,2) }}</td>
        
        <td></td>
    </tr>
    @endforeach
    
</table>

@stop

@section('javascript_bottom')


@stop

