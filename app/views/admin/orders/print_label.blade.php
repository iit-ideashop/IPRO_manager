<html>
    <head>
        <style>
            .label{
                width: 2.3in;
                height: 4in;
                page-break-before: always;
                text-align: center;
                position: relative;
            }
            .label-first{
                width: 2.3in;
                height: 4in;
                text-align: center;
                position: relative;
            }
            .barcode{
                position: absolute; 
                bottom: 20px;
                left: 12px;
                margin: auto;
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
            <h1>IPRO</h1>
            <h1>{{ $project->UID }}</h1>
            <h3>{{ User::getFullNameWithId($order->PeopleID)}}<h3>
            <h3>{{$item->Name}}</h3>
            <div class="barcode">
                <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($item->barcode,'UPCA') }}" alt="barcode"><br>
                {{ $item->barcode }}
            </div> 
       </div>
        @endforeach
    </body>
    
    
    
</html>