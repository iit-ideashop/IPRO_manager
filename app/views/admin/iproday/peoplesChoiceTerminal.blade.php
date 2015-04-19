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
        </div>
    </div>
</div>

<script>
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


</body>
</html>

