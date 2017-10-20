@extends('layouts.master')
@section('javascript')
        <script src="{{ URL::asset('packages/bootstrap/js/jquery.maskMoney.min.js') }}"></script>
        <script src="{{ URL::asset('packages/bootstrap/js/jquery.numeric.js') }}"></script>
@stop
@section('content')
<h2 class='page-header'>New Order</h2>
{{ Form::open() }}
<div class="panel panel-default">
    <div class="panel-heading">Order Information</div>
  <div class="panel-body">
      <div class="row">
          <div class="col-sm-6">User: {{ Auth::user()->FirstName }} {{ Auth::user()->LastName }}</div>
          <div class="col-sm-6"></div>
      </div>
      <div class="row">
          <div class="col-sm-6">Email: {{ Auth::user()->Email }}</div>
          <div class="col-sm-6">Project: {{ $project->UID }}</div>
      </div>
      <div class="row">
                    
                    <div class="col-sm-offset-6 col-sm-6">Project Name: {{ $project->Name }}</div>
      </div>
      <div class="row">
          <div class="col-sm-6">Order Name: {{ Form::text('Description',null,array('class'=>'form-control','tabindex'=>'1','required'))}}</div>
          <div class="col-sm-6">Phone number: {{ Form::text('Phone',null,array('class'=>'form-control','tabindex'=>'2')) }}</div>
      </div>
      <br>
          <div class="panel-group" id="approvedPickup">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#approvedPickup" href="#approvedPickups" class="collapsed">
          Approved Order Pickups
        </a>
      </h4>
    </div>
    <div id="approvedPickups" class="panel-collapse collapse" style="height: 0px;">
      <div class="panel-body">
      The following people are approved to pickup items from this order
      <ul>
          <li>{{ Auth::user()->FirstName }} {{ Auth::user()->LastName }}</li>
          <li>
              <select name="firstApproved">
                  <option value="0">Select a team member</option>
                  @foreach($enrolledStudents as $enrolledStudent)
                    <option value="{{$enrolledStudent->id}}">{{$enrolledStudent->FirstName}} {{$enrolledStudent->LastName}}</option>
                  @endforeach
              </select>
          </li>
          <li>
              <select name="secondApproved">
                  <option value="0">Select a team member</option>
                  @foreach($enrolledStudents as $enrolledStudent)
                      <option value="{{$enrolledStudent->id}}">{{$enrolledStudent->FirstName}} {{$enrolledStudent->LastName}}</option>
                  @endforeach
              </select>
          </li>
      </ul>
      </div>
    </div>
  </div>
      </div>
  </div>
</div>


<div class="panel panel-default">
    <div class="panel-heading">Order Items</div>
  <div class="panel-body">
      
      <ul class="list-group" id="itemGroup">
          
        
      </ul>
      <a class="btn btn-default" id="addMoreItemsBtn" tabindex="90000"> <span class="glyphicon glyphicon-plus"></span> Add more items</a>
  </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">Order Summary</div>
  <div class="panel-body">
      <div clas="row">
          <div class="col-sm-4"><h3>Available Funds: ${{ number_format($account->Balance,2) }}</h3></div>
          <div class="col-sm-4"><h3>Grand Total: $<span id="orderTotal"></span></h3></div>
          <div class="col-sm-4">{{ Form::submit('Place Order',array('class'=>'btn btn-primary','tabindex'=>'90001','id'=>'placeOrderButton')) }}</div>
          
          
      </div>
  </div>
</div>
{{ Form::close() }}



@stop

