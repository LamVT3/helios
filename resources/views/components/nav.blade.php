<!-- #NAVIGATION -->
<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">

    <!-- User info -->
    <div class="login-info">
				<span> <!-- User image size is adjusted inside CSS, it should stay as it -->

					<a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
						<img src="{{ asset('img/avatars/male.png') }}" alt="me" class="online"/>
						<span>
							{{ auth()->user()->username }}
						</span>
						<i class="fa fa-angle-down"></i>
					</a>

				</span>
    </div>
    <!-- end user info -->

    <!-- NAVIGATION : This navigation is also responsive-->
    <nav>
        <!--
        NOTE: Notice the gaps after each icon usage <i></i>..
        Please note that these links work a bit different than
        traditional href="" links. See documentation for details.
        -->

        <ul>
            <li class="{{ $active == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span
                            class="menu-item-parent">Dashboard</span></a>
            </li>
            <li class="{{ $active == 'mktmanager' ? 'active' : '' }}">
                <a href="javascript:void(0)"><i class="fa fa-lg fa-fw fa-sitemap"></i> <span
                            class="menu-item-parent">MKT Manager</span></a>
                <ul>
                    <li class="{{ $active == 'mktmanager' ? 'active' : '' }}">
                        <a href="{{ route('source') }}"><i class="fa fa-lg fa-fw fa-list-alt"></i> Sources</a>
                    </li>
                    <li class="{{ $active == 'mktmanager-teams' ? 'active' : '' }}">
                        <a href="{{ route('team') }}"><i class="fa fa-lg fa-fw fa-group"></i> Teams</a>
                    </li>
                    <li class="{{ $active == 'mktmanager-channel' ? 'active' : '' }}">
                        <a href="{{ route('channel') }}"><i class="fa fa-lg fa-fw fa-gg-circle"></i> Channel</a>
                    </li>
                    <li class="{{ $active == 'mktmanager-tksPage' ? 'active' : '' }}">
                        <a href="{{ route('thankyou-page') }}"><i class="fa fa-lg fa-fw fa-gift"></i> Thank You Page</a>
                    </li>
                </ul>
            </li>
            <li class="{{ $active == 'adsmanager' ? 'active' : '' }}">
                <a href="javascript:void(0)"><i class="fa fa-lg fa-fw fa-bullhorn"></i> <span
                            class="menu-item-parent">Ads Manager</span></a>
                <ul>
                    <li class="{{ $active == 'campaigns' ? 'active' : '' }}">
                        <a href="{{ route('campaigns') }}"><i class="fa fa-lg fa-fw fa-table"></i> Campaigns</a>
                    </li>
                    <li class="{{ $active == 'adsmanager-lp' ? 'active' : '' }}">
                        <a href="{{ route('landing-page') }}"><i class="fa fa-lg fa-fw fa-book"></i> Landing Pages</a>
                    </li>


                </ul>
            </li>

            <li class="{{ $active == 'sadsadasd' ? 'active' : '' }}">
                <a href="javascript:void(0)"><i class="fa fa-lg fa-fw fa-bar-chart-o"></i> <span
                            class="menu-item-parent">Report</span></a>
                <ul>
{{--                    <li class="{{ $active == 'sub_report_line' ? 'active' : '' }}">--}}
                    <li class="{{ $active == 'report' ? 'active' : '' }}">
                        <a href="{{ route('report') }}"><i class="fa fa-lg fa-fw fa-align-justify"></i> Quality Report</a>
                    </li>

                    <li class="{{ $active == 'sub-report-line' ? 'active' : '' }}">
                        <a href="{{ route('sub-report-line') }}"><i class="fa fa-lg fa-fw fa-line-chart"></i> Line Report</a>
                    </li>

                    <li class="{{ $active == 'hour-report' ? 'active' : '' }}">
                        <a href="{{ route('hour-report') }}"><i class="fa fa-lg fa-fw fa-calendar-times-o"></i> Hour Report</a>
                    </li>

                    <li class="{{ $active == 'channel-report' ? 'active' : '' }}">
                        <a href="{{ route('channel-report') }}"><i class="fa fa-lg fa-fw fa-calendar"></i> Channel Report</a>
                    </li>

                    <li class="{{ $active == 'assign_kpi' ? 'active' : '' }}">
                        <a href="{{ route('assign-kpi') }}"><i class="fa fa-lg fa-fw fa-map-signs"></i> KPI Report</a>
                    </li>

                    <li class="{{ $active == 'inventory-report' ? 'active' : '' }}">
                        <a href="{{ route('inventory-report') }}"><i class="fa fa-lg fa-fw fa-empire"></i> Inventory Report</a>
                    </li>

                    <li class="{{ $active == 'performance-report' ? 'active' : '' }}">
                        <a href="{{ route('performance-report') }}"><i class="fa fa-universal-access"></i> Performance Report</a>
                    </li>
                </ul>
            </li>

            <li class="{{ $active == 'contacts' ? 'active' : '' }}">
                <a href="{{ route('contacts-c3') }}"><i class="fa fa-lg fa-fw fa-child"></i> <span
                            class="menu-item-parent">Contacts</span></a>
            </li>

            <li class="{{ $active == 'notification' ? 'active' : '' }}">
                <a href="{{ route('notification') }}"><i class="fa fa-lg fa-fw fa-bell"></i> <span
                            class="menu-item-parent">Notification</span></a>
            </li>

            <li class="{{ $active == 'users' ? 'active' : '' }}">
                <a href="{{ route('users') }}"><i class="fa fa-lg fa-fw fa-user"></i> <span
                            class="menu-item-parent">Users</span></a>
            </li>
            @if(auth()->user()->role == "Manager" || auth()->user()->role == "Admin")
            <li class="{{ $active == 'config' ? 'active' : '' }}">
                <a href="{{ route('config') }}"><i class="fa fa-lg fa-fw fa-cog"></i> <span
                            class="menu-item-parent">Config</span></a>
            </li>
            @endif

            @if(auth()->user()->role == "Manager" || auth()->user()->role == "Admin")
                <li class="{{ $active == 'diff_contacts' ? 'active' : '' }}">
                    <a href="{{ route('diff-contacts') }}"><i class="fa fa-lg fa-fw fa-exclamation-triangle"></i> <span
                                class="menu-item-parent">Diff Contacts</span></a>
                </li>
            @endif
        </ul>
    </nav>


    <span class="minifyme" data-action="minifyMenu">
				<i class="fa fa-arrow-circle-left hit"></i>
			</span>

</aside>
<!-- END NAVIGATION -->