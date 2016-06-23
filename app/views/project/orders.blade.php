@extends('layouts.master')
@include('layouts.dataTables')

@section('content')
    @include('project.projectNavigation')
    @include('model_tables.orders')

@stop

@section('javascript_bottom')
    <script>
        $('#Orders').DataTable({
            "order": [[0, "desc"]],
            "lengthMenu": [5, 10, 15, 25, 50],
            "columnDefs": [
                {
                    "targets": [0],
                    "visible": false,
                },

            ]
        });
    </script>
@stop
