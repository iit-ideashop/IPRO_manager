@section('javascript') 
@parent
        <script src="{{ URL::asset('packages/bootstrap/js/jquery.signaturepad.min.js') }}"></script>
        <!--[if lt IE 9]><script src="{{ URL::asset('packages/bootstrap/js/flashcanvas.js') }}"></script><![endif]-->
        <script src="{{ URL::asset('packages/bootstrap/js/json2.min.js') }}"></script>
@stop
@section('stylesheets') 
@parent
        <link rel="stylesheet" href="{{ URL::asset('packages/bootstrap/css/jquery.signaturepad.css') }}">
@stop
