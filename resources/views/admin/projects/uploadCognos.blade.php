@extends('layouts.master')
@section('javascript')
    <script src="{{ URL::asset('packages/bootstrap/js/jquery.maskMoney.min.js') }}"></script>
    <script src="{{ URL::asset('packages/bootstrap/js/jquery.numeric.js') }}"></script>
@stop
@section('javascript_bottom')
    <script>
        $(document).ready( function() {
            $('.btn-file :file').on('change', function(event,numfiles,label) {
                var filename = event.currentTarget.files[0].name;
                $("#cognosFilename").val("Cognos Report we are applying to IPRO Manager: "+filename);
            });
            $('#initialBudget').maskMoney({thousands: ',', decimal: '.', allowZero: true, prefix: '$ '});
            $('#initialBudget').maskMoney('mask', 0.00);

        });
    </script>
@stop
@section('stylesheets')
<style>
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
</style>
@stop
@section('content')
<h2 class="sub-header">Uploading Cognos report for {{ $semester->Name }}</h2>
This tool is used to upload a cognos report to IPRO Manager and have it automatically populate IPRO Manager with students and projects. You can also configure initial budgets and teambuilding dollars via this page
so that teams are automatically assigned teambuilding money and budget money.
{{ Form::open(array('files'=>true)) }}
<h2 class="sub-header">Step 1: Upload cognos report</h2>
Please upload the most recent cognos report. The file that this script uses can be found under SharedReports -> ODS -> Active Registration -> Registrar's Office -> "Class List with Student Program". Make sure you run a 2007 Excel Report.<br />
<br />
<div class="input-group">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file">
                        Select Cognos Report <input type="file" name="cognosFile">
                    </span>
                </span>
    <input type="text" class="form-control" id="cognosFilename" readonly="">
</div>


<h2 class="sub-header">Step 2: Team budgets</h2>
In this section we can apply team budgets to newly created teams. If you would like an initial budget applied to newly created teams from the Cognos report please enter it below. $0 will not create an inital budget for the teams.
    <div class="form-group">
        <label for="initialBudget">Initial Budget:</label>
        <input type="text" class="form-control" name="initialBudget" id="initialBudget">
    </div>

<h2 class="sub-header">Step 3: Send emails</h2>
<div class="checkbox">
    <label><input type="checkbox" checked name="notifyStudents" /> Send an email to students who were enrolled or dropped in this update</label>
</div>
<h2 class="sub-header">Step 4: Run</h2>
{{ Form::submit("Update IPRO Manager", array('class'=>'btn btn-primary')) }}

{{ Form::close() }}
@stop

