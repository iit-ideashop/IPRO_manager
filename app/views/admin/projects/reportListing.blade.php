@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')

<h2 class="sub-header">Reports</h2>
    <div class="row">
        <div class="col-xs-6"><a href="{{ URL::route("admin.projects.reports.budget", $semesterId) }}" class="btn btn-lg btn-block btn-default" style="text-align: left;"><i class="fa fa-usd text-success"></i> <b>Budget Report</b><br>Run a budget report for the entire semester based on current data</a></div>
        <div class="col-xs-6"></div>
    </div>
@stop
