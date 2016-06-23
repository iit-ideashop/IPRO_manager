@section('javascript') 
@parent
        <script src="{{ URL::asset('packages/bootstrap/js/jquery.handsontable.full.min.js') }}"></script>
@stop
@section('stylesheets') 
@parent
        <link rel="stylesheet" href="{{ URL::asset('packages/bootstrap/css/jquery.handsontable.full.min.css') }}">
@stop
