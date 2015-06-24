<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Armour R&D - IPRO Manager</title>
        <link rel="stylesheet" href="{{ URL::asset('packages/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('packages/bootstrap/css/dashboard.css') }}">
        @section('stylesheets') 
        @show
        <script src="{{ URL::asset('packages/bootstrap/js/jquery-1.11.1.js')}}"></script>
        <script src="{{ URL::asset('packages/bootstrap/js/bootstrap.min.js') }}"></script>
        @section('javascript')
        @show
    </head>
    <body>
        @section('navbar')
        @include('layouts.navigation')
        @show
        <div class="container-fluid">
            <div class="row">
                @include('layouts.sidebar')
                @section('override_content_div')
                        <div class=" col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    @show
                    @if(Session::has('error'))
                    @foreach(Session::get('error') as $oneError)
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        {{ $oneError }}</div>
                    @endforeach
                    @endif
                    @if(Session::has('success'))
                    @foreach(Session::get('success') as $oneSuccess)
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        {{ $oneSuccess }}</div>
                    @endforeach
                    @endif

                    @section('content')

                    @show
                </div>
            </div></div>

        @section('javascript_bottom')
        @show
    </body>
</html>