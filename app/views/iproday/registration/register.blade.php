@extends('layouts.registration')


@section('content')
<b>Join us for IPRO Day!</b><br>
<br>
{{ date('l, F j, Y',strtotime($iproday->eventDate)) }}<br>
{{ date('g:i a',strtotime($iproday->guestsStart)) }} - {{ date('g:i a',strtotime($iproday->guestsEnd)) }}<br>
Judge's Breakfast: {{ date('g:i a',strtotime($iproday->judgesStart)) }}<br>
<br>
<a href="{{ $iproday->locationLink }}">{{ $iproday->location }}</a><br>
<p>
    Professionals from the Chicago area are invited to attend as Guests or serve as Judges. Volunteer Guests and Judges at IPRO Day are able to network with other professionals who are passionate about education, engineering, and design while interacting with some of the most talented students in the U.S. and learning about cutting-edge research at IIT. The IPRO Day event is the culmination of IPRO team work via exhibits. See IPRO Team descriptions here.
    <br><br>
    Your support of the IPRO Progam is greatly appreciated!
</p>
{{ Form::open(array('class'=>'form-horizontal')) }}
<div class="form-group">
    {{ Form::label('firstName','First Name',array('class'=>'col-xs-2 control-label'))}}
    <div class="col-xs-4">
        {{ Form::text('firstName','',array('class'=>'form-control','placeholder'=>'Required','required')) }}
    </div>
</div>


<div class="form-group">

    {{ Form::label('lastName','Last Name',array('class'=>'col-xs-2 control-label')) }}
    <div class="col-xs-4">
        {{ Form::text('lastName','',array('class'=>'form-control','placeholder'=>'Required','required')) }}

    </div>
</div>

<div class="form-group">
    {{ Form::label('organization','Organization',array('class'=>'col-xs-2 control-label')) }}

    <div class="col-xs-4">
        {{ Form::text('organization','',array('class'=>'form-control')) }}
    </div>
</div>


<div class="form-group">
    {{ Form::label('address','Address',array('class'=>'col-xs-2 control-label')) }}
    <div class="col-xs-4">
        {{ Form::text('address','',array('class'=>'form-control')) }}
    </div>
</div>



<div class="form-group">
    {{ Form::label('phone','Phone',array('class'=>'col-xs-2 control-label')) }}
    <div class="col-xs-4">
        {{ Form::text('phone','',array('class'=>'form-control')) }}
    </div>
</div>


<div class="form-group">
    {{ Form::label('email','Email',array('class'=>'col-xs-2 control-label')) }}
    <div class="col-xs-4">
        {{ Form::text('email','',array('class'=>'form-control','placeholder'=>'Required','required')) }}
    </div>
</div>



<div class="form-group">
    {{ Form::label('attendeetype','I would like to attend as a',array('class'=>'col-xs-2 control-label')) }}
    <div class="col-xs-4">
        {{ Form::radio('attendeetype','Guest')}} Guest <br>
        {{ Form::radio('attendeetype','Judge')}} Judge
    </div> 
</div>

<div class="form-group">
    {{ Form::label('judgedBefore','FOR JUDGES: Have you judged IPRO before?',array('class'=>'col-xs-2 control-label')) }}
    <div class="col-xs-4">

        {{ Form::radio('judgedBefore','1') }} Yes <br>
        {{ Form::radio('judgedBefore','0') }} No
        </div>
</div>
@if($tracks->isEmpty())
    <div class="row">
        <div class="col-xs-offset-2 col-xs-10">
        <b>Judges, please note:</b> Teams are still being sorted into Tracks.
    We will email you when Track Selection is available.
    </div></div><br>
@else
<div class="form-group">
    <div class="col-xs-offset-2 col-xs-4">
        <b>Which track would you prefer to judge?</b>
    </div>
</div>    
<div class="row">
    <div class="col-xs-offset-2 col-xs-10">
<div class="panel-group" id="iproListing">
   
      <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
          {{ Form::checkbox('trackSelection[]',0) }}
        <a data-toggle="collapse" data-parent="#iproListing" href="#listing-0">
            No Preference
        </a>
      </h4>
    </div>
    <div id="listing-0" class="panel-collapse collapse">
      <div class="panel-body">
          <ul class="list-group">
              No preference.
        </ul>
      </div>
    </div>
  </div>
    
    @foreach($tracks as $track)
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
          {{ Form::checkbox('trackSelection[]',$track->id) }}
        <a data-toggle="collapse" data-parent="#iproListing" href="#listing-{{ $track->id}}">
            Track #{{ $track->number }}: {{ $track->name }}
        </a>
      </h4>
    </div>
    <div id="listing-{{$track->id}}" class="panel-collapse collapse">
      <div class="panel-body">
          <ul class="list-group">
              @foreach($tracksArray[$track->id] as $iprolisting)
            <li class="list-group-item">
                <span class="badge pull-left">IPRO {{ $iprolisting->iproNumber }}</span> 
                    {{ $iprolisting->iproName }}
            </li>
                @endforeach
        </ul>
      </div>
    </div>
  </div>
    @endforeach
</div>
    </div>
</div>
@endif


<div class="form-group">
    {{ Form::label('dietaryRestrictions','Judges receive a complimentary breakfast and lunch. Do you have any dietary restrictions or require any special accommodations?',array('class'=>'col-xs-2 control-label')) }}
    <div class="col-xs-6">
        {{ Form::textarea('dieraryRestrictions','',array('class'=>'form-control','rows'=>'4')) }}
        </div>
</div>

<div class="form-group">
    <div class="col-xs-offset-2 col-xs-4">
        {{ Form::submit('Register',array('class'=>'btn btn-default')) }}
        </div>
</div>



{{ Form::close() }}

@stop
@section('javascript_bottom')
@stop
