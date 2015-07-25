@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')
    @include('project.projectNavigation')
    <h1>{{ $class->Name }} - New Scrum Report</h1>
    <hr>
    {{ Form::open() }}
    <div class="form-group">
        {{ Form::label('previously','What did we do last week?', array('class'=>'col-sm-2 control-label')) }}
        {{ Form::textarea('previously','',array('class'=>'form-control',"required")) }}
    </div>
    <div class="form-group">
        {{ Form::label('planned','What will we do this week?', array('class'=>'col-sm-2 control-label')) }}
        {{ Form::textarea('planned','',array('class'=>'form-control',"required")) }}
    </div>
    <div class="form-group">
        {{ Form::label('barriers','Barriers and Questions', array('class'=>'col-sm-2 control-label')) }}
        {{ Form::textarea('barriers','',array('class'=>'form-control',"required")) }}
    </div>
    <div class="form-group">
        {{ Form::submit("Create Scrum Report",array("class"=>"btn btn-primary")) }}
    </div>

    {{ Form::close() }}
@stop


@section('javascript_bottom')

@stop
