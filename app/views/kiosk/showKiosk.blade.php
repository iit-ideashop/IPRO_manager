@extends('layouts.registration')
@section('javascript')
    <script src="{{ URL::asset('packages/bootstrap/js/fastclick.js') }}"></script>
@stop
@section('javascript_bottom')
    <script>
        var attachFastClick = Origami.fastclick;
        attachFastClick(document.body);
        function addNumber(element){
            document.getElementById('PINbox').value = document.getElementById('PINbox').value+element.value;
            if(document.getElementById('PINbox').value.length == 4){
                document.forms["PINform"].submit();
            }
        }
        function clearForm(element){ document.getElementById('PINbox').value = ""; }
    </script>
    @stop
@section('stylesheets')
    <style>
        input:focus,
        select:focus,
        textarea:focus,
        button:focus {
            outline: none;
        }
        #PINcode {
            width:500px;
            margin:auto;
        }
        .headerCode{
            text-align:center;
        }
        #PINbox {
            margin: 3%;
            width: 100%;
            font-size: 4em;
            text-align: center;
            border: none;
            border-top: 2px solid #506CE8;
            border-bottom: 2px solid #506CE8;
            background: #fff;
        }
        .PINbuttons{
            width:300px;
            margin:auto;
        }
        .PINbutton {
            background: #fff;
            color: #506CE8;
            border: 2px solid #506CE8;
            border-radius: 50%;
            font-size: 1.5em;
            text-align: center;
            width: 75px;
            height: 75px;
            margin: 5px 11px;
        }
        .clear, .enter {
            font-size: 1em;
        }
        .PINbutton:hover {
            background: #fff;
            border: 2px solid #FF5A59;
            color: #FF5A59;
        }
    </style>
    @stop
@section('content')
<h1 class="page-header">IPRO Pickup</h1>
<div id="PINcode">
    <h3 class='headerCode'>Enter your 4 digit pickup code</h3>
    {{ Form::open(array('route'=>'kiosk.pickupPackage','method'=>'POST','name'=>'PINform','autocomplete'=>'off','id'=>'PINform')) }}
    {{ Form::text("PINcode",null,array('id'=>'PINbox','maxlength'=>'4')) }}
        <div class='PINbuttons'>
            <input type='button' class='PINbutton' name='1' value='1' id='1' onClick=addNumber(this); />
            <input type='button' class='PINbutton' name='2' value='2' id='2' onClick=addNumber(this); />
            <input type='button' class='PINbutton' name='3' value='3' id='3' onClick=addNumber(this); />
            <input type='button' class='PINbutton' name='4' value='4' id='4' onClick=addNumber(this); />
            <input type='button' class='PINbutton' name='5' value='5' id='5' onClick=addNumber(this); />
            <input type='button' class='PINbutton' name='6' value='6' id='6' onClick=addNumber(this); />
            <input type='button' class='PINbutton' name='7' value='7' id='7' onClick=addNumber(this); />
            <input type='button' class='PINbutton' name='8' value='8' id='8' onClick=addNumber(this); />
            <input type='button' class='PINbutton' name='9' value='9' id='9' onClick=addNumber(this); />
            <input type='button' class='PINbutton clear' name='-' value='clear' id='-' onClick=clearForm(this); />
            <input type='button' class='PINbutton' name='0' value='0' id='0' onClick=addNumber(this); />
            <input type='button' class='PINbutton enter' name='-' value='-' id='-' />
        </div>
    {{Form::close()}}
</div>
@stop



