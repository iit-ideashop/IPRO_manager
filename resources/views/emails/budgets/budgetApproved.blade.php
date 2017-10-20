@extends('emails.layout')
@section('TopSentence')
Update to your IPRO Budget
@stop
@section('content')

                            <h3>Hi {{ $person->FirstName }},</h3>
                            <p>Your Budget Request has been approved.</p>
                            <p>Below are the details of the approved budget request.</p>
<table class="tableBlue" width="100%" cellpadding="3px" cellspacing="3px">
<tr>
<td>
<p>Budget Request: {{ $budgetRequest->Request }}</p>
<p>Request Amount: ${{ number_format($budgetRequest->Amount,2)}}</p>
<p>Approval Amount: ${{ number_format($budget->Amount,2)}}</p>
<p>Budget Terms: {{$budget->Terms}}</p>
<p>Comments: {{ $budget->Comment }}</p>
<p>Approved by: {{User::getFullNameWithId($budget->Approver)}}</p>
</td>
</tr>
</table>
<p>-The IPRO Budgeting Robot</p>
@stop