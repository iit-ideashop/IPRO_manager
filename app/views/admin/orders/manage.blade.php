@extends('layouts.master')
@include('layouts.dataTables')
@section('content')

<h1 class="page-header">Order Overview - {{ $order->Description }} 

<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    Actions <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="#">Add Approved Pickups</a></li>
    <li><a href="#">Add shipping costs</a></li>
    <li><a href="#">Create Pickup</a></li>
  </ul>
</div>



</h1>
<div class="panel panel-default">
    <div class="panel-heading">Order Information</div>
  <div class="panel-body">
      <div class="row">
          <div class="col-sm-6">User: {{ $order_user->FirstName }} {{ $order_user->LastName }}</div>
          <div class="col-sm-6">Total: ${{ number_format($order->OrderTotal,2) }}</div>
      </div>
      <div class="row">
          <div class="col-sm-6">Email: {{ $order_user->Email}}</div>
          <div class="col-sm-6">Status: {{$order->getStatus() }}</div>
      </div>
    <div class="row">
          <div class="col-sm-6">Project: {{ $project->UID }}</div>
          <div class="col-sm-6">Created: {{ date('D F jS Y, g:i a',strtotime($order->created_at)) }}</div>
      </div>
      
      <div class="row">
          <div class="col-sm-6">Project Name: {{ $project->Name }}</div>
          <div class="col-sm-6">Last Modified: {{ date('D F jS Y, g:i a',strtotime($order->updated_at)) }}</div>
      </div>
      @if($order->Phone != '')
      <div class="row">
          <div class="col-sm-6">Contact: {{ $order->Phone }}</div>
          <div class="col-sm-6"></div>
      </div>
      @endif
      
  </div>
</div>

<div class="panel-group" id="Project">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#Project" href="#ProjectCollapse">
          Project Overview - {{ $project->UID }}
        </a>
      </h4>
    </div>
    <div id="ProjectCollapse" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">Name: {{ $project->Name }}</div>
                <div class="col-sm-6">Remaining Balance: ${{ number_format($account->Balance,2)}}</div>
            </div>
            <br>
            <h3 class="page-header">Budget Information</h3>
            <div class="table-responsive">
    <table class="table table-striped" id="BudgetListing">
        
        <thead>
        <tr>
            <th>id</th>
            <th>Terms</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tfoot>
                    <tr>
            <th>id</th>
            <th>Terms</th>
            <th>Amount</th>
        </tr>
        </tfoot>
        <tbody>
            @foreach($budgets as $budget)
            <tr>
                <td>{{$budget->id }}</td>
                <td>
                    {{ $budget->Terms }}
                </td>
                <td>{{ $budget->Amount }}</td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
            </div>
        </div>
    </div>
  </div>
</div>


<div class="panel-group" id="orderNotes">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#orderNotes" href="#orderNotesCollapse">
          Order Notes
          
          
          
        </a>
      </h4>
    </div>
    <div id="orderNotesCollapse" class="panel-collapse collapse">
        <div class="panel-body">
                <button class="btn btn-default" data-toggle="modal" data-target="#newNoteModal">
  New Note
                </button></br></br>
            <div class="table-responsive">
    <table class="table table-striped" id="noteListing">
        
        <thead>
        <tr>
            <th>id</th>
            <th>Refers To</th>
            <th>Note</th>
            <th>Entered by</th>
            <th>Date</th>
        </tr>
        </thead>
        <tfoot>
                    <tr>
            <th>id</th>
            <th>Refers To</th>
            <th>Note</th>
            <th>Entered by</th>
            <th>Date</th>
        </tr>
        </tfoot>
        <tbody>
            @foreach($notes as $note)
            <tr>
                <td>{{$note->id }}</td>
                <td>
                    @if($note->ItemID != 0)
                        {{ $note->refersTo()}}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $note->Notes }}</td>
                <td>{{ User::getFullNameWithId($note->EnteredBy)}}</td>
                <td>{{ date('D F jS Y, g:i a',strtotime($note->created_at))}} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
            </div>
        </div>
    </div>
  </div>
</div>



