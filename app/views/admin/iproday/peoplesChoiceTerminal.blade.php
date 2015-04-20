<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ URL::asset('packages/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('packages/bootstrap/css/dashboard.css') }}">
    @section('stylesheets')
        @include('layouts.fontawesome')
    @show
    <style>
        html, body, .container {
            height: 100%;
        }
        .container {
            display: table;
            vertical-align: middle;
            width: 85%;
        }
        .vertical-center-row {
            display: table-cell;
            vertical-align: middle;
        }
    </style>
    <script src="{{ URL::asset('packages/bootstrap/js/jquery-1.11.1.js')}}"></script>
    <script src="{{ URL::asset('packages/bootstrap/js/bootstrap.min.js') }}"></script>
    @section('javascript')
    @show
</head>
<body>
<<<<<<< Updated upstream
<div class="container">
    <div class="row vertical-center-row">
        <div class="col-lg-12">
            <div class="row jumbotron" id="firstpage">
                <div class="col-xs-10 col-xs-offset-1 " style="text-align: center">
                    <h2>Welcome to IPRO Day Spring 2015</h2><br><br><h2>Peoples Choice Voting</h2><br><br>
                    <h2>Please scan your ID below to continue</h2><br>
                    <a class="btn btn-primary" onclick="runJscript();">Continue</a></div>
            </div>
            <div class="row jumbotron" id="secondpage" style="display: none;">
                <div class="col-xs-10 col-xs-offset-1 " style="text-align: center">
                    <h2>One moment as we validate your ID...</h2><br><br>
                    <i class="fa fa-cog fa-4x fa-spin"></i><br><br><br>
                    <a class="btn btn-primary" onclick="runJscript2();">Continue</a></div>
            </div>
            <div class="row jumbotron" id="thirdpage" style="display: none;">
                <div class="col-xs-12" style="text-align: center">
                    <h2>Please select your vote for Peoples Choice</h2><br><br>
                    <div class="row">
                        <div class="col-xs-3"><a href="#" class="btn btn-default btn-lg" onclick="runJscript3();"><h1>Track 1</h1></a></div>
                        <div class="col-xs-3"><a href="#" class="btn btn-default btn-lg" onclick="runJscript3();"><h1>Track 2</h1></a></div>
                        <div class="col-xs-3"><a href="#" class="btn btn-default btn-lg" onclick="runJscript3();"><h1>Track 3</h1></a></div>
                        <div class="col-xs-3"><a href="#" class="btn btn-default btn-lg" onclick="runJscript3();"><h1>Track 4</h1></a></div>
                    </div><br>
                    <a class="btn btn-primary" onclick="runJscript2();">Continue</a></div>
            </div>
            <div class="row jumbotron" id="fourthpage" style="display: none;">
                <div class="col-xs-12" style="text-align: center">
                    <h2>Please select your vote for Peoples Choice</h2><br><br>
                    <a href="#" class="btn btn-default btn-lg"><h2>IPRO 397-200-A Rock-Your-Baby</h2></a><br><br>
                    <a href="#" class="btn btn-default btn-lg"><h2>IPRO 397-200-B I like chicken</h2></a><br><br>
                    <a href="#" class="btn btn-default btn-lg"><h2>IPRO 397-200-C I prefer pasta</h2></a><br><br>
                    <a href="#" class="btn btn-default btn-lg"><h2>IPRO 397-200-D Cheese puffs!</h2></a><br><br>
                    <a href="#" class="btn btn-default btn-lg"><h2>IPRO 397-200-E John the toolman</h2></a><br><br>
                    <a href="#" class="btn btn-default btn-lg"><h2>IPRO 397-200-F Rock-Your-Cat</h2></a><br><br>

                    </div>
            </div>
=======

<div class="container">
    <div class="row vertical-center-row">
        <div class="col-lg-12">
            <div class="row jumbotron" id="indexPage">
                <div class="col-xs-10 col-xs-offset-1 " style="text-align: center">
                    <h2>Welcome to IPRO Day Spring 2015</h2><br><br><h2>Peoples Choice Voting</h2><br><br>
                    <img src="{{ URL::asset("packages/images/hawkcard.jpg") }}"><br>
                    <i class="fa fa-caret-down fa-4x"></i><br>
                    <h2>Please scan your ID to continue</h2><br>
                        {{ Form::open() }}
                        {{ Form::text("firstName",null,array("id"=>"firstName","autocomplete"=>"off","style"=>"opacity:100","tabindex"=>"1")) }}
                        {{ Form::text("lastName",null,array("id"=>"lastName","autocomplete"=>"off","style"=>"opacity:100","tabindex"=>"2")) }}
                        {{ Form::text("idNumber",null,array("id"=>"idnumber","autocomplete"=>"off","style"=>"opacity:100","tabindex"=>"3")) }}
                        {{ Form::close() }}
                </div>
            </div>
            <div class="row jumbotron" id="loadingPage" style="display: none;">
                <div class="col-xs-10 col-xs-offset-1 " style="text-align: center">
                    <h2>One moment as we validate your ID...</h2><br><br>
                    <i class="fa fa-cog fa-4x fa-spin"></i></div>
            </div>
            <div class="row jumbotron" id="errorPage" style="display: none;">
                <div class="col-xs-10 col-xs-offset-1 " style="text-align: center">
                    <h2 id="errorH2"></h2></div>
            </div>
            <div class="row jumbotron" id="trackListingPage" style="display: none;">
                <div class="col-xs-12" style="text-align: center">
                    <h2>Please select your vote for Peoples Choice <span id="trackListingFirstName"></span></h2><br><br>
                    <div class="row" id="trackListingDiv"></div>
                </div>
            </div>
            <div class="row jumbotron" id="projectListing" style="display: none;">
                <div class="col-xs-12" style="text-align: center">
                    <h2>Please select your vote for Peoples Choice</h2><br><br>
                        <div id="projectListingDiv"></div>
                    </div>
            </div>
            <div class="row jumbotron" id="confirmVotePage" style="display: none;">
                <div class="col-xs-12" style="text-align: center">
                    <h2>Please confirm your selection</h2><br><br>
                    <h2>You are voting for:</h2><br>
                    <div id="confirmVoteProject"></div>
                </div>
            </div>
            <div class="row jumbotron" id="voteSubmittingPage" style="display: none;">
                <div class="col-xs-12" style="text-align: center">
                    <h2>Submitting your vote...</h2><br><br>
                    <i class="fa fa-cog fa-4x fa-spin"></i>
                </div>
            </div>

>>>>>>> Stashed changes
        </div>
    </div>
</div>

<script>
<<<<<<< Updated upstream
    function runJscript(){
        $("#firstpage").slideUp();
        $("#secondpage").slideDown().delay(400);
    }
    function runJscript2(){
        $("#secondpage").slideUp().delay(400);
        $("#thirdpage").slideDown().delay(400);
    }
    function runJscript3(){
        $("#thirdpage").slideUp().delay(400);
        $("#fourthpage").slideDown().delay(400);
    }

</script>


=======
    var firstname, lastname, idnumber, tracks, vote_projectid;
    var id_scanned = false;
    $(document).on("ready",function(){
        $("#firstName").focus();
        $("#idnumber").keypress(function(e) {
            if(e.which == 13) {
                id_scanned = true;
                $("#indexPage").slideUp();
                $("#loadingPage").slideDown().delay(400);
                //Submit the data for testing
                var firstName = $("#firstName").val();
                var lastName = $("#lastName").val();
                var idnumber = $("#idnumber").val();
                //We have to take this data and perform an ajax request to get the ipro listing and validate the ID.
                var fd = new FormData();
                fd.append("firstName",firstName);
                fd.append("lastName",lastName);
                fd.append("idnumber",idnumber);
                $.ajax({
                    url: '{{ URL::route('admin.iproday.api.validateUser') }}',
                    type: 'POST',
                    data: fd,
                    cache: false,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data){
                       //We need to process this data and customize the page to fit the needs of a single voter
                        if(data.app_error){
                            //There was an error with the request, display the error and redirect away to the home page again
                            show_error(data.app_error);
                        }else{
                            //Otherwise there were no errors!!
                            //Lets take the returned data and add it to our JS data pool
                            firstname = data.firstName;
                            lastname = data.lastName;
                            idnumber = data.idnumber;
                            tracks = data.tracks;
                            showTrackListing();
                        }
                    },
                    error: function(data, textStatus){
                        console.log(data);
                        console.log(textStatus);
                        alert("there was an error processing your request");
                    }
                });
                alert("Name"+firstName+" Last:"+lastName+" ID:"+idnumber);
            }
        });
        $("#firstName").on("blur",function(event){
            if((!id_scanned) && (event.relatedTarget == null)){
                console.log(event.relatedTarget);
                $("#firstName").focus();
            }
        });
    });


    function show_error(error){
        $("#loadingPage").slideUp(400);
        $("#voteSubmittingPage").slideUp(400)
        $("#errorH2").html(error);
        $("#errorPage").slideDown(400);
        window.setTimeout(function(){
            // Move to a new location or you can do something else
            window.location.href = "{{ URL::route("admin.iproday.peopleschoice.terminal") }}";
        }, 3900);
    }

    function showTrackListing(){
        //We are going to load up the track listing from scratch
        $("#trackListingDiv").html("");
        $("#trackListingFirstName").html(firstname);
        $.each(tracks, function(key,value){
            $("#trackListingDiv").append('<div class="col-xs-3"><button class="btn btn-default btn-lg btn-block" onClick="showProjectListing('+key+')"><h2>Track '+key+'</h2></button></div>');
        });
        $("#trackListingDiv").append('<div class="col-xs-3"><button class="btn btn-default btn-lg btn-block" onClick="show_error(\'Canceling Voting <i class=&quot;fa fa-cog fa-2x fa-spin &quot;></i>\');"><h2>Cancel Voting</h2></button></div>');
        $("#loadingPage").slideUp(400);
        $("#projectListing").slideUp(400);
        $("#confirmVotePage").slideUp(400);
        $("#trackListingPage").slideDown(400);
    }

    function showProjectListing(trackid){
        //<a href="#" class="btn btn-default btn-lg"><h2>IPRO 397-200-A Rock-Your-Baby</h2></a><br><br>
        $("#projectListingDiv").html("");
        $.each(tracks, function(key,value){
            if(key == trackid){
                var classes = value["classes"];
                console.log(classes);
                $.each(classes, function(arrayindex,projectdata){
                    $("#projectListingDiv").append('<button class="btn btn-default btn-lg" onClick="selectProject('+projectdata.id+')"><h2>'+projectdata.UID+' '+projectdata.Name+'</h2></button><br><br>');
                });
            }
        });
        $("#projectListingDiv").append('<button class="btn btn-default btn-lg" onclick="showTrackListing()"><h2><i class="fa fa-chevron-left"></i> Back to Track Listing</h2></button><br><br>');
        $("#trackListingPage").slideUp(400);
        $("#projectListing").slideDown(400);
    }

    function selectProject(projectid){
        $.each(tracks, function(key,value){
                var classes = value["classes"];
                $.each(classes, function(arrayindex,projectdata){
                    if(projectdata.id == projectid){
                        $("#confirmVoteProject").html('<h2>'+projectdata.UID+' '+projectdata.Name+'</h2><br>' +
                        '<button class="btn btn-primary btn-lg btn-block" onClick="submitVote('+projectid+')">Confirm</button><br><br>' +
                        '<button class="btn btn-danger btn-lg btn-block" onClick="showTrackListing()">Back to Track Listing</button>');
                    }
                });
        });
        $("#projectListing").slideUp(400);
        $("#confirmVotePage").slideDown(400);
    }

    function submitVote(projectid){
        $("#confirmVotePage").slideUp(400);
        $("#voteSubmittingPage").slideDown(400)
        //Make an ajax call to cast the vote
        window.setTimeout(function(){
            show_error("Thanks for Voting!");
        },1500);
    }
</script>
>>>>>>> Stashed changes
</body>
</html>

