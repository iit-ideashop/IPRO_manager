@section('navbar')
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">IPRO Management</a>
        </div>
                @if(Auth::check())
                {{-- Navbar menu if the user is logged in --}}                
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
              <li><a>Welcome {{ $user->FirstName }}</a></li>
            <li><a href="{{ URL::route('dashboard') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('help') }}">Help</a></li>
            <li><a href="{{ URL::route('logout') }}">Logout</a></li>
          </ul>
        </div>
                @else
                {{-- Navbar menu if the user is not logged in --}}
                <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
              <li><a>Welcome Guest!</a></li>
            <li><a href="/authenticate">Login</a></li>
          </ul>
        </div>
                @endif
                
                
      </div>
    </div>

@stop