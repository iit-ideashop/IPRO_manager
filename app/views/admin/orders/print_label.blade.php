<html>
    <head>
        <style>
            .label{
                width: 4in;
                height: 2.1in;
                page-break-before: always;
                text-align: center;
                position: relative;
            }
            .label-first{
                width: 4in;
                height: 2.1in;
                text-align: center;
                position: relative;
            }
            .nomarginheadings{
                margin:0px;
            }
            .barcode{
                width: 4in;
                position: absolute; 
                bottom: 20px;
                
                margin: auto;
            }
            .barcode img {
                
                margin-left: auto;
                margin-right: auto;
            }
        </style>
        <script>
            $window.load(function(){
                window.print();
            });
            
        </script>
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
                <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($item->barcode,'MSI') }}" alt="barcode"><br>
                {{ $item->barcode }}
            </div> 
       </div>
        @endforeach
    </body>
    
    
    
</html>