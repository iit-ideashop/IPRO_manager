@extends('layouts.master');
@section('javascript')
        <script src="{{ URL::asset('packages/bootstrap/js/jquery.maskMoney.min.js') }}"></script>
        <script src="{{ URL::asset('packages/bootstrap/js/jquery.numeric.js') }}"></script>
@stop
@section('content')
<p><a href="{{ URL::to('/admin/projects/') }}"><span class="glyphicon glyphicon-arrow-left"></span> Back</a></p>
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
    {{ Form::label('CRN','Course Registration Number', array('class'=>'col-sm-2 control-label')) }}
    <div class="col-sm-8">
        {{ Form::text('CRN','',array('class'=>'form-control','placeholder'=>'999999')) }}
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
    {{ Form::label('Budget','Initial Budget', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-2">
        {{ Form::text('Budget','',array('class'=>'form-control')) }}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ Form::submit('Submit',array('class'=>'btn btn-default'))}}
    </div>
</div>


{{ Form::close() }}
@stop

@section('javascript_bottom')
<script>
$('#Budget').maskMoney({thousands:',', decimal:'.', allowZero:true, prefix: '$ '});
$('#Budget').maskMoney('mask',0.00);
</script>
@stop
