@section('javascript') 
@parent
        <script src="{{ URL::asset('packages/bootstrap/js/jquery.treetable.js') }}"></script>
@stop
@section('stylesheets') 
@parent
        <link rel="stylesheet" href="{{ URL::asset('packages/bootstrap/css/jquery.treetable.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('packages/bootstrap/css/jquery.treetable.theme.default.css') }}">
@stop
