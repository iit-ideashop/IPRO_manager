@extends('layouts.master')
@include('layouts.dataTables')
@include('layouts.fontawesome')
@section('content')
    @include('project.projectNavigation')
    <h1>{{ $class->Name }} - Viewing a scrum report
    </h1>
    <hr>
    @foreach($scrums as $scrum)
        <h4>{{ date('D F jS Y, g:i a') }} Submission</h4>
        <div class="row">
            <div class="col-xs-4">
                <div class="panel panel-default">
                    <div class="panel-heading">What did we do last week?</div>
                    <div class="panel-body">
                    {{ nl2br($scrum->previously) }}

                    </div>
                </div>
            </div>

            <div class="col-xs-4">
                <div class="panel panel-default">
                    <div class="panel-heading">What will we do this week?</div>
                    <div class="panel-body">
                        {{ nl2br($scrum->planned) }}

                    </div>
                </div>
            </div>

            <div class="col-xs-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Barriers and Questions</div>
                    <div class="panel-body">
                        {{ nl2br($scrum->barriers) }}
                    </div>
                </div>
            </div>
        </div>
        <hr>
    @endforeach
@stop

@section('javascript_bottom')

@stop



