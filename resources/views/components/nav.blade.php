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
                    <!-- 2018-04-04 lamvt update icon -->
                        <a href="{{ route('source') }}"><i class="fa fa-lg fa-fw fa-list-alt"></i> Sources</a>
                    <!-- end 2018-04-04 -->
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
                    {{--<li class="{{ $active == 'kpis' ? 'active' : '' }}">
                        <a href="{{ route('kpis') }}"><i class="fa fa-lg fa-fw fa-bullseye"></i> Kpis</a>
                    </li>--}}
                </ul>
            </li>
            <li class="{{ $active == 'adsmanager' ? 'active' : '' }}">
                <a href="javascript:void(0)"><i class="fa fa-lg fa-fw fa-bullhorn"></i> <span
                            class="menu-item-parent">Ads Manager</span></a>
                <ul>
                    <li class="{{ $active == 'campaigns' ? 'active' : '' }}">
                        <a href="{{ route('campaigns') }}"><i class="fa fa-lg fa-fw fa-table"></i> Campaigns</a>
                    </li>
                   {{-- <li class="{{ $active == 'subcampaigns' ? 'active' : '' }}">
                        <a href="--}}{{--{{ //route('subcampaigns') }}--}}{{--"><i class="fa fa-lg fa-fw fa-table"></i> Subcampaigns</a>
                    </li>
                    <li class="{{ $active == 'ads' ? 'active' : '' }}">
                        <a href="--}}{{--{{ //route('ads') }}--}}{{--"><i class="fa fa-lg fa-fw fa-table"></i> Ads</a>
                    </li>--}}
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
                        <a href="{{ route('sub-report-line') }}"><i class="fa fa-lg fa-fw fa-line-chart"></i> Line Chart</a>
                    </li>

                    <li class="{{ $active == 'hour-report' ? 'active' : '' }}">
                        <a href="{{ route('hour-report') }}"><i class="fa fa-lg fa-fw fa-calendar-times-o"></i> Hour Report</a>
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

            {{--@permission('view-user')--}}
            <li class="{{ $active == 'users' ? 'active' : '' }}">
                <!-- 2018-04-04 lamvt update icon -->
                <a href="{{ route('users') }}"><i class="fa fa-lg fa-fw fa-user"></i> <span
                            class="menu-item-parent">Users</span></a>
                <!-- end 2018-04-04 -->
                {{--<ul>--}}
                    {{--<li class="{{ $active == 'users' ? 'active' : '' }}">--}}
                        {{--<a href="{{ route('users') }}">List</a>--}}
                    {{--</li>--}}
                    {{--@if(auth()->user()->role == "Manager")--}}
                    {{--<li class="{{ $active == 'users-create' ? 'active' : '' }}">--}}
                        {{--<a href="{{ route('users-create') }}">Add user</a>--}}
                    {{--</li>--}}
                    {{--@endif--}}
                    {{--<li class="{{ $active == 'users-roles' ? 'active' : '' }}">--}}
                        {{--<a href="{{ route('users-roles') }}">User roles</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            </li>
            {{--2018-04-18 LamVT [HEL_9] Add more setting for VND/USD conversion--}}
            @if(auth()->user()->role == "Manager" || auth()->user()->role == "Admin")
            <li class="{{ $active == 'config' ? 'active' : '' }}">
                <a href="{{ route('config') }}"><i class="fa fa-lg fa-fw fa-cog"></i> <span
                            class="menu-item-parent">Config</span></a>
            </li>
            @endif
            {{-- end 2018-04-18 LamVT [HEL_9] Add more setting for VND/USD conversion--}}
            {{--@endpermission

            @permission('view-report')
            <li class="{{ $active == 'reports' ? 'active' : '' }}">
                <a href="javascript:void(0)"><i class="fa fa-lg fa-fw fa-bar-chart"></i> <span
                            class="menu-item-parent">Reports</span></a>
                <ul>
                    <li>
                        <a href="javascript:void(0)">Sản phẩm</a>
                        <ul>
                            <li class="{{ $active == 'reports-product' ? 'active' : '' }}">
                                <a href="{{ route('reports-product') }}">Số lượt xem</a>
                            </li>
                            <li class="{{ $active == 'reports-product-booking' ? 'active' : '' }}">
                                <a href="{{ route('reports-product-booking') }}">Thông tin booking</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ $active == 'reports-coupon' ? 'active' : '' }}">
                        <a href="{{ route('reports-sale-coupon') }}">Coupons</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">Customers</a>
                        <ul>
                            <li class="{{ $active == 'reports-customer-booking' ? 'active' : '' }}">
                                <a href="{{ route('reports-customer-booking') }}">Bookings</a>
                            </li>
                            <li class="{{ $active == 'reports-customer-activity' ? 'active' : '' }}">
                                <a href="{{ route('reports-customer-activity') }}">Activities</a>
                            </li>
                        </ul>
                    </li>
                    --}}{{--<li class="{{ $active == 'reports-subscriptions' ? 'active' : '' }}">
                        <a href="{{ route('reports-subscriptions') }}">Subscriptions</a>
                    </li>--}}{{--
                </ul>
            </li>
            @endpermission

            @permission('view-setting')--}}
            {{--<li class="{{ $active == 'settings' ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}"><i class="fa fa-lg fa-fw fa-gear"></i> <span class="menu-item-parent">General settings</span></a>
            </li>--}}
            {{--@endpermission--}}
        </ul>
    </nav>


    <span class="minifyme" data-action="minifyMenu">
				<i class="fa fa-arrow-circle-left hit"></i>
			</span>

</aside>
<!-- END NAVIGATION -->