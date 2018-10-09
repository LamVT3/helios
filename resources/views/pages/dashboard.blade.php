@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

        @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
            <form action="/home" method="GET" class="form_search">
                {{ csrf_field()}}
            </form>
        @endcomponent

        <!-- widget grid -->
            <section id="widget-grid" class="">
                <form class="smart-form" style="padding: 10px; background: white; border: 1px solid #ccc; margin: 0px 0px 20px 0px ">
                    <div class="row" >
                        <section class="col col-sm-6 col-lg-3">
                            <label class="label">Marketer</label>
                            <select name="marketer" class="select2" style="width: 280px" id="marketer"
                                    data-url="">
                                <option value="">All</option>
                                @foreach($users as $item)
                                    @if(auth()->user()->_id == $item->id && auth()->user()->role == 'Marketer')
                                        <option value="{{ $item->id }}" selected>{{ $item->username}}</option>
                                    @else
                                        <option value="{{ $item->id }}">{{ $item->username }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <i></i>
                        </section>
                        <section class="col col-sm-6 col-lg-3">
                            <label class="label">Channel</label>
                            <select name="channel" class="select2" style="width: 280px" id="channel"
                                    data-url="">
                                <option value="">All</option>
                                @foreach($channels as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <i></i>
                        </section>
                        <section class="col col-sm-6 col-lg-3" style="min-height: 55px">
                            <label class="label">Currency</label>
                            <div id="wrapper_currency" style="float: left; padding: 5px 0px 5px 0px;">
                                <label>
                                    <input type="radio" name="currency" id="currency" value="USD" checked>USD
                                </label>
                                <label>
                                    <input type="radio" name="currency" id="currency" value="VND">VND
                                </label>
                                <label>
                                    <input type="radio" name="currency" id="currency" value="Baht">Baht
                                </label>
                            </div>
                        </section>
                        <section class="col col-sm-6 col-lg-3" style="min-height: 55px">
                            <label class="label">Date</label>
                            <div id="reportrange" class="pull-left"
                                 style="background: #fff; cursor: pointer; padding: 7px 10px 7px 10px; border: 1px solid #ccc;">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span></span> <b class="caret"></b>
                            </div>
                            <div id="" class="pull-right"
                                 style="">
                                <button id="filter" class="btn btn-primary btn-sm" type="button" style="float: right" >
                                    <i class="fa fa-search"></i>

                                </button>
                            </div>
                        </section>
                    </div>
                </form>
                <!-- row -->
                <div class="row">
                    <div class="col-sm-6 col-lg-3">
                        <div class="panel panel-default widget-c3">
                            <div class="panel-body status">
                                <div class="who clearfix widget-title">
                                    <h4>
                                        <i class="fa fa-lg fa-fw fa-child"></i><strong>C3B Total</strong>
                                        <span class="widget-unit">(C3B)</span>
                                    </h4>
                                </div>
                                <div class="text text-align-left font-xs widget-caption">
                                    KPI
                                    <span class="widget-unit">Actual</span>
                                </div>
                                <div class="text text-align-right font-xl widget-actual pull-right">
                                    ...
                                </div>
                                <div class="text text-align-right font-xl widget-kpi pull-left">
                                    ...
                                </div>

                                {{--<div class="text text-align-right font-sm widget-kpi">
                                    12,000
                                </div>
                                <ul class="links widget-progress">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-color-red" role="progressbar" style="width: 56%;vertical-align: top;line-height: unset;">56%</div>
                                    </div>
                                </ul>--}}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="panel panel-default widget-c3b-cost">
                            <div class="panel-body status">
                                <div class="who clearfix widget-title">
                                    <h4>
                                        <i class="fa fa-lg fa-fw fa-money"></i><strong>C3B Cost</strong>
                                        <span class="widget-unit">(USD)</span>
                                    </h4>
                                </div>
                                <div class="text text-align-left font-xs widget-caption">
                                    KPI
                                    <span class="widget-unit">Actual</span>
                                </div>
                                <div class="text text-align-right font-xl widget-actual pull-right">
                                    ...
                                </div>
                                <div class="text text-align-right font-xl widget-kpi pull-left">
                                    ...
                                </div>
                                {{--<div class="text text-align-right font-sm widget-kpi">
                                    2.3
                                </div>
                                <ul class="links widget-progress">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-color-red" role="progressbar" style="width: 90%;vertical-align: top;line-height: unset;">90%</div>
                                    </div>
                                </ul>--}}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="panel panel-default widget-l3-c3bg">
                            <div class="panel-body status">
                                <div class="who clearfix widget-title">
                                    <h4>
                                        <i class="fa fa-lg fa-fw fa-child"></i><strong>L3/C3BG</strong>
                                        <span class="widget-unit">(%)</span>
                                    </h4>
                                </div>
                                <div class="text text-align-left font-xs widget-caption">
                                    KPI
                                    <span class="widget-unit">Actual</span>
                                </div>
                                <div class="text text-align-right font-xl widget-actual pull-right">
                                    ...
                                </div>
                                <div class="text text-align-right font-xl widget-kpi pull-left">
                                    ...
                                </div>

                                {{--<div class="text text-align-right font-sm widget-kpi">
                                    12,000
                                </div>
                                <ul class="links widget-progress">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-color-red" role="progressbar" style="width: 56%;vertical-align: top;line-height: unset;">56%</div>
                                    </div>
                                </ul>--}}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="panel panel-default widget-c3bg-c3b">
                            <div class="panel-body status">
                                <div class="who clearfix widget-title">
                                    <h4>
                                        <i class="fa fa-lg fa-fw fa-child"></i><strong>C3BG/C3B</strong>
                                        <span class="widget-unit">(%)</span>
                                    </h4>
                                </div>
                                <div class="text text-align-left font-xs widget-caption">
                                    <span class="widget-unit">Actual</span>
                                </div>
                                <div class="text text-align-right font-xl widget-actual">
                                    ...
                                </div>
                                {{--<div class="text text-align-right font-sm widget-kpi">
                                    12,000
                                </div>
                                <ul class="links widget-progress">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-color-red" role="progressbar" style="width: 56%;vertical-align: top;line-height: unset;">56%</div>
                                    </div>
                                </ul>--}}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="panel panel-default widget-budget">
                            <div class="panel-body status">

                                <div class="who clearfix widget-title">
                                    <h4>
                                        <i class="fa fa-lg fa-fw fa-credit-card"></i><strong>Budget</strong>
                                        <span class="widget-unit">(USD)</span>
                                    </h4>
                                </div>
                                <div class="text text-align-left font-xs widget-caption">
                                    Left
                                    <span class="widget-unit">Actual</span>
                                </div>
                                <div class="text text-align-right font-xl widget-actual pull-right">
                                    ...
                                </div>
                                <div class="text text-align-right font-xl widget-kpi pull-left">
                                    ...
                                </div>
                                {{--<div class="text text-align-right font-sm widget-kpi">
                                    2,000
                                </div>
                                <ul class="links widget-progress">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-color-red" role="progressbar" style="width: 60%;vertical-align: top;line-height: unset;">60%</div>
                                    </div>
                                </ul>--}}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="panel panel-default widget-me-re">
                            <div class="panel-body status">

                                <div class="who clearfix widget-title">
                                    <h4>
                                        <i class="fa fa-lg fa-fw fa-usd"></i><strong>ME/RE</strong>
                                        <span class="widget-unit">(%)</span>
                                    </h4>
                                </div>
                                <div class="text text-align-left font-xs widget-caption">

                                    <span class="widget-unit">Actual</span>
                                </div>
                                <div class="text text-align-right font-xl widget-actual">
                                    ...
                                </div>
                                {{--<div class="text text-align-right font-sm widget-kpi">
                                    2,000,000,000
                                </div>
                                <ul class="links widget-progress">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-color-red" role="progressbar" style="width: 60%;vertical-align: top;line-height: unset;">60%</div>
                                    </div>
                                </ul>--}}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="panel panel-default widget-l1-c3bg">
                            <div class="panel-body status">
                                <div class="who clearfix widget-title">
                                    <h4>
                                        <i class="fa fa-lg fa-fw fa-child"></i><strong>L1/C3BG</strong>
                                        <span class="widget-unit">(%)</span>
                                    </h4>
                                </div>
                                <div class="text text-align-left font-xs widget-caption">

                                    <span class="widget-unit">Actual</span>
                                </div>
                                <div class="text text-align-right font-xl widget-actual">
                                    ...
                                </div>
                                {{--<div class="text text-align-right font-sm widget-kpi">
                                    2.3
                                </div>
                                <ul class="links widget-progress">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-color-red" role="progressbar" style="width: 90%;vertical-align: top;line-height: unset;">90%</div>
                                    </div>
                                </ul>--}}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="panel panel-default widget-l8-l1">
                            <div class="panel-body status">

                                <div class="who clearfix widget-title">
                                    <h4>
                                        <i class="fa fa-lg fa-fw fa-child"></i><strong>L8/L1</strong>
                                        <span class="widget-unit">(%)</span>
                                    </h4>
                                </div>
                                <div class="text text-align-left font-xs widget-caption">

                                    <span class="widget-unit">Actual</span>
                                </div>
                                <div class="text text-align-right font-xl widget-actual">
                                    ...
                                </div>
                                {{--<div class="text text-align-right font-sm widget-kpi">
                                    2,000
                                </div>
                                <ul class="links widget-progress">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-color-red" role="progressbar" style="width: 60%;vertical-align: top;line-height: unset;">60%</div>
                                    </div>
                                </ul>--}}
                            </div>
                        </div>
                    </div>

                </div>

                <!-- end row -->

                <div class="row">
                    <article class="col-sm-12 col-md-12">

                    @component('components.jarviswidget',
                    ['id' => 'c3_chart', 'icon' => 'fa-line-chart', 'title' => "C3B in " , 'dropdown' => "true"])
                        <!-- widget content -->
                            <div class="widget-body no-padding">

                                {{--<div class="widget-body-toolbar bg-color-white smart-form" id="rev-toggles">--}}

                                {{--</div>--}}
                                <div id="site-stats-c3" class="chart has-legend"></div>
                            </div>
                        @endcomponent
                    </article>

                </div>

                <div class="row" id="c3a_c3b">
                    <article class="col-sm-12 col-md-12">
                        <div class="loading" style="display: none">
                            <div class="col-md-12 text-center">
                                <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt=""
                                     style="width: 2%;"/>
                            </div>
                        </div>
                        <br>
                    @component('components.jarviswidget',
                    ['id' => 'C3A-C3B', 'icon' => 'fa-line-chart', 'title' => "C3A-C3B Report in ", 'dropdown' => 'true'])
                        <!-- widget content -->
                            <div class="widget-body no-padding">
                                @component('components.C3A-C3B_chart', ['id' => 'C3A-C3B_chart', 'chk' => 'C3A-C3B_chk'])
                                @endcomponent
                            </div>
                        @endcomponent
                    </article>

                </div>
                <!-- row -->
                <h1><i class="glyphicon glyphicon-calendar fa fa-trophy"></i> <b>Leaderboard</b></h1>
                <div class="row">

                    <article class="col-sm-12 col-md-12 col-lg-4">

                    @component('components.jarviswidget',
                    ['id' => 0, 'icon' => 'fa-table', 'title' => "C3B Leaderboard"])

                        <!-- widget content -->
                            <div class="widget-body no-padding widget-c3-leaderboard">
                                <div class="alert alert-info no-margin fade in">
                                    <button class="btn btn-xs btn-default today"
                                            onclick="c3_leaderboard(this, 'today')">
                                        Today
                                    </button>
                                    <button class="btn btn-xs btn-default thisweek"
                                            onclick="c3_leaderboard(this, 'thisweek')">
                                        This Week
                                    </button>
                                    <button class="btn btn-xs btn-default thismonth"
                                            onclick="c3_leaderboard(this, 'thismonth')">
                                        This Month
                                    </button>
                                </div>
                                <div class="c3_leaderboard">
                                    <h3>loading...</h3>
                                </div>
                            </div>
                            <!-- end widget content -->

                        @endcomponent


                    </article>

                    <article class="col-sm-12 col-md-12 col-lg-4">

                        @component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Revenue Leaderboard'])
                            <div class="widget-body no-padding widget-revenue-leaderboard">
                                <div class="alert alert-info no-margin fade in">
                                    <button class="btn btn-xs btn-default today"
                                            onclick="revenue_leaderboard(this, 'today')">
                                        Today
                                    </button>
                                    <button class="btn btn-xs btn-default"
                                            onclick="revenue_leaderboard(this, 'thisweek')">
                                        This Week
                                    </button>
                                    <button class="btn btn-xs btn-default"
                                            onclick="revenue_leaderboard(this, 'thismonth')">
                                        This Month
                                    </button>
                                </div>
                                <div class="revenue_leaderboard">
                                    <h3>loading...</h3>
                                </div>
                            </div>
                        @endcomponent

                    </article>

                    <article class="col-sm-12 col-md-12 col-lg-4">

                        @component('components.jarviswidget',
                                                    ['id' => 2, 'icon' => 'fa-table', 'title' => 'Spent Leaderboard'])
                            <div class="widget-body no-padding widget-spent-leaderboard">
                                <div class="alert alert-warning no-margin fade in">
                                    <button class="btn btn-xs btn-default today"
                                            onclick="spent_leaderboard(this, 'today')">
                                        Today
                                    </button>
                                    <button class="btn btn-xs btn-default"
                                            onclick="spent_leaderboard(this, 'thisweek')">
                                        This Week
                                    </button>
                                    <button class="btn btn-xs btn-default"
                                            onclick="spent_leaderboard(this, 'thismonth')">
                                        This Month
                                    </button>
                                </div>
                                <div class="spent_leaderboard">
                                    <h3>loading...</h3>
                                </div>
                            </div>
                        @endcomponent

                    </article>

                </div>

                <!-- end row -->

            </section>
            <!-- end widget grid -->

        </div>
        <!-- END MAIN CONTENT -->
        <input type="hidden" name="c3_month" id="c3_month" value="{{$month}}">
        <input type="hidden" name="C3AC3B_month" id="C3AC3B_month" value="{{$month}}">
        <input type="hidden" name="get-channel-url" id="get-channel-url" value="{{route('dashboard-get-channel')}}">
        <input type="hidden" id="c3_total" value="{{ $dashboard['c3a_c3b']["c3"] }}">
        <input type="hidden" name="C3AC3B_url" value="{{route('get-C3AC3B')}}">

    </div>
    <!-- END MAIN PANEL -->

@endsection

@section('script')
    <!-- PAGE RELATED PLUGIN(S) -->
    <!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
    <script src="{{ asset('js/plugin/flot/jquery.flot.cust.min.js') }}"></script>
    <script src="{{ asset('js/plugin/flot/jquery.flot.resize.min.js') }}"></script>
    <script src="{{ asset('js/plugin/flot/jquery.flot.time.min.js') }}"></script>
    <script src="{{ asset('js/plugin/flot/jquery.flot.tooltip.min.js') }}"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>
    <script src="{{ asset('js/fixedTable/tableHeadFixer.js') }}"></script>

    <style>
        input#currency
        {
            margin-right: 5px;
        }
        #wrapper_currency label
        {
            margin-right: 15px;
        }

        .widget-title .widget-unit {
            float: right;
            font-style: italic;
        }

        .widget-caption .widget-unit {
            float: right;
            /*font-style: italic;*/
        }

        .widget-caption {
            min-height: 30px !important;
            padding: 0 14px !important;
        }

        .widget-progress .progress {
            margin-bottom: 0px;
        }

        .widget-caption.text {
            padding: 5px 10px;
        }

        /*.widget-revenue .widget-title {
            background-color: #E91E63;
            color: white;
        }*/

        .widget-c3 .progress-bar {
            background-color: #3F51B5 !important;
        }

        .widget-c3 .widget-actual.text {
            padding: 0 14px;
            font-weight: bold;
            /*color: #3F51B5;*/
            color: #3b3f52;
        }

        .widget-kpi.text {
            padding: 0 14px;
            /*color: #4CAF50;*/
            font-style: italic;
        }
        /*
        .widget-c3b-cost .widget-title {
            background-color: #4CAF50;
            color: white;
        }*/

        .widget-c3b-cost .progress-bar {
            background-color: #4CAF50 !important;
        }

        .widget-c3b-cost .widget-actual.text {
            padding: 0 14px;
            font-weight: bold;
            /*color: #4CAF50;*/
            color: #3b3f52;
        }

        /*.widget-budget .widget-title {
            background-color: #FF9800;
            color: white;
        }*/

        .widget-l3-c3bg .progress-bar {
            background-color: #FF9800 !important;
        }

        .widget-l3-c3bg .widget-actual.text {
            padding: 0 14px;
            font-weight: bold;
            /*color: #FF9800;*/
            color: #3b3f52;
        }

        .widget-c3bg-c3b .progress-bar {
            background-color: #FF66FF !important;
        }

        .widget-c3bg-c3b .widget-actual.text {
            padding: 0 14px;
            font-weight: bold;
            /*color: #FF66FF;*/
            color: #3b3f52;
        }

        .widget-budget .progress-bar {
            background-color: #FF9800 !important;
        }

        .widget-budget .widget-actual.text {
            padding: 0 14px;
            font-weight: bold;
            /*color: #FF9800;*/
            color: #3b3f52;
        }

        .widget-me-re .progress-bar {
            background-color: #4CAF50 !important;
        }

        .widget-me-re .widget-actual.text {
            padding: 0 14px;
            font-weight: bold;
            /*color: #CCCC00;*/
            color: #3b3f52;
        }

        .widget-l8-l1 .progress-bar {
            background-color: #9999FF !important;
        }

        .widget-l8-l1 .widget-actual.text {
            padding: 0 14px;
            font-weight: bold;
            /*color: #9999FF;*/
            color: #3b3f52;
        }

        .widget-l1-c3bg .progress-bar {
            background-color: #3F51B5 !important;
        }

        .widget-l1-c3bg .widget-actual.text {
            padding: 0 14px;
            font-weight: bold;
            /*color: #3F51B5;*/
            color: #3b3f52;
        }

        /*.widget-c3 .widget-title {
            background-color: #3F51B5;
            color: white;
        }*/

        .line-height-md{
            font-size: 130%!important;
            line-height: 2.82em!important;
        }
        .line-height-sm{
            font-size: 95%!important;
            line-height: 3.82em!important;
        }
        .kpi-gap{
            border: 1px solid red !important;
            color: #F44336 !important;
        }
        .kpi-gap .widget-actual.text{
            color: #F44336 !important;
        }

    </style>

    <script type="text/javascript">

        // 2018-04-17 LamVT [HEL-9] add dropdown for C3/L8 chart
        /* chart colors default */
        var $chrt_border_color = "#efefef";
        var $chrt_grid_color = "#DDD";
        var $chrt_main = "#E24913";
        /* red       */
        var $chrt_second = "#6595b4";
        /* blue      */
        var $chrt_third = "#00c01a";
        /* orange    */
        var $chrt_fourth = "#7e9d3a";
        /* green     */
        var $chrt_fifth = "#BD362F";
        /* dark red  */
        var $chrt_mono = "#000";
        /* site stats chart */
        // end 2018-04-17 LamVT [HEL-9] add dropdown for C3/L8 chart

        var __arr_month = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"];

        $(".select2").select2({
            placeholder: "Select a State",
            allowClear: true
        });
        // DO NOT REMOVE : GLOBAL FUNCTIONS!
        $(document).ready(function () {

            var start = moment();
            var end = moment();

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    "This Week": [moment().startOf("isoWeek"), moment().endOf("isoWeek")],
                    "Last Week": [moment().subtract(1, "week").startOf("isoWeek"), moment().subtract(1, "week").endOf("isoWeek")],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: "left"
            }, cb);

            cb(start, end);

            init_dashboard();

            var data_c3b = [
                {data: {{ $dashboard["chart_c3"] }}, label: "C3B"},
                {data: {{ $dashboard["chart_kpi"] }}, label: "KPI"},
            ];

            var data_c3a_c3b = [
                {data: {{ $dashboard['c3a_c3b']["C3A_Duplicated"] }}, label: "C3A-Duplicated"},
                {data: {{ $dashboard['c3a_c3b']["C3B_Under18"] }}, label: "C3B-Under18"},
                {data: {{ $dashboard['c3a_c3b']["C3B_Duplicated15Days"] }}, label: "C3B-Duplicated15Days"},
                {data: {{ $dashboard['c3a_c3b']["C3A_Test"] }}, label: "C3A-Test"}
            ];

            initChart($("#site-stats-c3"), data_c3b, [$chrt_third, $chrt_main]);
            initChart($("#C3A-C3B_chart"), data_c3a_c3b, ["#800000", "#6A5ACD", "#808080", "#7CFC00"], 'C3AC3B');
            /* end site stats */

            $('.today').click();

            $('button#filter').click(function (e) {
                init_dashboard();

                var c3_month = $('#c3_month').val();
                get_c3_chart(parseInt(c3_month));

                var c3a_c3b_month = $('input[name="C3AC3B_month"]').val();
                get_C3AC3B(c3a_c3b_month);

                $('.widget-revenue-leaderboard button.active').click();
                $('.widget-c3-leaderboard button.active').click();
                $('.widget-spent-leaderboard button.active').click();

            })

            $('#marketer').change(function (e) {
                var url = $('#get-channel-url').val();
                var marketer = $('#marketer').val();
                $.ajax({
                    url: url,
                    type: 'GET',
                    contentType: "application/json",
                    dataType: "json",
                    data: {
                        marketer: marketer
                    }
                }).done(function (response) {
                    var select_channel = "<option value='' selected>All</option>";
                    $.each(response, function (index, item) {
                        select_channel += "<option value=" + item._id + "> " + item.name + " </option>";
                    });

                    $('#channel').html(select_channel);
                    $("#channel").select2();
                });
            })

            $('div#c3a_c3b li#month').click(function() {
                var month       = $(this).val();
                var dropdown    = $(this).closest('ul').siblings();
                dropdown.html(__arr_month[month - 1]);

                $('h2#C3A-C3B').html('C3A-C3B Report in ' + dropdown.html());
                if (month < 10) {
                    month = "0" + month.toString();
                }
                else {
                    month = month.toString();
                }
                $('input[name="C3AC3B_month"]').val(month);
                get_C3AC3B(month);
            });

            $('#C3A-C3B_chk input[type=checkbox]').change(function (e) {
                var month = $('input[name="C3AC3B_month"]').val();
                get_C3AC3B(month);
            })

        });

        function init_dashboard(){
            var unit = $('input[name=currency]:checked').val();

            var date = $('#reportrange span').html();
            date = date.split('-');

            var startDate = formatDate(date[0]);
            var endDate = formatDate(date[1]);

            dashboard(startDate, endDate, unit);

            $c3ac3b_month = $('input[name="C3AC3B_month"]').val();
            $('h2#C3A-C3B').html('C3A-C3B Report in ' + __arr_month[$c3ac3b_month - 1]);

        }

        function formatDate(str) {
            var date = str.split('/');
            if (date[1] < 10) {
                date[1] = '0' + date[1];
            }

            return date[2] + '-' + date[1] + '-' + date[0];
        }

        // PAGE RELATED SCRIPTS
        function cb(start, end) {
            $('#reportrange span').html(start.format('D/M/Y') + '-' + end.format('D/M/Y'));
        }

        function dashboard(startDate, endDate, unit) {

            $('.widget-c3b-cost .widget-title .widget-unit').html('(' + unit + ')');
            $('.widget-budget .widget-title .widget-unit').html('(' + unit + ')');

            $('.widget-c3 .widget-actual').html('...');
            $('.widget-c3 .widget-kpi').html('...');
            $('.widget-c3b-cost .widget-actual').html('...');
            $('.widget-c3b-cost .widget-kpi').html('...');
            $('.widget-l3-c3bg .widget-actual').html('...');
            $('.widget-l3-c3bg .widget-kpi').html('...');
            $('.widget-c3bg-c3b .widget-actual').html('...');
            $('.widget-budget .widget-actual').html('...');
            $('.widget-budget .widget-kpi').html('...');
            $('.widget-me-re .widget-actual').html('...');
            $('.widget-l1-c3bg .widget-actual').html('...');
            $('.widget-l8-l1 .widget-actual').html('...');

            $('div.panel-default').removeClass('kpi-gap');

            $marketer_id = $('#marketer').val();
            $channel_id  = $('#channel').val();

            $.get("{{ route('ajax-dashboard') }}", {
                startDate   : startDate,
                endDate     : endDate,
                unit        : unit,
                marketer_id : $marketer_id,
                channel_id  : $channel_id,
            }, function (data) {
                var dashboard = data.dashboard;
                $('.widget-c3 .widget-actual').html(dashboard.c3);
                $('.widget-c3 .widget-kpi').html(dashboard.kpi);
                $('.widget-c3b-cost .widget-actual').html(dashboard.c3_cost);
                $('.widget-c3b-cost .widget-kpi').html(dashboard.kpi_cost);
                $('.widget-l3-c3bg .widget-actual').html(dashboard.l3_c3bg);
                $('.widget-l3-c3bg .widget-kpi').html(dashboard.kpi_l3_c3bg);
                $('.widget-c3bg-c3b .widget-actual').html(dashboard.c3bg_c3b);
                $('.widget-budget .widget-actual').html(dashboard.spent);
                $('.widget-budget .widget-kpi').html(dashboard.spent_left);
                $('.widget-me-re .widget-actual').html(dashboard.me_re);
                $('.widget-l1-c3bg .widget-actual').html(dashboard.l1_c3bg);
                $('.widget-l8-l1 .widget-actual').html(dashboard.l8_l1);

                $('.panel-body').each(function( index ) {
                    var kpi = $(this).find('.widget-kpi').text();
                    var actual = $(this).find('.widget-actual').text();

                    if($(this).parent().hasClass('widget-c3b-cost')){
                        if(parseFloat(kpi.replace(/\,/g, '')) < parseFloat(actual.replace(/\,/g, ''))){
                            $(this).parent().addClass('kpi-gap');
                        }
                    }else if($(this).parent().hasClass('widget-budget')){
                        if(parseFloat(kpi.replace(/\,/g, '')) < 0){
                            $(this).parent().addClass('kpi-gap');
                        }
                    }else{
                        if(parseFloat(kpi.replace(/\,/g, '')) > parseFloat(actual.replace(/\,/g, ''))){
                            $(this).parent().addClass('kpi-gap');
                        }
                    }



                    if((kpi.length + actual.length) > 12
                        && (kpi.length + actual.length) < 24){
                        $(this).find('.widget-kpi').removeClass('font-xl').removeClass('line-height-md').removeClass('line-height-sm').addClass('line-height-md');
                        $(this).find('.widget-actual').removeClass('font-xl').removeClass('line-height-md').removeClass('line-height-sm').addClass('line-height-md');
                    }else if (kpi.length + actual.length > 24){
                        $(this).find('.widget-kpi').removeClass('font-xl').removeClass('line-height-md').removeClass('line-height-sm').addClass('line-height-sm');
                        $(this).find('.widget-actual').removeClass('font-xl').removeClass('line-height-md').removeClass('line-height-sm').addClass('line-height-sm');
                    }else{
                        $(this).find('.widget-kpi').removeClass('font-xl').removeClass('line-height-md').removeClass('line-height-sm').addClass('font-xl');
                        $(this).find('.widget-actual').removeClass('font-xl').removeClass('line-height-md').removeClass('line-height-sm').addClass('font-xl');
                    }
                });

            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        }

        function c3_leaderboard(self, period) {
            $('.c3_leaderboard').html('<h3>loading...</h3>');

            $('.widget-c3-leaderboard button').removeClass('active');
            $(self).addClass('active');

            $.get("{{ route('ajax-c3-leaderboard') }}", {period: period}, function (data) {
                $('.c3_leaderboard').html(data);
                $("#c3_leaderboard").tableHeadFixer({'z-index': 0});
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        }

        function revenue_leaderboard(self, period) {
            $('.revenue_leaderboard').html('<h3>loading...</h3>');

            $('.widget-revenue-leaderboard button').removeClass('active');
            $(self).addClass('active');
            var unit = $('input[name=currency]:checked').val();

            $.get("{{ route('ajax-revenue-leaderboard') }}", {period: period, unit: unit}, function (data) {
                $('.revenue_leaderboard').html(data);
                $("#revenue_leaderboard").tableHeadFixer({'z-index': 0});
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        }

        function spent_leaderboard(self, period) {
            $('.spent_leaderboard').html('<h3>loading...</h3>');

            $('.widget-spent-leaderboard button').removeClass('active');
            $(self).addClass('active');
            var unit = $('input[name=currency]:checked').val();

            $.get("{{ route('ajax-spent-leaderboard') }}", {period: period, unit: unit}, function (data) {
                $('.spent_leaderboard').html(data);
                $("#spent_leaderboard").tableHeadFixer({'z-index': 0});
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        }

        // 2018-04-17 LamVT [HEL-9] add dropdown for C3/L8 chart
        function get_c3_chart(month) {

            if (month < 10) {
                month = "0" + month.toString();
            }
            else {
                month = month.toString();
            }
            var marketer_id = $('#marketer').val();
            var channel_id  = $('#channel').val();

            $.get("{{ route('ajax-getC3Chart') }}",
                {
                    month       : month,
                    marketer_id : marketer_id,
                    channel_id  : channel_id
                },
            function (data) {
                var data_c3b = [
                    {data: $.parseJSON(data.chart_c3), label: "C3B"},
                    {data: $.parseJSON(data.chart_kpi), label: "KPI"},
                ];
                initChart($("#site-stats-c3"), data_c3b, [$chrt_third, $chrt_main]);
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        }

        var previousPoint = null, previousLabel = null;
        $.fn.UseTooltip = function (mode) {
            $(this).bind("plothover", function (event, pos, item) {
                if (item) {
                    if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                        previousPoint = item.dataIndex;
                        previousLabel = item.series.label;
                        $("#tooltip").remove();

                        var x = item.datapoint[0];
                        var y = item.datapoint[1];

                        var color = item.series.color;

                        var c3_total = jQuery.parseJSON($('#c3_total').val());
                        var x_index = getDate(x).split("/")[0];
                        var per = 0;
                        if (numberWithCommas(y) != 0)
                            per = (numberWithCommas(y) * 100 / c3_total[x_index]).toFixed(2);

                        var tooltip = '';
                        if(item.series.label == 'C3B'){
                            tooltip = "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + y + "</strong>";
                        } else if(item.series.label == 'KPI'){
                            tooltip = "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + y + "</strong>" + " (C3B)";
                        } else if (mode == 'C3AC3B') {
                            tooltip = "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + numberWithCommas(y) + " - " + per + " % </strong>";
                        }

                        showTooltip(item.pageX, item.pageY, color, tooltip);
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });
        };

        function showTooltip(x, y, color, contents) {

            $('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y - 40,
                left: x - 40,
                border: '2px solid ' + color,
                padding: '3px',
                'font-size': '9px',
                'border-radius': '5px',
                'background-color': '#fff',
                'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                opacity: 0.9
            }).appendTo("body").fadeIn(200);
        }
        function getDate(date) {
            var d = new Date(date);
            var curr_date = d.getDate();
            var curr_month = d.getMonth();
            curr_month++;

            return curr_date + "/" + curr_month;

        }
        function numberWithCommas(number) {
            var parts = number.toFixed().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }
        function get_C3AC3B(month) {
            var url = $('input[name="C3AC3B_url"]').val();
            var marketer_id = $('#marketer').val();
            var channel_id  = $('#channel').val();

            var data = {};
            data.marketer_id    = marketer_id;
            data.channel_id     = channel_id;
            data.month          = month;

            $("#C3A-C3B_chart").parent().parent().parent().parent().find('.loading').css("display", "block");
            $.get(url, data, function (rs) {
                set_C3AC3B(rs, $("#C3A-C3B_chart"), $('#C3A-C3B_chk'));
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        }
        function set_C3AC3B(rs, element, checkbox) {
            var item = element;
            var dataSet = [];
            var arr_color = [];

            var C3A_Duplicated = {data: jQuery.parseJSON(rs.C3A_Duplicated), label: "C3A-Duplicated"};
            var C3B_Under18 = {data: jQuery.parseJSON(rs.C3B_Under18), label: "C3B-Under18"};
            var C3B_Duplicated15Days = {data: jQuery.parseJSON(rs.C3B_Duplicated15Days), label: "C3B-Duplicated15Days"};
            var C3A_Test = {data: jQuery.parseJSON(rs.C3A_Test), label: "C3A-Test"};
            $('#c3_total').val(rs.c3);

            var lst_checkbox = checkbox.find('input[type=checkbox]:checked');
            jQuery.each(lst_checkbox, function (index, checkbox) {
                $label = $(checkbox).val();
                if ($label == 'C3A-Duplicated') {
                    dataSet.push(C3A_Duplicated);
                    arr_color.push('#800000');
                }
                if ($label == 'C3B-Under18') {
                    dataSet.push(C3B_Under18);
                    arr_color.push('#6A5ACD')
                }
                if ($label == 'C3B-Duplicated15Days') {
                    dataSet.push(C3B_Duplicated15Days);
                    arr_color.push('#808080')
                }
                if ($label == 'C3A-Test') {
                    dataSet.push(C3A_Test);
                    arr_color.push('#7CFC00')
                }
            });


            initChart(item, dataSet, arr_color, 'C3AC3B');
        }


        function initChart(item, data, arr_color, type) {
            var option = {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 1,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 0.1
                            }, {
                                opacity: 0.15
                            }]
                        }
                    },
                    points: {
                        show: true
                    },
                    shadowSize: 0
                },
                yaxes: [{
                    ticks: 10,
                    min: 0,
                }],
                xaxis: {
                    mode: "time",
                    timeformat: "%d/%m",
                    ticks: 14
                },
                grid: {
                    hoverable: true,
                    // clickable : true,
                    tickColor: $chrt_border_color,
                    borderWidth: 0,
                    borderColor: $chrt_border_color,
                },
                colors: arr_color,
            };

            if (item.length) {
                $.plot(item, data, option);
                item.UseTooltip(type);
                item.parent().parent().parent().parent().find('.loading').css("display", "none");
            }
            /* end site stats */
        }

    </script>
    @include('components.script-jarviswidget')
@endsection
{{-- end 2018-04-17 LamVT [HEL-9] add dropdown for C3/L8 chart--}}