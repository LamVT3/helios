<!-- HEADER -->
<header id="header">
    <div id="logo-group">

        <!-- PLACE YOUR LOGO HERE -->
        <span id="logo"> <img src="{{ asset('img/logo2.png') }}" alt="Helios"> </span>
        <!-- END LOGO PLACEHOLDER -->

        <!-- Note: The activity badge color changes when clicked and resets the number to 0
        Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
        <span id="activity" data-booking="" data-support="" class="activity-dropdown"> <i class="fa fa-user"></i> <b class="badge bg-color-redLight"> 0 </b> </span>

        <!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
        <div class="ajax-dropdown">

            <!-- the ID links are fetched via AJAX to the ajax container "ajax-notifications" -->
            <div class="btn-group btn-group-justified" data-toggle="buttons">
                <label class="btn btn-default booking-noti">
                    News <span class="booking-num"></span> </label>
                <label class="btn btn-default support-noti">
                    Support <span class="support-num"></span> </label>
            </div>

            <!-- notification content -->
            <div class="ajax-notifications custom-scroll">
                <div id="booking-content" class="noti-content"></div>
                <div id="support-content" class="noti-content"></div>
            </div>
            <!-- end notification content -->


        </div>
        <!-- END AJAX-DROPDOWN -->
    </div>

    <!-- pulled right: nav area -->
    <div class="pull-right">

        <!-- collapse menu button -->
        <div id="hide-menu" class="btn-header pull-right">
            <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i
                            class="fa fa-reorder"></i></a> </span>
        </div>
        <!-- end collapse menu -->

        <!-- #MOBILE -->
        <!-- Top menu profile link : this shows only when top menu is active -->
        <ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
            <li class="">
                <a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown">
                    <img src="{{ asset('img/avatars/male.png') }}" alt="{{ auth()->user()->username }}" class="online"/>
                </a>
                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="{{ route('profile') }}" class="padding-10 padding-top-0 padding-bottom-0"> <i
                                    class="fa fa-user"></i> <u>P</u>rofile</a>
                    </li>
                    <li class="divider"></li>
                    {{--<li>
                        <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"
                           data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
                    </li>
                    <li class="divider"></li>--}}
                    <li>
                        <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"
                           data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="padding-10 padding-top-5 padding-bottom-5"><i
                                    class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- logout button -->
        <div id="logout" class="btn-header transparent pull-right">
            <span>
                <a href="{{ route('logout') }}" title="Sign Out"
                      onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                      data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i
                            class="fa fa-sign-out"></i></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
            </span>
        </div>
        <!-- end logout button -->

        <!-- fullscreen button -->
        <div id="fullscreen" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i
                            class="fa fa-arrows-alt"></i></a> </span>
        </div>
        <!-- end fullscreen button -->
<!--
        <div class="btn-header transparent pull-right">
            <span><a href="javascript:void(0);" title="Shortcut"
                     data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> Tạo nhanh</a></span>
        </div>
-->
    </div>
    <!-- end pulled right: nav area -->

</header>
<!-- END HEADER -->