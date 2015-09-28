<div class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#project-nav-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand">{{ $class->UID }}</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="project-nav-collapse">
                <ul class="nav navbar-nav">
                    <li
                            @if(Route::currentRouteName() == 'project.dashboard')
                                class="active"
                            @endif
                            ><a href="{{URL::route('project.dashboard',$class->id)}}">Dashboard</a></li>
                    <li
                            @if(Route::currentRouteName() == 'project.orders')
                                class="active"
                            @endif
                            ><a href="{{ URL::route('project.orders',$class->id) }}">Orders</a></li>
                    <li
                            @if(Route::currentRouteName() == 'project.roster')
                                class="active"
                            @endif
                            ><a href="{{ URL::route('project.roster',$class->id) }}">Roster</a></li>
                    <li
                    @if(Route::currentRouteName() == 'project.tableTent')
                        class="active"
                            @endif
                            ><a href="{{ URL::route('project.tableTent',$class->id) }}">Digital Table Tent</a></li>

                    @if($class->Semester == $activeSemester->id)
                    <li
                    @if(Route::currentRouteName() == 'project.printSubmission')
                        class="active"
                            @endif
                            ><a href="{{ URL::route('project.printSubmission',$class->id) }}">Submit PDFs</a></li>

                    @endif
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Actions <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{URL::route("project.order.new",$class->id)}}"><i class="fa fa-plus"></i> New Order</a></li>
                            <li><a href="{{ URL::route("project.newScrumReport",$class->id) }}"><i class="fa fa-plus"></i> New Scrum Report</a></li>
                            <li><a  href="{{ URL::route("project.allScrumReports",$class->id) }}"><i class="fa fa-search"></i> View Scrum Reports</a></li>

                            <!-- <li><a href="#">New Budget Request</a></li> -->
                            @if(($class->getAccessLevel() > 1) || (Auth::user()->isAdmin))
                                <li class="dropdown-header">Instructor Actions</li>
                                <li><a href="{{ URL::route('project.groupmanager',$class->id) }}">Group Management</a></li>
                            @endif
                        </ul>
                    </li>
                </ul>

                <div class="pull-right navbar-brand" id="parentProjectAccountBalance">Account: ${{ number_format($account->Balance,2) }}</div>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </div>