<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">Order Items</div>
 <div class="panel-body">
 <div class="table-responsive">
    <table class="table table-striped" id="itemListing">
        <thead>
            <tr>
                
                <th>id</th>
                <th></th>
                <th>Name</th>
                <th>Link</th>
                <th>Part Number</th>
                <th>Justification</th>
                <th>Cost</th>
                <th>Quantity</th>
                <th>Total Cost</th>
                <th>Returning</th>
                <th>Status</th>
                <th>Updated</th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th></th>
                <th>Name</th>
                <th>Link</th>
                <th>Part Number</th>
                <th>Justification</th>
                <th>Cost</th>
                <th>Quantity</th>
                <th>Total Cost</th>
                <th>Returning</th>
                <th>Status</th>
                <th>Updated</th>
                <th>With Selected:  
                
                <div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    Actions <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="#" id="massChangeStatus">Change Order Status</a></li>
    <li><a href="#" id="massReturning">Mark Returning</a></li>  
    <li><a href="#" id="massLabelPrint">Print Labels</a></li>
  </ul>
</div>
                
                
                
                </th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td><input type="checkbox" name="{{ $item->Name}}" id="item-{{ $item->id }}" value="{{ $item->id}}"></th>
                <td><a href="{{$item->Link}}" target="_blank">{{ $item->Name}}</a></td>
                <td>{{ $item->Link }}</td>
                <td>{{ $item->PartNumber }}</td>
                <td>{{ $item->Justification }}</td>
                <td>${{ number_format($item->Cost,2) }}</td>
                <td>{{ $item->Quantity}}</td>
                <td>${{ number_format($item->TotalCost,2) }}</td>
                @if($item->Returning)
                <td>Yes</td>
                @else
                <td>No</td>
                @endif
                <td>{{ $item->getStatus() }}
                @if($item->barcode != null)
                    <h6><span class="glyphicon glyphicon-barcode"></span>Barcode ok<h6>
                @endif</td>
                <td>{{ date('D F jS Y, g:i a',strtotime($item->updated_at))}}</td>
                <td>
                                    <div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    Actions <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="#edit{{$item->id}}Modal" data-toggle="modal">Edit</a></li>
    <li>
        <a onclick="changeStatus({{ $item->id }})">
        Change Order Status</a></li>
    @if($item->Returning)
     <li><a href="/admin/items/{{$item->id}}/markNotReturning">Mark Not Returning</a></li>
    @else
    <li><a href="/admin/items/{{$item->id}}/markReturning">Mark Returning</a></li>
    @endif
    <li><a href="#{{$item->id}}DeleteModal" data-toggle="modal">Delete item</a></li>
    <li><a href="#" onClick="printLabel({{$item->id}})">Print Labels</a></li>
  </ul>
</div>
                    
                    </th>
            </tr>
            @endforeach
        </tbody>
    </table>
 </div>
 </div>
