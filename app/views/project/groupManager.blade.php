@extends('layouts.master')
@include('layouts.autocomplete')
@include('layouts.fontawesome')
@include('layouts.maskMoney')
@section('content')
    @include('project.projectNavigation')
    <div class="page-header">
        <h1>Group Manager <button id="addGroupBtn" class="btn btn-default">+ New Group</button></h1>
    </div>
    <div id="groupManagerContainer"></div>

<!-- New Group Modal -->
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
                            <label for="uniqueID">Unique Identifier:</label>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">{{$class->UID}}-</span>
                            <input type="text" id="uniqueID" class="form-control" placeholder="Enter unique identifier" aria-describedby="basic-addon1">
                        </div>
                        </div>
                    </p>
                    <p>
                        <div class="form-group">
                            <label for="fullName">Full Name:</label>
                            <input type="text" id="fullName" class="form-control" placeholder="Enter group's full name">
                        </div>
                    </p>
                    <p>
                    <div class="form-group">
                        <label for="group-description">Description:</label>
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
<!-- Funds Transfer Modal -->
    <div class="modal fade" id="fundsTransferModal" tabindex="-1" role="dialog" aria-labelledby="fundsTransferModal" aria-hidden="true">>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Transfering Funds to <span id="transfer-funds-group-name"></span></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="fund-transfer-group-id" value="">
                    <div class="row">
                        <div class="col-xs-5" style="text-align: center;"><i class="fa fa-university fa-4x"></i></div>
                        <div class="col-xs-2"></div>
                        <div class="col-xs-5" style="text-align: center;"><i class="fa fa-group fa-4x"></i></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="form-group">
                                <label for="funding-account">Account Balance:</label>
                                <input type="text" id="funding-account-balance" class="form-control" style="text-align: center;" disabled>
                            </div>
                        </div>
                        <div class="col-xs-2" style="text-align: center;"><i class="fa fa-usd fa-2x text-success"></i>  <i class="fa fa-arrow-right fa-2x"></i></div>
                        <div class="col-xs-5">
                            <div class="form-group">
                                <label for="group-account">Transfer Amount:</label>
                                <input type="text" id="group-account-transfer" class="form-control" style="text-align: center;">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="funds-transfer-cancel">Cancel</button>
                    <button type="button" class="btn btn-success" id="transfer-funds-complete">Transfer Funds</button>
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
        var accountBalance = 0.00;

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
            $("#funds-transfer-cancel").on('click',function(){
                clearTransferFunds();
            });
            $("#transfer-funds-complete").on('click',function(){
               transferFunds();
            });

            //Next we need to run an ajax request and build the groups panel on the page
            refreshAccountBalance();
            loadEnrolledStudents();
            reloadGroups();
            //Apply mask money
            $('#group-account-transfer').maskMoney({thousands:',', decimal:'.', allowZero:true, prefix: '$ '});


        });
        function refreshAccountBalance(){
            //Get the primary project's account balance
            $.ajax("{{URL::route('project.api.getAccountBalance',$class->id)}}")
                .done(function(data){
                    accountBalance = data;
                    $("#funding-account-balance").val("$"+accountBalance);
                    $("#parentProjectAccountBalance").html("Account: $"+accountBalance);
                })
                .fail(function(){
                    alert("Could not get Account Balance from server. Try reloading the page");
                });
        }

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
            .done(function( data ) {
                        console.log(data);
                if(data['error']){
                    alert(data['errorarr']);
                }else {
                    $("#newGroupModal").modal("hide");
                    clearGroupModal();
                    reloadGroups();
                }
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
                            '<div class="pull-right">Group Account Balance: $'+internalData[0][3]+'</div>'+
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
                            $("#group-"+internalData[0][0]+"-controlPanel").append('<div class="row"><div class="col-xs-10"><div class="form-group">'+
                            '<label for="enrollStudent">Enroll Student</label>'+
                            '<input type="text" class="form-control" id="enrollStudent-'+internalData[0][0]+'" placeholder="Start typing students name...">'+
                            '</div></div><div class="col-xs-2"><br><button id="transfer-funds-'+internalData[0][0]+'" class="btn btn-success">Transfer Funds</button></div></div>');
                            $("#enrollStudent-"+internalData[0][0]).autocomplete({
                                lookup: enrolledStudentArray,
                                onSelect: function (suggestion) {
                                    registerStudent(internalData[0][0], suggestion.data, suggestion.value);
                                    $("#enrollStudent-"+internalData[0][0]).val("");
                                }
                            });
                            $("#transfer-funds-"+internalData[0][0]).on('click',function(){
                                showTransferFunds(internalData[0][0], internalData[0][1]);
                            });

                            $("#group-collapse-"+internalData[0][0]+"-body").append('<table class="table-condensed table-striped table" id="students-'+internalData[0][0]+'"><tr><th>First Name, Last Name</th><th>Email</th><th>-</td></tr></table>');
                            //Next loop the internalData[1] array for all the students
                            $.each(internalData[1], function(index,student){
                                //Add data to the table
                                var studentUsername = student[3].substr(0,student[3].lastIndexOf("@"));
                                $("#students-"+internalData[0][0]).append('<tr id="student-table-row-'+internalData[0][0]+'-'+studentUsername+'"><td>'+student[1]+', '+student[2]+'</td><td><a href="mailto:'+student[3]+'">'+student[3]+'</a></td><td><button class="btn btn-danger" type="button" onclick="dropStudent(\''+internalData[0][0]+'\',\''+student[3]+'\')">'+
                                'Remove <span class="badge">X</span>'+
                                '</button></td></tr>');
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

        function registerStudent(groupid, studentEmail, studentFullName){
            $.ajax({
                method: "POST",
                url: "{{URL::route('project.api.enrollStudent',$class->id)}}",
                data: { groupid: groupid, studentEmail: studentEmail }
            })
                    .done(function( data ) {
                        if(data['error'] == true){
                            alert(data['errorarr']);
                        }else if(data['success']){
                            //Successfully added person, make another line for this person in the table and provide all needed code for deleting
                            var studentUsername = studentEmail.substr(0,studentEmail.lastIndexOf("@"));
                            $("#students-"+groupid).append('<tr id="student-table-row-'+groupid+'-'+studentUsername+'"><td>'+studentFullName+'</td><td><a href="mailto:'+studentEmail+'">'+studentEmail+'</a></td><td><button class="btn btn-danger" type="button" onclick="dropStudent(\''+groupid+'\',\''+studentEmail+'\')">'+
                            'Remove <span class="badge">X</span>'+
                            '</button></td></tr>');
                        }
                    });


        }

        function dropStudent(groupid, studentEmail){
            $.ajax({
                method: "POST",
                url: "{{URL::route('project.api.dropStudent',$class->id)}}",
                data: { groupid: groupid, studentEmail: studentEmail }
            })
                    .done(function( data ) {
                        if(data['error'] == true){
                            alert(data['errorarr']);
                        }else if(data['success']){
                            //Successfully added person, make another line for this person in the table and provide all needed code for deleting
                            var studentUsername = studentEmail.substr(0,studentEmail.lastIndexOf("@"));
                            $("#student-table-row-"+groupid+"-"+studentUsername).remove();
                        }
                    });

        }

        function showTransferFunds(groupid, groupname){
            //Show the modal
            refreshAccountBalance();
            $("#transfer-funds-group-name").html(groupname);
            $("#group-account-transfer").val("$ 0.00");
            $("#fund-transfer-group-id").val(groupid);
            $("#fundsTransferModal").modal("show");
        }

        function clearTransferFunds(){
            //Clear data out of the transfer funds modal
            $("#group-account-transfer").val("$ 0.00");
            $("#transfer-funds-group-name").html("");
            $("#fund-transfer-group-id").val(0);
        }

        function transferFunds(){
            //process data in the modal
            var transferAmount = $("#group-account-transfer").maskMoney('unmasked')[0];
            if(transferAmount > accountBalance){
                transferAmount = accountBalance;
            }
            var groupname = $("#transfer-funds-group-name").html();
            var transferfunds = confirm("Are you sure you want to transfer $"+transferAmount.toFixed(2)+" to "+groupname+"?");
            var groupid = $("#fund-transfer-group-id").val();
            if(transferfunds) {
                $.ajax({
                    method: "POST",
                    url: "{{URL::route('project.api.transferFunds',$class->id)}}",
                    data: {groupid: groupid, amount: transferAmount}
                })
                        .done(function (data) {
                            if (data['error'] == true) {
                                alert(data['errorarr']);
                            } else if (data['success']) {
                                //Funds successfully transferred
                                reloadGroups();

                            }
                        });
                refreshAccountBalance();
                clearTransferFunds();
                $("#fundsTransferModal").modal("hide");

            }
        }

        var substringMatcher = function(strs) {
            return function findMatches(q, cb) {
                var matches, substrRegex;

                // an array that will be populated with substring matches
                matches = [];

                // regex used to determine if a string contains the substring `q`
                substrRegex = new RegExp(q, 'i');

                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each(strs, function(i, str) {
                    if (substrRegex.test(str)) {
                        // the typeahead jQuery plugin expects suggestions to a
                        // JavaScript object, refer to typeahead docs for more info
                        matches.push({ value: str });
                    }
                });

                cb(matches);
            };
        };
    </script>
@stop