@section('javascript_bottom')
<script>
    $(document).ready(function(){
        //Bind various page controls to actions
        $("#addMoreItemsBtn").click(function(){
            addMoreItems();
        });
        
        updateTotalCost();
        @if(Session::has('items'))
        @foreach(Session::get('items') as $item)
            addMoreItems('{{ $item->Name }}', '{{ $item->Link }}', '{{ $item->PartNumber }}', '{{ $item->Cost }}', '{{ $item->Quantity }}','{{ $item->Shipping }}', '{{ $item->Justification }}');
        @endforeach
            recalculateAllTC();
        @else
        addMoreItems();
        @endif

        
    });
    
    function updateTotalCost(){
        //Update the total cost span
        $('#orderTotal').html("0.00");
    }
    function destroyItem(divtag){
        $(divtag).remove();
    }
    function addMoreItems(itemName,itemLink,itemPN,itemCost,itemQuantity,shippingCost,justification){
        itemName = itemName || '';
        itemLink = itemLink || '';
        itemPN = itemPN || '';
        itemCost = itemCost || '0';
        itemQuantity = itemQuantity || '1';
        justification = justification || '';
        shippingCost = shippingCost || '0';
        var ItemID = countItems();
        var startTabIndex = (ItemID * 7) + 100;
        $('#itemGroup').append('<li class="list-group-item item" id="item'+ ItemID+'">' + 
              '<div class="row">' +
                  '<div class="col-sm-8">' +
                    '{{ Form::label('Names','Item Name', array('class'=>'col-sm-4 control-label')) }}' + 
                    '<input type="text" name="Names[]" class="form-control" value="'+itemName+'" required tabindex="'+startTabIndex+'">' +
          '</div>' +
          '<div class="col-sm-4">'+
          '<a class="btn btn-warning" onClick="destroyItem(\'#item'+ItemID+'\')">Remove Item</a>' +
                '</div>'+
              '</div>' +
              '<div class="row">' +
                  '<div class="col-sm-8">' +
                        '{{ Form::label('Links','Link', array('class'=>'col-sm-2 control-label')) }}' +
                        '<input type="text" name="Links[]" class="form-control" value="'+itemLink+'" required tabindex="'+(startTabIndex + 1)+'" placeholder="http://www.amazon.com/Accoutrements-12027-Horse-Head-Mask/dp/B003G4IM4S/ref=sr_1_1?s=apparel&ie=UTF8&qid=1408188850&sr=1-1&keywords=horse+mask">' +
                    '</div>' +
                  '<div class="col-sm-4">'+
                     '{{ Form::label('Costs','Cost', array('class'=>'col-sm-2 control-label')) }}' + 
                    '<input type="text" class="form-control cost" name="Costs[]" itemID="'+ItemID+'" id="Cost'+ItemID+'" value="'+itemCost+'" onKeyUp="recalculateSingleTC('+ItemID+')" required tabindex="'+(startTabIndex + 4)+'">' +
                '</div>' +
              '</div>' +
          '<div class="row">' +
              '<div class="col-sm-8">' +
                    '{{ Form::label('PartNumbers','Part Number', array('class'=>'col-sm-4 control-label')) }}' + 
                    '<input type="text" name="PartNumbers[]" class="form-control" value="'+itemPN+'" tabindex="'+(startTabIndex + 2)+'" >' +
          '</div>' +
              '<div class="col-sm-4">'+
                     '{{ Form::label('Quantitys','Quantity', array('class'=>'col-sm-2 control-label')) }}' + 
                    '<input type="text" name="Quantitys[]" class="form-control quantity" itemID="'+ItemID+'" onKeyUp="recalculateSingleTC('+ItemID+')" id="Quantity'+ItemID+'" value="'+itemQuantity+'" required tabindex="'+(startTabIndex + 5)+'">' +
                '</div>' +
              '</div>' +
          '<div class="row">' +
                  '<div class="col-sm-8">'+
                     '{{ Form::label('Justifications','Justification', array('class'=>'col-sm-2 control-label')) }}' + 
                    '<textarea name="Justifications[]" rows="4" class="form-control" required tabindex="'+(startTabIndex + 3)+'">'+justification+'</textarea>' +
                '</div>' +
                '<div class="col-sm-4">' +
        '<label for="shippingCost'+ItemID+'" class="col-sm-4 control-label">Shipping</label>'+
        '<input type="text" id="shippingCost'+ItemID+'" itemID="'+ItemID+'" name="shippingCosts[]"  value="'+shippingCost+'" required onKeyUp="recalculateSingleTC('+ItemID+')" tabindex="'+(startTabIndex + 6)+'" class="form-control">' +
        '<label for="totalCost'+ItemID+'" class="col-sm-4 control-label">Total Cost</label>'+
        '<input type="text" id="totalCost'+ItemID+'" class="form-control" disabled>' +

                '</div>' +
    '</li>');
        //Initialize our money plugin
        $('#Cost'+ItemID).maskMoney({thousands:',', decimal:'.', allowZero:true, prefix: '$ '});
        $('#totalCost'+ItemID).maskMoney({thousands:',', decimal:'.', allowZero:true, prefix: '$ '});
        $('#shippingCost'+ItemID).maskMoney({thousands:',', decimal:'.', allowZero:true, prefix: '$ '});
        $('#Cost'+ItemID).maskMoney('mask');
        $('#totalCost'+ItemID).maskMoney('mask');
        $('#shippingCost'+ItemID).maskMoney('mask');
        $('#Quantity'+ItemID).numeric();
        recalculateSingleTC(ItemID);
    }
    
    function recalculateSingleTC(itemid){
       $('#totalCost'+itemid).attr('disabled',false);
       var Cost = $('#Cost'+itemid).maskMoney('unmasked')[0];
        var shipping = $('#shippingCost'+itemid).maskMoney('unmasked')[0];
       var Qty = $('#Quantity'+itemid)[0].value;
       $('#totalCost'+itemid).maskMoney('mask',(Cost * Qty)+shipping);
       $('#totalCost'+itemid).attr('disabled',true);
       recalculateAllTC();
    }
    var totalBalance = {{ $account->Balance }};
    function recalculateAllTC(){
        var grandtotal = 0;
       $(".cost").each(function(){
           //Calculate all the costs

           var singleCost = ($(this).maskMoney('unmasked')[0] * $('#Quantity'+$(this).attr('itemid'))[0].value) + $('#shippingCost'+$(this).attr('itemid')).maskMoney('unmasked')[0];
           grandtotal = grandtotal + singleCost;
       });
       $('#orderTotal').html(grandtotal.toFixed(2));
       if(grandtotal > totalBalance){
           $('#placeOrderButton').attr('disabled',true);
           $('#placeOrderButton').attr('value','Over Budget');
       }else{
           $('#placeOrderButton').attr('disabled',false);
           $('#placeOrderButton').attr('value','Place Order');
       }
    }
    var itemCounter = 0;
function countItems(){
    itemCounter = itemCounter + 1;
    return itemCounter;
}

</script>
@stop