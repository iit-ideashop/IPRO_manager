@extends('layouts.master');
@section('stylesheets')
<link rel="stylesheet" href="{{ URL::asset('packages/bootstrap/css/datepicker.css') }}">
@stop

@section('javascript')
<script src="{{ URL::asset('packages/bootstrap/js/bootstrap-datepicker.js') }}"></script>
@stop

@section('content')
<p><a href="{{ URL::to('/admin/semesters/') }}">&LT;--Back</a></p>
<h1 class="page-header">New Semester</h1>
<ul>
   
   @forelse($errors as $error)
   <li>{{ $error }}</li>
   @empty
   
   @endforelse
</ul>

{{ Form::open(array('class'=>'form-horizontal')) }} 
<div class="form-group">
    {{ Form::label('Name','Semester Name', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-8">
        {{ Form::text('Name','',array('class'=>'form-control','placeholder'=>'Fall 1998')) }}
    </div>
</div>

<div class="form-group">
    {{ Form::label('ActiveStart','Semester Start', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-3">
        {{ Form::text('ActiveStart','',array('class'=>'form-control','id'=>'ActiveStart','data-date-format'=>'yyyy-mm-dd')) }}
    </div>
</div>


<div class="form-group">
    {{ Form::label('ActiveEnd','Semester End', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-3">
        {{ Form::text('ActiveEnd','',array('class'=>'form-control','id'=>'ActiveEnd','data-date-format'=>'yyyy-mm-dd')) }}
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
        {{ Form::checkbox('makeActive','yes',false) }} Make semester active?
        </div>
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ Form::submit('Submit',array('class'=>'btn btn-default'))}}
    </div>
</div>
{{ Form::close() }}
<script>
$('#ActiveEnd').datepicker().data('datepicker');
$('#ActiveStart').datepicker().data('datepicker');
</script>
@stop