@extends('layouts.master');
@section('content')
<p><a href="{{ URL::to('/admin/projects/') }}">&LT;--Back</a></p>
<h1 class="page-header">New Project</h1>
<ul>
   @forelse($errors as $error)
   <li>{{ $error }}</li>
   @empty
   
   @endforelse
</ul>

{{ Form::open(array('class'=>'form-horizontal')) }} 
<div class="form-group">
    {{ Form::label('UID','Unique Name', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-8">
        {{ Form::text('UID','',array('class'=>'form-control','placeholder'=>'IPRO 303')) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('Name','Name', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-8">
        {{ Form::text('Name','',array('class'=>'form-control')) }}
    </div>
</div>


<div class="form-group">
    {{ Form::label('Description','Description', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-8">
        {{ Form::textarea('Description','',array('class'=>'form-control')) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('Semester','Semester', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-8">
        <select name="Semester" class="form-control">
            @foreach($semesters as $semester)
            <option value="{{ $semester->id }}">{{ $semester->Name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    {{ Form::label('Project','Parent Project', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-8">
        <select name="ParentClass" class="form-control">
            @foreach($projects as $project)
            <option value="{{ $project['id'] }}">{{ $project['UID'] }}</option>
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