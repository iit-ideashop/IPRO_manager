@extends('layouts.master')
@include('layouts.dataTables')
@section('javascript_bottom')
@stop
@section('content')

<div class="page-header">
    <h1>{{ $project->UID}} Overview
    <!-- Split button -->   
    <div class="btn-group">
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    Actions <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="#">Create Budget</a></li>
    <li><a href="{{URL::route("admin.orders",array('ipro'=>$project->id, 'status'=>'', 'semester'=>'', 'filters'=>'1'))}}">View Orders</a></li>
    <li><a href="{{URL::route("admin.accounts.editor",$project->id)}}">Account Editor</a></li>
      <li><a href="{{URL::route("project.groupmanager",$project->id)}}">Group Manager</a></li>
  </ul>
</div>
    </h1>
    <h3>{{$project->Name}}</h3>
</div>

<div class="page-header">
    <h3>Budgets</h3>
</div>
<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>Requester</th>
        <th>Amount</th>
        <th>Terms</th>
        <th>Approved by</th>
    </tr>
    @foreach($budgets as $budget)
    <tr>
        <td>{{ $budget->id }}</td>
        <td>{{ User::getFullNameWithId($budget->Requester)}}</td>
        <td>${{ number_format($budget->Amount,2) }}</td>
        <td>{{ $budget->Terms}}</td>
        <td>{{ User::getFullNameWithId($budget->Approver)}}</td>
    </tr>
    @endforeach
</table>
<div class="page-header">
    <h3>Requested Budgets</h3>
</div>        
<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>Requester</th>
        <th>Amount</th>
        <th>Justification</th>
        <th></th>
    </tr>
    @foreach($budgetRequests as $budgetRequest)
    <tr>
        <td>{{$budgetRequest->id}}</td>
        <td>{{User::getFullNameWithId($budgetRequest->Requester)}}</td>
        <td>${{number_format($budgetRequest->Amount,2)}}</td>
        <td>{{$budgetRequest->Request}}</td>
        <td><a href="" class="btn btn-default btn-sm">View</a></td>
    </tr>
    
    @endforeach
    
</table>
<div class="page-header">
    <h3>Students</h3>
</div>        
<table class="table table-bordered table-condensed">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th></th>
    </tr>
        @foreach($students as $student)
        <tr>
            <td>{{ $student->id}}</td>
            <td>{{ $student->FirstName}} {{$student->LastName}} ( <a href="mailto:{{$student->Email}}">{{$student->Email}}</a> )</td>
            <td><a href="" class="btn btn-default btn-sm">View</a></td>
        </tr>
        @endforeach
</table>
@stop
