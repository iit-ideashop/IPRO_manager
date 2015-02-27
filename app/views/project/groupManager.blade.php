@extends('layouts.master')

@section('content')
    @include('project.projectNavigation')
    <div class="page-header">
        <h1>Group Manager</h1>
    </div>
    @if(!$groups->isEmpty())

    @else
        <div class="panel panel-default">
            <div class="panel-body">
                Oops. There are no groups created for your IPRO. Click above to create one.
            </div>
        </div>
    @endif
@stop

