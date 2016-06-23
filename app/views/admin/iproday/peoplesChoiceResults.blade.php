@extends('layouts.master')
@include('layouts.fontawesome')
@include("layouts.dataTables")
@include('layouts.autocomplete')
@section('content')
    @include("admin.iproday.iprodayNavigation")
    <table class="table table-condensed" id="resultsTable">
        <thead>
            <th>Project</th>
            <th>Votes</th>
        </thead>
        @foreach($resultArray as $result)
            <tr>
                <td>{{ Project::getProjectUID($result["ProjectID"]) }}</td>
                <td>{{ $result["votes"] }}</td>
            </tr>
        @endforeach
    </table>


@stop
@section('javascript_bottom')
<script>
        $(document).ready( function () {
            $('#resultsTable').DataTable({
                "order": [[1, "desc"]],
                "columnDefs": [
                    {
                        "targets": [1],
                        "visible": true
                    }
                ]
            });
        });
</script>
    @stop