<html>
    <head>
        <style>
            .label{
                width: 2.1in;
                height: 3.9in;
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
    <body onload="window.print();">
        <div class="label">
        <br />
            <h1 class="nomarginheadings">{{ $printSubmission->file_type }}</h1><br /><br />
            <h5 class="nomarginheadings">{{ $printSubmission->original_filename }}</h5><br />
            <h5 class="nomarginheadings">Job_{{ $printSubmission->id }}.pdf</h5><br />
            <h5 class="nomarginheadings">{{ date('D F jS Y, g:i a', strtotime($printSubmission->created_at)) }}</h5>
            <div class="barcode">
                <img id="bc{{ $printSubmission->barcode }}"><br />
                
            </div> 
       </div>
    </body>
    <script>
        $("#bc{{ $printSubmission->barcode }}").JsBarcode("{{$printSubmission->barcode}}",{format:"CODE39",displayValue:true,fontSize:20,quite:5, width:1,height:35});
        (function() {
            var afterPrint = function() {
                window.close();
            };

            if (window.matchMedia) {
                var mediaQueryList = window.matchMedia('print');
                mediaQueryList.addListener(function(mql) {
                    if (!mql.matches) {
                        afterPrint();
                    }
                });
            }
            window.onafterprint = afterPrint;
        }());
    </script>
</html>