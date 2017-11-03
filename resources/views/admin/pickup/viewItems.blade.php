@extends('layouts.master')
@include('layouts.dataTables')
@section('content')
<h1 class="page-header">Pickup Order Items</h1>
<h3>Student: {{$student->FirstName}} {{$student->LastName}}</h3>
<h3>ID Hash: {{$student->CWIDHash}}</h3>
<div class="form-horizontal row">
<div class="form-group col-xs-6">
    <label for="barcode">Barcode</label>
    <input type="text" id="barcode" class="form-control">
</div>
</div>
<div class="form-horizontal row">
    <div class="form-group col-xs-6">
        <button id="checkBarcode" class="btn btn-primary">Check Barcode</button>
    </div>
</div>

    <table class="table table-striped"  id="itemListing">
        <tr>
            <th>Barcode</th>
            <th>Item Name</th>
        </tr>
        @foreach($items as $item)
        <tr>
            <td><input type="checkbox" value="{{$item->id}}" id="item-{{$item->barcode}}"> {{$item->barcode}}</td>
            <td>{{$item->Name}}</td>
        </tr>
        @endforeach
    </table>
<div class="alert alert-info" role="alert"><b>Missing items?</b> Make sure all items have a valid barcode printed/generated.</div>
    <div class="row">
        <div class="col-xs-11">
            <button class="btn btn-primary btn-lg pull-right" id="submitIDs">Proceed <span class="glyphicon glyphicon-arrow-right"></span></button>
        </div>
    </div>
    <form action="{{ URL::route('admin.order.pickup.createPickup') }}" method="post" id="hiddenIDform">
        {{ csrf_field() }}
        <input type="hidden" name="pickupuserid" value="{{$student->id}}">
        <input type="hidden" name="ItemIDs" id="HiddenIDs">
    </form>
@stop
@section('javascript_bottom')
<script>
$( document ).ready(function() {
    //Bind the barcode lookup button with the barcode lookup function
    $("#checkBarcode").on('click',function(){
        checkBarcode();
    });
    $("#barcode").bind('keypress',function(e){
        checkBarcodeEnter(e);
    });
    $("#submitIDs").on('click',function(){
        submitIDs();
    });
    $("#barcode").focus();
});
    function checkBarcodeEnter(e){
        var code = e.keyCode || e.which;
        if(code == 13) { //Enter keycode
            checkBarcode();
        }
    }

    function checkBarcode(){
        //Take the input from the barcode field and find the associated input line
        var barcode = $("#barcode").val();
        //Find the input on the page with said barcode
        if($("#item-"+barcode).length){
            $("#item-"+barcode).attr('checked',true);
        }else{
            alert("This person cannot pickup that specific item.");
        }
        $("#barcode").val("");
        $("#barcode").focus();
    }

    function submitIDs(){
        //Read the barcodes
        var itemIDs = [];
        $('#itemListing input:checked').each(function() {
            itemIDs.push($(this).attr('value'));
        });
        $('#HiddenIDs').attr('value',JSON.stringify(itemIDs));
        $("#hiddenIDform").submit();
    }

</script>
@stop
