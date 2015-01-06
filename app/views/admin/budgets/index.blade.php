@extends('layouts.master')
@section('javascript')
<script src="{{ URL::asset('packages/bootstrap/js/jquery.numeric.js') }}"></script>
@stop
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
            
            <a href="{{ URL::route('admin.budget.viewRequest',['id'=> $budgetRequest->id])}}" class="btn btn-default">View</a>
            <div class="btn-group">
                <button type="button" class="btn btn-success" data-toggle="dropdown">Approve</button>
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#" onclick="approvePartialBudget('{{$budgetRequest->id}}','{{ Project::getProjectUID(Account::lookupProjectID($budgetRequest->AccountID)) }}','{{ User::getFullNameWithId($budgetRequest->Requester) }}','{{ number_format($budgetRequest->Amount, 2) }}')">Partial Approval</a></li>
                    <li><a href="#" onclick="approveBudget('{{$budgetRequest->id}}','{{ Project::getProjectUID(Account::lookupProjectID($budgetRequest->AccountID)) }}','{{ User::getFullNameWithId($budgetRequest->Requester) }}','{{ number_format($budgetRequest->Amount, 2) }}')">Full Approval</a></li>
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
          {{Form::open(array('route' => 'admin.budget.approve'))}}
        <dl class="dl-horizontal">
            <input type="hidden" name="requestID" id="bdgtID-full">
            <dt>Budget</dt>
            <dd id="bdgtName-full"></dd>
            <dt>Budget Amount</dt>
            <dd id='bdgtAmount-full'></dd>
            <input type='hidden' name="budgetAmount" id="bdgtAmount-full-hidden" value="">
            <dt>Requestor</dt>
            <dd id="bdgtRequestor-full"></dd>
            <dt>Comments</dt>
            <dd>{{Form::textarea('requestComment',null,array('class'=>'form-control')) }}
                </dd>
        </dl>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Approve</button>
        {{ Form::close()}}
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
          {{Form::open(array('route' => 'admin.budget.approve'))}}
        <dl class="dl-horizontal">
            <dt>Budget</dt>
            <dd id="bdgtName-partial"></dd>
            <input type="hidden" name="requestID" id="bdgtID-partial">
            <dt>Budget Amount</dt>
            <dd id='bdgtAmount-partial'>
                <div class="form-group">
                    <label for="amount">Amount</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            {{Form::text('budgetAmount','0.00',array('class'=>'form-control','id'=>'budgetAmount-partial'))}}
                            <span class="input-group-addon">/</span>
                            <input type='text' disabled="disabled" class="form-control" id="budgetAmount-partial-original">
                            
                        </div>
                </div>
            </dd>
            <dt>Requestor</dt>
            <dd id="bdgtRequestor-partial"></dd>
            <dt>Comments</dt>
            <dd>{{Form::textarea('requestComment',null,array('class'=>'form-control')) }}</dd>
        </dl>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-warning">Approve partial budget</button>
      </div>
        {{ Form::close()}}
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
          {{Form::open(array('route' => 'admin.budget.deny'))}}
        <dl class="dl-horizontal">
            <input type="hidden" name="requestID" id="bdgtID-deny">
            <dt>Budget</dt>
            <dd id="bdgtName-deny"></dd>
            <dt>Requestor</dt>
            <dd id="bdgtRequestor-deny"></dd>
            <dt>Comments</dt>
            <dd>{{Form::textarea('requestComment',null,array('class'=>'form-control')) }}</dd>
        </dl>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger">Deny Budget</button>
        {{ Form::close() }}
      </div>
    </div>
  </div>
</div><!-- ./Deny modal -->
@stop

@section('javascript_bottom')
<script>
    $(document).ready(function(){
        $("#budgetAmount-partial").numeric({ decimal : "." , negative : false });
    });
//Conver the modals on the page to have the data we need..
    function approveBudget(bdgtid,bdgtName,requestor,amount){
        $("#bdgtID-full").attr('value',bdgtid);
        $("#bdgtName-full").html(bdgtName);
        $("#bdgtRequestor-full").html(requestor);
        $("#bdgtAmount-full-hidden").attr('value',amount);
        $("#bdgtAmount-full").html(amount);
        $('#approveModal').modal('show');
    }
    function approvePartialBudget(bdgtid,bdgtName,requestor,amount){
        $("#bdgtID-partial").attr('value',bdgtid);
        $("#bdgtName-partial").html(bdgtName);
        $("#budgetAmount-partial-original").attr('value',amount);
        $("#bdgtRequestor-partial").html(requestor);
        $('#partialApproveModal').modal('show');
    }
    function denyBudget(bdgtid,bdgtName,requestor,amount){
        $("#bdgtID-deny").attr('value',bdgtid);
        $("#bdgtName-deny").html(bdgtName);
        $("#bdgtRequestor-deny").html(requestor);
        $('#denyModal').modal('show');
    }
</script>

@stop

