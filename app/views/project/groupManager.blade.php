@extends('layouts.master')

@section('content')
    @include('project.projectNavigation')
    <div class="page-header">
        <h1>Group Manager <button id="addGroupBtn" class="btn btn-default">+ New Group</button></h1>
    </div>

@stop
@section('javascript_bottom')
    <script>
        $(document).ready(function(){
            //Run on page startup
            //Associate buttons on the page with functions
            $("#addGroupBtn").on('click',function(){
                addNewGroup();
            });

        });

        function addNewGroup(){

        }
    </script>
@stop


