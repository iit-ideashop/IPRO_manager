<div class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#project-nav-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand">IPRO Day</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="project-nav-collapse">
                <ul class="nav navbar-nav">
                    <li
                            @if(Route::currentRouteName() == 'admin.iproday')
                                class="active"
                            @endif
                            ><a href="{{ URL::route("admin.iproday") }}">Active Registration</a></li>
                    <li
                    @if(Route::currentRouteName() == 'admin.iproday.peopleschoice')
                        class="active"
                            @endif
                            ><a href="{{ URL::route("admin.iproday.peopleschoice") }}">Peoples Choice</a></li>
                    <li>
                    <a href="#">Peoples Choice Results</a></li>

                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </div>