</div>
<!-- MODALS!!! -->
@foreach($items as $item)
<div class="modal fade" id="edit{{$item->id}}Modal" tabindex="-1" role="dialog" aria-labelledby="{{$item->id}}EditModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="{{$item->id}}EditModalLabel">Edit</h4>
      </div>
      <div class="modal-body">
        
          {{ Form::model($item,array('route'=> array('admin.item.edit',$item->id),'class'=>'form-horizontal'))}}
  <div class="form-group">
      {{ Form::label('Name','Item Name', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-10">
      {{ Form::text('Name',null,array('class'=>'form-control')) }}
    </div>
  </div>
  <div class="form-group">
      {{ Form::label('Link','Link', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-10">
      {{ Form::text('Link',null,array('class'=>'form-control')) }}
    </div>
  </div>
          <div class="form-group">
      {{ Form::label('PartNumber','Part Number', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-10">
      {{ Form::text('PartNumber',null,array('class'=>'form-control')) }}
    </div>
  </div>
          <div class="form-group">
      {{ Form::label('Cost','Cost', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-10">
      {{ Form::text('Cost',null,array('class'=>'form-control')) }}
    </div>
  </div>
          <div class="form-group">
      {{ Form::label('Quantity','Quantity', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-10">
      {{ Form::text('Quantity',null,array('class'=>'form-control')) }}
    </div>
  </div>
          <div class="form-group">
      {{ Form::label('TotalCost','Total Cost', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-10">
      {{ Form::text('TotalCost',null,array('class'=>'form-control')) }}
    </div>
  </div>
          <div class="form-group">
      {{ Form::label('Justification','Justification', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-10">
      {{ Form::textarea('Justification',null,array('class'=>'form-control')) }}
    </div>
  </div>          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {{ Form::submit('Update',array('class'=>'btn btn-default'))}}{{ Form::close() }}
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="{{$item->id}}ChangeStatusModal" tabindex="-1" role="dialog" aria-labelledby="{{$item->id}}ChangeStatusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="{{$item->id}}ChangeStatusModalLabel">Change Order Status</h4>
      </div>
      <div class="modal-body">
        {{ Form::model($item,array('route'=> array('admin.item.statusChange',$item->id),'class'=>'form-horizontal'))}}
          <div class="form-group">
      {{ Form::label('Status','Status', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-10">
        
        <select name="Status" class="form-control">
            @foreach($itemStatuses as $itemStatus)
            <option value="{{ $itemStatus->id}}"
                    @if($item->Status == $itemStatus->id)
                    selected="selected"
                    @endif
                    >{{ $itemStatus->status }}</option>
            @endforeach
        </select>
      
    </div>
  </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {{ Form::submit('Change Status',array('class'=>'btn btn-default'))}}{{ Form::close() }}
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="{{$item->id}}DeleteModal" tabindex="-1" role="dialog" aria-labelledby="{{$item->id}}DeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="{{$item->id}}DeleteModalLabel">Delete</h4>
      </div>
      <div class="modal-body">
        
          {{ Form::model($item,array('route'=> array('admin.item.delete',$item->id),'class'=>'form-horizontal'))}}
          Are you sure you want to delete  the item <b>{{ $item->Name}}</b>?<br>
          <h6>Items should usually be marked as Cancelled and not deleted</h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        {{ Form::submit('Yes',array('class'=>'btn btn-danger'))}}{{ Form::close() }}
      </div>
    </div>
  </div>
</div>
@endforeach


<!-- Order note modal-->
<div class="modal fade" id="newNoteModal" tabindex="-1" role="dialog" aria-labelledby="newNoteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="newNoteModalLabel">New Order Note</h4>
      </div>
      <div class="modal-body">
        {{ Form::open(array('route'=>array('admin.order.createNote',$order->id),'class'=>'form-horizontal')) }} 
        <div class="form-group">
    {{ Form::label('Note','Note', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-8">
        {{ Form::textarea('Note','',array('class'=>'form-control')) }}
    </div>
</div>
        
        <div class="form-group">
    {{ Form::label('ItemID','Refers to:', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-8">
        <select name="ItemID" class="form-control">
            <option value="0">None</option>
            @foreach($items as $item)
            <option value="{{ $item->id }}">{{ $item->Name }}</option>
            @endforeach
        </select>
         </div>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {{ Form::submit('Create Note',array('class'=>'btn btn-default'))}}
        {{ Form::close()}}
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="massStatusModal" tabindex="-1" role="dialog" aria-labelledby="massStatusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="massStatusModalLabel">Update Status</h4>
      </div>
      <div class="modal-body">
          You will be updating the following items:
          <ul id="massStatusUpdateUL">
          </ul>
          {{ Form::open(array('route'=> 'admin.items.statusChange','class'=>'form-horizontal'))}}
          <div class="form-group">
      {{ Form::label('Status','Status', array('class'=>'col-sm-2 control-label')) }} 
    <div class="col-sm-10">
          <select name="Status" class="form-control">
              @foreach($itemStatuses as $itemStatus)
              <option value="{{$itemStatus->id}}">{{ $itemStatus->status}}</option>
              @endforeach
          </select>
          <input name="massItemID" id="massItemID" type="hidden" value="">
    </div>
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {{ Form::submit('Change Status',array('class'=>'btn btn-default'))}}{{ Form::close() }}
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="massReturnModal" tabindex="-1" role="dialog" aria-labelledby="massReturnModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="massReturnModalLabel">Mark Returning</h4>
      </div>
      <div class="modal-body">
          You will be marking the following items as returning:
          <ul id="massReturnUL">
          </ul>
          {{ Form::open(array('route'=> 'admin.items.markReturning'))}}
          <input name="massItemID" id="massItemIDReturn" type="hidden" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {{ Form::submit('Change Status',array('class'=>'btn btn-default'))}}{{ Form::close() }}
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="PrintLabelModal" tabindex="-1" role="dialog" aria-labelledby="PrintLabelModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="PrintLabelModalLabel">Print Labels</h4>
      </div>
      <div class="modal-body">
          You will be printing the following labels:
          <ul id="PrintLabelUL">
          </ul>
          {{ Form::open(array('route'=> 'admin.items.printLabels','target'=>'_blank','id'=>'labelPrint'))}}
          <input name="items" id="massLabelID" type="hidden" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {{ Form::submit('Print Labels',array('class'=>'btn btn-default'))}}{{ Form::close() }}
      </div>
    </div>
  </div>
</div>
@stop
                                            
@section('javascript_bottom')
<script>
$(document).ready( function () {
    $('#itemListing').DataTable({
         "order": [[ 0, "asc" ]],
         "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
            },
            {
                "targets": [ 3 ],
                "visible": false,
            }
            
        ]
    } );
    $('#noteListing').DataTable({
         "order": [[ 0, "desc" ]],
         "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
            }
        ]
    } );
    $('#BudgetListing').DataTable({
         "order": [[ 0, "desc" ]],
         "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
            }
        ]
    } );
    
    $('#massChangeStatus').click(function(){
        showMassStatusModal();
    });
    $('#massReturning').click(function(){
        showMassReturnModal();
    });
    $("#massLabelPrint").click(function(){
       massPrintLabels(); 
    });
} );

function showMassStatusModal(){
    //Let's read all of the checked boxes
    var itemNames = [];
    var itemIDs = [];
    $('#itemListing input:checked').each(function() {
        itemNames.push($(this).attr('name'));
        itemIDs.push($(this).attr('value'));
    });
    //Show the modal
    if(itemIDs.length == 0){
        alert('Select atleast 1 Item');
        return false;
    }
    $('#massStatusUpdateUL').html(' ');
    for(var i=0; i<itemNames.length;i++){
        $('#massStatusUpdateUL').append($('<li>'+ itemNames[i]+'</li>'));
    }
    $('#massItemID').attr('value',JSON.stringify(itemIDs));
    $('#massStatusModal').modal('show');
}

function changeStatus(id){
    $("#item-"+id).attr('checked',true);
    showMassStatusModal();
}
    
function markReturning(id){
    $("#item-"+id).attr('checked',true);
    showMassReturnModal();
}
function printLabel(id){
    $("#item-"+id).attr('checked',true);
    massPrintLabels();
}

function showMassReturnModal(){
    //Let's read all of the checked boxes
    var itemNames = [];
    var itemIDs = [];
    $('#itemListing input:checked').each(function() {
        itemNames.push($(this).attr('name'));
        itemIDs.push($(this).attr('value'));
    });
    //Show the modal
    if(itemIDs.length == 0){
        alert('Select atleast 1 Item');
        return false;
    }
    $('#massReturnUL').html(' ');
    for(var i=0; i<itemNames.length;i++){
        $('#massReturnUL').append($('<li>'+ itemNames[i]+'</li>'));
    }
    $('#massItemIDReturn').attr('value',JSON.stringify(itemIDs));
    $('#massReturnModal').modal('show');
    
}

function massPrintLabels(){
    //Grab the labels we want to print
    //Let's read all of the checked boxes
    var itemNames = [];
    var itemIDs = [];
    $('#itemListing input:checked').each(function() {
        itemNames.push($(this).attr('name'));
        itemIDs.push($(this).attr('value'));
    });
    //Update the modal form, we are not actually going to show the modal :/
    $('#massLabelID').attr('value',JSON.stringify(itemIDs));
    $('#labelPrint').submit();
}
</script>
@stop

