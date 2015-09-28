@extends('layouts.master')
@include('layouts.dataTables')
<<<<<<< HEAD
@include('layouts.fontawesome')
@section('content')
    @include('project.projectNavigation')
    <h1>{{ $class->Name }}
        <div class="pull-right">
            <button class="btn btn-primary">Raise hand</button>
        </div>
    </h1>
=======

@section('content')
    @include('project.projectNavigation')
    <h1>{{ $class->Name }}<div class="pull-right"><button class="btn btn-primary">Raise hand</button></div></h1>
>>>>>>> parent of 3cf0555... Revert "Added code for digital table tent into routes and views. Code is not yet live in nav."
    <hr>
    <div class="row">
        @foreach($teamMembers as $teamMember)
            <div class="col-xs-3" style="text-align: center"><img src="{{ URL::asset("packages/images/default-profile.jpg") }}" class="img-circle" width="180" height="180"><br>{{ $teamMember->FirstName }} {{ $teamMember->LastName }} </div>
        @endforeach
    </div>

@stop

@section('javascript_bottom')

@stop
