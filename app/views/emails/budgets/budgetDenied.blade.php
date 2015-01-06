@extends('emails.layout')
@section('TopSentence')
Update to your IPRO Budget
@stop
@section('content')

                            <h3>Hi {{ $person->FirstName }},</h3>
                            <p>Your Budget Request has been denied.</p>
                            <p>Below are the details of the denied budget request.</p>
<table class="tableBlue" width="100%" cellpadding="3px" cellspacing="3px">
<tr>
<td>
<p>Budget Request: {{ $budgetRequest->Request }}</p>
<p>Request Amount: ${{ number_format($budgetRequest->Amount,2)}}</p>
<p>Reason for Rejection: {{ $budgetRequest->Comment }}</p>
<p>Rejected by: {{User::getFullNameWithId($budgetRequest->ModifiedBy)}}</p>
</td>
</tr>
</table>
<p>-The IPRO Budgeting Robot</p>
@stop