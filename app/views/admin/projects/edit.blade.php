@extends('layouts.master');
@section('content')
<p><a href="{{ URL::to('/admin/projects/') }}"><span class="glyphicon glyphicon-arrow-left"></span> Back</a></p>
<h1 class="page-header">Edit Project</h1>

{{ Form::model($editProject,array('class'=>'form-horizontal')) }} 
<div class="form-group">
    {{ Form::label('UID','Unique Name', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-8">
        {{ Form::text('UID',null,array('class'=>'form-control','placeholder'=>'IPRO 303')) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('Name','Name', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-8">
        {{ Form::text('Name',null,array('class'=>'form-control')) }}
    </div>
</div>


<div class="form-group">
    {{ Form::label('Description','Description', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-8">
        {{ Form::textarea('Description',null,array('class'=>'form-control')) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('Semester','Semester', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-8">
        <select name="Semester" class="form-control">
            @foreach($semesters as $semester)
            <option value="{{ $semester->id }}"
                    @if($semester->id == $editProject->Semester)
                        selected="selected"
                    @endif
                    >{{ $semester->Name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    {{ Form::label('Project','Parent Project', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-8">
        <select name="ParentClass" class="form-control">
            @foreach($projects as $project)
            <option value="{{ $project['id'] }}"
                    @if($project['id'] == null)
                        selected="selected"
                    @elseif ($project['id'] == $editProject->ParentClass)
                        selected="selected"
                    @endif
                    >{{ $project['UID'] }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ Form::submit('Submit',array('class'=>'btn btn-default'))}}
    </div>
</div>


{{ Form::close() }}
@stop