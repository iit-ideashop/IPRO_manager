<html>
    <head>
        <style>
            .label{
                width: 2.1in;
                height: 4in;
                page-break-before: always;
                text-align: center;
                position: relative;
            }
            .label-first{
                width: 2.1in;
                height: 4in;
                text-align: center;
                position: relative;
            }
            .nomarginheadings{
                margin:0px;
            }
            .barcode{
                width: 2.1in;
                position: absolute; 
                bottom: 20px;
                
                margin: auto;
            }
            .barcode img {
                margin-left: auto;
                margin-right: auto;
            }
        </style>
        <script src="{{ URL::asset('packages/bootstrap/js/jquery-1.11.1.js')}}"></script>
        <script src="{{ URL::asset('packages/bootstrap/js/JsBarcode.all.min.js')}}"></script>
    </head>
    <body onload="window.print()">
        <?php $first = true;?>
        @foreach($items as $item)
            <?php 
            $order = $item->Order()->first();
            $project = $order->Project()->first(); 
            ?>
        @if($first)
        <div class="label-first">
        <?php $first = false; ?>
        @else
        <div class="label">
        @endif
        <br>
            <h1 class="nomarginheadings">{{ $project->UID }}</h1>
            <h3 class="nomarginheadings">{{ User::getFullNameWithId($order->PeopleID)}}</h3>
            <h3 class="nomarginheadings">{{$item->Name}}</h3>
            <div class="barcode">
                <img id="bc{{ $item->barcode }}"><br>
                
            </div> 
       </div>
        @endforeach
    </body>
    <script>
        @foreach($items as $item)
        $("#bc{{ $item->barcode }}").JsBarcode("{{$item->barcode}}",{format:"CODE39",displayValue:true,fontSize:20,quite:5, width:1,height:35});
        @endforeach
    </script>
</html>