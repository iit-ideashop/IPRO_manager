@extends('layouts.master')
@include('layouts.handsontable')
@include('layouts.dataTables')
@section('content')
    @include('admin.iproday.iprodayNavigation')
<select name="iprodays">
    @foreach($iprodays as $iprod)
    <option>{{ $iprod->eventDate}}</option>
    @endforeach
</select>
<div class="table-responsive">
<table class="table table-striped" id="regListing">
    <thead>
        <th>RegID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Organization</th>
        <th>Attendee Type</th>
    </thead>
    @foreach($reg_data as $reg)
    <tr>
        <td>{{$reg->id}}</td>
        <td>{{$reg->firstName}}</td>
        <td>{{$reg->lastName}}</td>
        <td>{{$reg->organization}}</td>
        <td>{{$reg->type}}</td>
    </tr>
    @endforeach
</table>
    
    <a href="/admin/iproday/{{ $iproday->id }}/report/registration" target="_blank" class="btn btn-primary">Download Registration Report</a>
    <div id="registrationTable">
    </div>
    
</div>
@stop

@section('javascript_bottom')
<script>
    $('#regListing').DataTable({
    } );
var data = [
    ["RegID","First Name","Last Name","Organization","Attendee Type","Reg Time","Update Time","No Preference"
    <?php
        $trackArray = array();
        $trackArray[0] = 0;
        $counter = 1;
    ?>
    @foreach($tracks as $track)
        ,"{{$track->name}}"
    <?php
        $trackArray[$track->id] = $counter;
        $counter++;
    ?>
    @endforeach
        ],
        
        @foreach($reg_data as $reg)
        ["{{$reg->id}}","{{$reg->firstName}}","{{$reg->lastName}}","{{$reg->organization}}","{{$reg->type}}","{{date('m/d/Y g:i a',strtotime($reg->created_at))}}","{{date('m/d/Y g:i a',strtotime($reg->updated_at))}}"
            <?php
                //Build a new array of what we should be echoing
                $regTrackPref = array();
            ?>
            @foreach(unserialize($reg->trackPreferences) as $trackPref)
                <?php
                    array_push($regTrackPref,$trackArray[$trackPref]);
                ?>
            @endforeach
            <?php
            for($a = 0; $a < count($trackArray); $a++){
                if(in_array($a,$regTrackPref)){
                    echo ',"x"';
                }else{
                    echo ',""';
                }
            }
            ?>
    ],    
        @endforeach
    
];
var container = $("#registrationTable");
container.handsontable({
  data: data,
  colHeaders: true,
  stretchH: 'all',
  width: 1000,
  height: 700
});

</script>
@stop