@extends('layouts.registration')
@include('layouts.sigpad')
@section('content')
    <h1>Pickup a Package</h1>
    <img id="signature">

    <form method="post" action="" class="sigPad">
        <div class="sig sigWrapper">
            <canvas class="pad" id="sigpad" width="550" height="200"></canvas>
            <input type="hidden" name="output" class="output">
        </div>
    </form>


@stop
@section('javascript_bottom')
    <script>
        var options = {
            drawOnly : true,
            lineWidth: 0,
            lineMargin: 0
        };
        $(document).ready(function() {
            $('.sigPad').signaturePad(options);

            var img = new Image();
            var imgData = "data:image/png;base64,{{$sigpad}}";
            img.src = imgData;
            var c=document.getElementById("sigpad");
            var ctx=c.getContext("2d");
            ctx.drawImage(img,0,0,550,200);
        });
    </script>
@stop


