@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')

<h2 class="sub-header">Reports</h2>
    <div class="row">
        <div class="col-xs-6">
            <div class="btn-group btn-group-lg" role="group">
                <a href="{{ URL::route("admin.projects.reports.budget", $semesterId) }}" class="btn btn-lg btn-default"> <b>Budget Report</b></a>
                <a href="{{ URL::route("admin.projects.reports.budget",  array("sem_id" => $semesterId,"type"=>"excel")) }}" class="btn btn-lg btn-success" ><i class="fa fa-file-excel-o"></i> <b>Excel</b></a>


            </div>
        </div>
        <div class="col-xs-6"></div>
    </div>
@stop
