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
                    <li><a href="#" onclick="approvePartialBudget()">Partial Approval</a></li>
                    <li><a href="#" onclick="approveBudget()">Full Approval</a></li>
                </ul>
            </div>
            <a href="#" onclick="denyBudget('{{$budgetRequest->id}}','{{ Project::getProjectUID(Account::lookupProjectID($budgetRequest->AccountID)) }}','{{ User::getFullNameWithId($budgetRequest->Requester) }}','{{ number_format($budgetRequest->Amount, 2) }}')" class="btn btn-danger">Deny</a>
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

<!-- Page modals -->

<!-- Full approval modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="approveModalLabel">Approve Budget</h4>
      </div>
      <div class="modal-body">
        Approving budget: Example Budget
        Budget amount: $500
        Requestor: IPRO Student
        Approval amount: $500
        Comments: <textarea></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success">Approve</button>
      </div>
    </div>
  </div>
</div><!-- ./Full approval modal -->

<!-- Partial approval modal -->
<div class="modal fade" id="partialApproveModal" tabindex="-1" role="dialog" aria-labelledby="partialApproveModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="partialApproveModalLabel">Partially Approve Budget</h4>
      </div>
      <div class="modal-body">
        Approving budget: Example Budget
        Budget amount: $500
        Requestor: IPRO Student
        Approval amount: $<input type="text" value="0.00">
        Comments: <textarea></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-warning">Approve full Budget</button>
      </div>
    </div>
  </div><!-- ./Partial approval modal -->
</div>
    <!-- Deny modal -->
<div class="modal fade" id="denyModal" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="denyModalLabel">Deny Budget</h4>
      </div>
      <div class="modal-body">
        <dl class="dl-horizontal">
            
            <dt>Budget</dt>
            <dd id="bdgtName"></dd>
            <dt>Budget Amount</dt>
            <dd id='bdgtAmount'></dd>
            <dt>Requestor</dt>
            <dd id="bdgtRequestor"></dd>
            <dt>Comments</dt>
            <dd><textarea class="form-control"></textarea></dd>
        </dl>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger">Deny Budget</button>
      </div>
    </div>
  </div>
</div><!-- ./Deny modal -->
@stop

@section('javascript_bottom')
<script>
//Conver the modals on the page to have the data we need..
    function approveBudget(bdgtid){
        $('#approveModal').modal('show');
    }
    function approvePartialBudget(bdgtid){
        $('#partialApproveModal').modal('show');
    }
    function denyBudget(bdgtid,bdgtName,requestor,amount){
        $("#bdgtId").html(bdgtid);
        $("#bdgtName").html(bdgtName);
        $("#bdgtRequestor").html(requestor);
        $("#bdgtAmount").html(amount);
        $('#denyModal').modal('show');
    }
</script>

@stop

