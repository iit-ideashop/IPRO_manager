@extends('layouts.master');
@section('javascript')
    @include('layouts.handsontable')
    <script src="{{ URL::asset('packages/bootstrap/js/jquery.numeric.js') }}"></script>
@stop
@section('content')
    <h1 class="page-header">General Ledger Editor</h1>
    <div class="panel-group" id="addGL" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="addGLtab">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#addGL" href="#addGLCollapse" aria-expanded="true"
                       aria-controls="addGLCollapse">
                        Add General Ledger Entry
                    </a>
                </h4>
            </div>
            <div id="addGLCollapse" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="addGLtab">
                <div class="panel-body">
                    {{ Form::open() }}
                    <div class="form-group">
                        <label for="entryType">Entry Type</label>
                        {{Form::select('entryType', array('RECONCILE' => 'Reconcile', 'BUDGET' => 'Budget', 'OTHER' => 'Other', 'REIMBURSEMENT' => 'Reimbursement'),null,array('class'=>'form-control'))}}
                    </div>
                    <div class="form-group">
                        <label for="creditdebit">Debit/Credit</label>
                        {{Form::select('creditdebit', array('CREDIT' => 'Credit Account(+)', 'DEBIT' => 'Debit Account(-)'),null,array('class'=>'form-control'))}}
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            {{Form::text('amount',null,array('class'=>'form-control','id'=>'amount'))}}
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-default">Create GL Entry</button>
                    </div>
                    </form>
                </div>
            </div>
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <th>Entry Type</th>
                    <th>Credit(+)</th>
                    <th>Debit(-)</th>
                    <th>New Balance</th>
                    <th></th>
                </tr>
                @foreach($gl_data as $gl)
                    <tr>
                        <td>{{$gl->id}}</td>
                        <td>{{$gl->EntryType}}</td>
                        <td>${{number_format($gl->Credit,2)}}</td>
                        <td>${{number_format($gl->Debit,2)}}</td>
                        <td>${{number_format($gl->NewAccountBalance,2)}}</td>
                        @if($gl->EntryType == "ORDER")
                            <td><a href='{{URL::to('/admin/orders/')}}/{{$gl->EntryTypeID}}'
                                   class='btn btn-default'>View</a></td>
                        @else
                            <td></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
    </div>


    <!--
    <div id="glTable"></div>
    -->

@stop

@section('javascript_bottom')
    <script>
        $(document).ready(function () {
            $("#amount").numeric({decimal: ".", negative: false});
        });
        var data = [
                @foreach($gl_data as $gl)
            {
                entryid: "{{$gl->id}}",
                entrytype: "{{$gl->EntryType}}",
                credit: "${{number_format($gl->Credit,2)}}",
                debit: "${{number_format($gl->Debit,2)}}",
                newbal: "${{number_format($gl->NewAccountBalance,2)}}",
                @if($gl->EntryType == "ORDER")
                    action: "<a href='{{URL::to('/admin/orders/')}}/{{$gl->EntryTypeID}}' class='btn btn-default'>View</a>"
                @else
                    action: ""
                @endif
            },
            @endforeach
        ];
        var container = $("#glTable");
        container.handsontable({
            data: data,
            colHeaders: ["EntryID", "Entry Type", "Credit(+)", "Debit(-)", "New Balance", "-"],
            stretchH: 'all',
            columns: [
                {data: "entryid"},
                {data: "entrytype"},
                {data: "credit"},
                {data: "debit"},
                {data: "newbal"},
                {data: "action", renderer: "html"}

            ],
            width: 800,
            height: 500,
            cells: function (row, col, prop) {
                var cellProperties = {};
                cellProperties.readOnly = true; //make cell read-only if it is first row or the text reads 'readOnly'
                return cellProperties;
            }
        });
    </script>
@stop
