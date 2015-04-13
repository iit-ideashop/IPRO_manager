<div class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#project-nav-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand">Printing Management</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="project-nav-collapse">
                <ul class="nav navbar-nav">
                    @if(Auth::user()->isAdmin)
                        <li
                        @if(Route::currentRouteName() == 'printing.awaitingApproval')
                            class="active"
                                @endif
                                ><a href="{{URL::route('printing.awaitingApproval')}}">Awaiting Approval</a></li>
                    @endif
                    <li
                            @if(Route::currentRouteName() == 'printing.awaitingPrint')
                                class="active"
                            @endif
                            ><a href="{{ URL::route('printing.awaitingPrint') }}">Awaiting Print</a></li>
                    <li
                            @if(Route::currentRouteName() == 'printing.printed')
                                class="active"
                            @endif
                            ><a href="{{ URL::route('printing.printed') }}">Printed</a></li>
                        @if(Auth::user()->isAdmin)
                            <li
                            @if(Route::currentRouteName() == 'printing.checkin')
                                class="active"
                                    @endif
                                    ><a href="{{URL::route('printing.checkin')}}">Check-in Poster</a></li>
                            <li
                            @if(Route::currentRouteName() == 'printing.posterpickup')
                                class="active"
                                    @endif
                                    ><a href="{{URL::route('printing.posterpickup')}}">Pickup Poster</a></li>
                            <li
                            @if(Route::currentRouteName() == 'printing.projectReport')
                                class="active"
                                    @endif
                                    ><a href="{{URL::route('printing.projectReport')}}">Project Report</a></li>
                        @endif
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </div>