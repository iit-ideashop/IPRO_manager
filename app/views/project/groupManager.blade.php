@extends('layouts.master')

@section('content')
    @include('project.projectNavigation')
    <div class="page-header">
        <h1>Group Manager <button id="addGroupBtn" class="btn btn-default">+ New Group</button></h1>
    </div>
    <div id="groupManagerContainer"></div>


    <div class="modal fade" id="newGroupModal" tabindex="-1" role="dialog" aria-labelledby="newGroupModal" aria-hidden="true">>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Creating a new Group</h4>
                </div>
                <div class="modal-body">
                    <p>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Unique Identifier:</label>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">{{$class->UID}}-</span>
                            <input type="text" id="uniqueID" class="form-control" placeholder="Enter unique identifier" aria-describedby="basic-addon1">
                        </div>
                        </div>
                    </p>
                    <p>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Full Name:</label>
                            <input type="text" id="fullName" class="form-control" placeholder="Enter group's full name">
                        </div>
                    </p>
                    <p>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Description:</label>
                        <textarea class="form-control" id="group-description"></textarea>
                    </div>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="group-create-cancel">Cancel</button>
                    <button type="button" class="btn btn-primary" id="group-create">Create Group</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


@stop
@section('javascript_bottom')
    <script>
        //global vars
        var groupData = null;
        var enrolledStudentArray = null;
        var ProjectName = "{{$class->UID}}";

        $(document).ready(function(){
            //Run on page startup
            //Associate buttons on the page with functions
            $("#addGroupBtn").on('click',function(){
                addNewGroup();
            });
            $("#group-create").on('click',function(){
                createGroup();
            });
            $("#group-create-cancel").on('click',function(){
                clearGroupModal();
            });

            //Next we need to run an ajax request and build the groups panel on the page

            reloadGroups();
            loadEnrolledStudents();

        });


        function clearGroupModal(){
            //Clear the new group modal
            $("#uniqueID").val("");
            $("#fullName").val("");
            $("#group-description").val("");
        }
        //Take the data from the modal, verify it, show errors and perform an ajax request to project.api.addGroup
        function createGroup(){
            var groupUID = $("#uniqueID").val();
            var groupName = $("#fullName").val();
            var groupDesc = $("#group-description").val();
            //let's do an ajax post.
            $.ajax({
                method: "POST",
                url: "{{URL::route('project.api.addGroup',$class->id)}}",
                data: { groupUID: groupUID, groupName: groupName, groupDesc: groupDesc }
            })
            .done(function( msg ) {
                alert( "Data Saved: " + msg );
                $("#newGroupModal").modal("hide");
                clearGroupModal();
                reloadGroups();
            });

        }


        //Show a modal to add a new group
        function addNewGroup(){
            //Show the modal
            $("#newGroupModal").modal("show");


        }

        function reloadGroups(){
            //Clear the old data from the div container
            $("#groupManagerContainer").html("Reloading..");
            //Need to run an ajax request to pull down the group details from the server
            $.ajax("{{URL::route('project.api.getGroups',$class->id)}}")
                    .done(function(data){
                        groupData = data;
                        //Once the groups have been reloaded we need to loop through the groups data and create some html elements
                        //All data goes into #groupManagerContainer
                        //Let's clear out GroupManagerContainer in case there is something in there already
                        $("#groupManagerContainer").html("");
                        //Loop the array
                        $.each(groupData,function(groupUID, internalData){
                            //Generate a new Collapse element inside the GroupManager container and give it some defaults
                            $("#groupManagerContainer").append('<div class="panel-group" id="group-collapse-'+internalData[0][0]+'" role="tablist" aria-multiselectable="true">'+
                            '<div class="panel panel-default">'+
                            '<div class="panel-heading" role="tab" id="group-collapse-heading-'+internalData[0][0]+'">'+
                            '<h4 class="panel-title">'+
                            '<a data-toggle="collapse" data-parent="#group-collapse-'+internalData[0][0]+'" href="#group-collapse-link-'+internalData[0][0]+'" aria-expanded="true" aria-controls="group-collapse-link-'+internalData[0][0]+'">'+
                                    internalData[0][1]+
                            '</a>'+
                            '</h4>'+
                            '</div>'+
                            '<div id="group-collapse-link-'+internalData[0][0]+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="group-collapse-heading-'+internalData[0][0]+'">'+
                            '<div class="panel-body" id="group-collapse-'+internalData[0][0]+'-body">'+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '</div>');
                            //Add the control panel for the group
                            $("#group-collapse-"+internalData[0][0]+"-body").append('<div class="panel panel-default">'+
                            '<div class="panel-body" id="group-'+internalData[0][0]+'-controlPanel">'+
                            '</div>'+
                            '</div><br>');
                            //Build the control panel
                            $("#group-"+internalData[0][0]+"-controlPanel").append('<div class="form-group">'+
                            '<label for="enrollStudent">Enroll Student</label>'+
                            '<input type="text" class="form-control" id="enrollStudent" placeholder="Start typing students name...">'+
                            '</div>');


                            $("#group-collapse-"+internalData[0][0]+"-body").append('<table class="table-condensed table-striped table" id="students-'+internalData[0][0]+'"><tr><th>First Name, Last Name</th><th>Email</th></tr></table>');
                            //Next loop the internalData[1] array for all the students
                            $.each(internalData[1], function(index,student){
                                //Add data to the table
                                $("#students-"+internalData[0][0]).append('<tr><td>'+student[1]+', '+student[2]+'</td><td><a href="mailto:'+student[3]+'">'+student[3]+'</a></td></tr>');
                            });
                        });

                    })
                    .fail(function(){
                        alert("Could not fetch groups from server. Try reloading the page");
                    });

        }

        function loadEnrolledStudents() {
            $.ajax("{{URL::route('project.api.getStudents',$class->id)}}")
                    .done(function (data) {
                        enrolledStudentArray = data;
                    })
                    .fail(function () {
                        alert("Could not fetch students from server. Try reloading the page");
                    });
        }
    </script>
@stop





