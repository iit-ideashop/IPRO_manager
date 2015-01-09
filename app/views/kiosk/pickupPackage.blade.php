@extends('layouts.registration')
@include('layouts.sigpad')
@section('content')
    <h1>Pickup a Package</h1>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">User information</h3>
        </div>
        <div class="panel-body">
            Name: {{$student->FirstName}} {{$student->LastName}}<br>
            Email: {{$student->Email}}<br>
        </div>
    </div>
    <ul class="list-group">
        <li class="list-group-item disabled">Items being picked up</li>
        @foreach($items as $item)
            <li class="list-group-item">{{$item->Name}}
            @if($item->Returning)
                    <span class="badge">Returning</span>
            @endif
            </li>
        @endforeach
    </ul>

    <p><b>By signing this pickup agreement you agree to the following:</b> </p>
    <ul>
        <li>You have been authorized by your team to pickup these items</li>
        <li>You take full responsibility for these items. If any of these items are lost your student account will be billed and a hold will be placed on your account</li>
        <li>These items will be used for your IPRO</li>
        <li>Any items marked as "Returning" must be returned to the Ideashop at the end of the semester</li>
    </ul>
<div class="row"><div class="col-xs-11">
        {{ Form::open(array("route"=>'kiosk.completePackagePickup',"name"=>"completePickup","method"=>"post","id"=>"completePickup","class"=>"sigPad")) }}
        <input type="hidden" name="pickupid" value="{{$pickup->id}}">
        <input type="hidden" name="AuthorizeCode" value="{{$pickup->AuthorizeCode}}">
        <div class="sig sigWrapper">
            <canvas class="pad" id="sigpad" width="550" height="200"></canvas>
            <input type="hidden" name="signatureData" id="sigpadoutput" class="output">
        </div>
    {{Form::close()}}
    </div></div>
<div class="row">
    <div class="col-xs-10 col-xs-offset-2"><button class="pull-right btn btn-primary btn-lg" id="completePickupBtn">Complete</button></div>
</div>

@stop
@section('javascript_bottom')
    <script>
        $( document ).ready(function() {
            //Bind the barcode lookup button with the barcode lookup function
           $("#completePickupBtn").on("click",function(){
                completePickup();
           });
        });

        function completePickup(){
            //Take the sig, make it into data and submit the form
            $("#sigpadoutput").attr("value",$(".sigPad").signaturePad().getSignatureImage());
            $("#completePickup").submit();
        }
        var options = {
            drawOnly : true,
            lineWidth: 0,
            lineMargin: 0
        };
        $(document).ready(function() {
            $('.sigPad').signaturePad(options);

            var img = new Image();
            var imgData = "data:image/png;base64,{{$sigpad}}";
            img.src = imgData;
            var c=document.getElementById("sigpad");
            var ctx=c.getContext("2d");
            ctx.drawImage(img,0,0,550,200);
        });
    </script>
@stop


