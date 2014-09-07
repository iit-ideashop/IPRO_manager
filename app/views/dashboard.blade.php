@extends('layouts.master')

@section('content')
        
          <h1 class="page-header">Dashboard</h1>
          <p>Welcome to the IPRO Manager dashboard. This application allows you to manage your IPRO budget, make purchases and track your spending account. You can view enrolled IPROs in the sidebar to the left. If you have any questions or are having issues accessing certain parts of your IPRO please email us at <a href="mailto:ipro@iit.edu">ipro@iit.edu</a></p>
          @include('model_tables.my_orders') 
        
@stop