{{-- ONLY SHOW THIS BAD BOY TO LOGGED IN USERS --}}
@if(Auth::check())
<div class="col-sm-3 col-md-2 sidebar">
            <p>Enrolled IPROs</p>
          <ul class="nav nav-sidebar">
            @foreach ($navigation['classes'] as $class)
                <li  
                @if ($class->id == @$selected_class)
                   class="active"
                @endif
                        ><a href="/project/{{ $class->id}}">{{ $class->Name }}</a></li>
            @endforeach
          </ul>
            @if(isset($navigation['admin']))
            <p>Administration</p>
            <ul class='nav nav-sidebar'>
                @foreach ($navigation ['admin'] as $links)
                <li><a href='{{ $links['link'] }}'>{{ $links['text'] }}</a></li>
                @endforeach
            </ul>
            @endif
            
</div>
@endif