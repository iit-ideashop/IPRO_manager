@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')
    @include('project.projectNavigation')
    <h1>{{ $class->Name }} - All Scrum Reports</h1>
    <hr>
    <table class="table table-striped" id="scrumListing">
        <thead>
            <th>Scrum Report #</th>
            <th>Submitted Date</th>
            <th>Scrum Submitter</th>
            <th> </th>
            <th>Compare?</th>
        </thead>
        <tfoot>
            <th></th>
            <th></th>
            <th></th>
            <th> </th>
            <th><a class="btn btn-default" onclick="runCompare()"><i class="fa fa-arrow-up"></i> Compare</a></th>
        </tfoot>
        <?php
            $scrumCounter = 1;
            $lastScrum = 0;
        ?>
        @foreach($scrums as $scrum)
        <tr>
            <td>{{ $scrumCounter }}</td>
            <td>{{ date('D F jS Y, g:i a', strtotime($scrum->created_at)) }}</td>
            <td>{{ User::getFullNameWithId($scrum->CreatedBy) }}</td>
            <td><a class="btn btn-default" onclick="viewScrum({{ $scrum->id }})">View</a>
                @if($lastScrum != 0)
                    <a class="btn btn-default" onclick="progressScrum({{ $lastScrum }},{{ $scrum->id }})">Compare to previous</a>
                @endif
            </td>
            <td>
                <input type="checkbox" name="scrum-{{ $scrum->id }}" value="{{ $scrum->id }}">
            </td>
        </tr>
            <?php
                $scrumCounter++;
                $lastScrum = $scrum->id;
            ?>
        @endforeach
    </table>
    {{ Form::open(array("route"=>array("project.viewScrumReport",$class->id),"id"=>"scrumReportForm")) }}
    {{ Form::hidden("scrumIDs","",array("id"=>"scrumIDs")) }}
    {{ Form::close() }}

@stop


@section('javascript_bottom')
<script>
    var scrumReportIDs = [];

    function viewScrum(scrum){
        scrumReportIDs = [];
        scrumReportIDs.push(scrum);
        runScrumReport();
    }
    function progressScrum(firstScrum, secondScrum){
        scrumReportIDs = [];
        scrumReportIDs.push(firstScrum, secondScrum);
        runScrumReport();
    }

    function runCompare(){
        //Grab the checkboxes and run a compare
        $('#scrumListing input:checked').each(function() {
            scrumReportIDs.push($(this).attr('value'));
        });
        runScrumReport();
    }

    function runScrumReport(){
        //Take the ID's from the scrumReport array and make the request
        if(scrumReportIDs.length > 1) {
            $("#scrumIDs").attr("value", JSON.stringify(scrumReportIDs));
            $("#scrumReportForm").submit();
        }else{
            alert("Please Select at least one scrum to compare or view");
        }

    }

</script>
@stop
