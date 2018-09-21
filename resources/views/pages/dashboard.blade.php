@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

        @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs, 'currency' => true])
            <form action="/home" method="GET" class="form_search">
                {{ csrf_field()}}
                @component('components.currency')
                @endcomponent
            </form>
        @endcomponent

        <!-- widget grid -->
            <section id="widget-grid" class="">
                <fieldset style="background-color: white">
                    <legend>Filter
                        <a id="filter" href="javascript:void(0)"><i class="fa fa-angle-up fa-lg"></i></a>
                    </legend>
                <form class="smart-form" >
                    <div class="row" >
                        <section class="col col-3">
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
                        <section class="col col-3">
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
                        <section class="col col-3">
                            <div id="reportrange" class="pull-left"
                                 style="background: #fff; cursor: pointer; padding: 10px; margin: 20px 0px 0px 0px; border: 1px solid #ccc;">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span></span> <b class="caret"></b>
                            </div>
                            <div id="" class="pull-right"
                                 style="margin: 28px 0px 0px 0px; padding: 10px px 7px 10px;">
                                <button id="filter" class="btn btn-primary btn-sm" type="button" style="float: right" >
                                    <i class="fa fa-filter"></i>
                                    Filter
                                </button>
                            </div>
                        </section>
                    </div>
                </form>
                </fieldset>

                <!-- row -->
                <div class="row">
                    <div class="col-sm-6 col-lg-3">

                        <div class="panel panel-default widget-c3">
                            <div class="panel-body status">
                                <div class="who clearfix widget-title">
                                    <h4><i class="fa fa-lg fa-fw fa-child"></i><strong>C3 Total</strong></h4>
                                </div>
                                <div class="text text-align-left font-xs widget-caption">
                                    Actual / KPI
                                    <span class="widget-unit">C3</span>
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

                        <div class="panel panel-default widget-c3-cost">
                            <div class="panel-body status">
                                <div class="who clearfix widget-title">
                                    <h4><i class="fa fa-lg fa-fw fa-money"></i><strong>C3 Cost</strong></h4>
                                </div>
                                <div class="text text-align-left font-xs widget-caption">
                                    Actual / KPI
                                    <span class="widget-unit">USD</span>
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

                        <div class="panel panel-default widget-budget">
                            <div class="panel-body status">

                                <div class="who clearfix widget-title">
                                    <h4><i class="fa fa-lg fa-fw fa-credit-card"></i><strong>Budget</strong></h4>
                                </div>
                                <div class="text text-align-left font-xs widget-caption">
                                    Actual / KPI
                                    <span class="widget-unit">USD</span>
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

                    <div class="col-sm-6 col-lg-3">

                        <div class="panel panel-default widget-revenue">
                            <div class="panel-body status">

                                <div class="who clearfix widget-title">
                                    <h4><i class="fa fa-lg fa-fw fa-usd"></i><strong>Revenue</strong></h4>
                                </div>
                                <div class="text text-align-left font-xs widget-caption">
                                    Actual / KPI
                                    <span class="widget-unit">USD</span>
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

                <div class="row">
                    <article class="col-sm-12 col-md-12">

                    @component('components.jarviswidget',
                    ['id' => 'l8_chart', 'icon' => 'fa-line-chart', 'title' => "L8 in ", 'dropdown' => 'true'])
                        <!-- widget content -->
                            <div class="widget-body no-padding">

                                {{--<div class="widget-body-toolbar bg-color-white smart-form">--}}

                                {{--</div>--}}
                                <div id="site-stats-l8" class="chart has-legend"></div>
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
        <input type="hidden" name="l8_month" id="l8_month" value="{{$month}}">

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

    <link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to('css/custom-radio.css') }}">

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


            });
            cb(start, end);

            if ($("#site-stats-c3").length) {

                var plot = $.plot($("#site-stats-c3"), [
                    {data: {{ $dashboard["chart_c3"] }}, label: "C3B"},
                    {data: {{ $dashboard["chart_kpi"] }}, label: "KPI"},
                    ], {
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
                    xaxis: {
                        mode: "time",
                        timeformat: "%d/%m",
                        ticks: 14
                    },

                    yaxes: [{
                        ticks: 10,
                        min: 0,
                    }],
                    grid: {
                        hoverable: true,
                        clickable: true,
                        tickColor: $chrt_border_color,
                        borderWidth: 0,
                        borderColor: $chrt_border_color,
                    },
                    colors: [$chrt_third, $chrt_main],
                });

                $("#site-stats-c3").UseTooltip();

            }
            /* end site stats */

            if ($("#site-stats-l8").length) {

                var plot = $.plot($("#site-stats-l8"), [{
                    data: {{ $dashboard["chart_l8"] }},
                    label: "L8"
                }], {
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
                    xaxis: {
                        mode: "time",
                        timeformat: "%d/%m",
                        ticks: 14
                    },

                    yaxes: [{
                        ticks: 10,
                        min: 0,
                    }],
                    grid: {
                        hoverable: true,
                        clickable: true,
                        tickColor: $chrt_border_color,
                        borderWidth: 0,
                        borderColor: $chrt_border_color,
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: "<b>%y L8</b> (%x)",
                        dateFormat: "%d/%m/%Y",
                        defaultTheme: false,
                        shifts: {
                            x: -50,
                            y: 20
                        }
                    },
                    colors: [$chrt_second],
                });

            }
            /* end site stats */

            $('.today').click();

            $('input#currency').click(function (e) {
                var unit = $(this).val();
                $('#currency_unit').val(unit);

                var date = $('#reportrange span').html();
                date = date.split('-');

                var startDate = formatDate(date[0]);
                var endDate = formatDate(date[1]);

                dashboard(startDate, endDate, unit);
                $('.today').click();

            })

            $('button#filter').click(function (e) {
                var unit = $('#currency_unit').val();

                var date = $('#reportrange span').html();
                date = date.split('-');

                var startDate = formatDate(date[0]);
                var endDate = formatDate(date[1]);

                dashboard(startDate, endDate, unit);

                var c3_month = $('#c3_month').val();
                var l8_month = $('#l8_month').val();
                get_c3_chart(parseInt(c3_month));
                get_l8_chart(parseInt(l8_month));
            })

        });

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

            var startDate = start.format('YYYY-MM-DD');
            var endDate = end.format('YYYY-MM-DD');
            var unit = $('#currency_unit').val();

            dashboard(startDate, endDate, unit);

        }

        function dashboard(startDate, endDate, unit) {

            $('.widget-c3-cost .widget-unit').html(unit);
            $('.widget-budget .widget-unit').html(unit);
            $('.widget-revenue .widget-unit').html(unit);

            $('.widget-c3 .widget-actual').html('...');
            $('.widget-c3-cost .widget-actual').html('...');
            $('.widget-budget .widget-actual').html('...');
            $('.widget-revenue .widget-actual').html('...');

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
                $('.widget-c3-cost .widget-actual').html(dashboard.c3_cost);
                $('.widget-budget .widget-actual').html(dashboard.spent);
                $('.widget-revenue .widget-actual').html(dashboard.revenue);

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
            var unit = $('#currency_unit').val();

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
            var unit = $('#currency_unit').val();

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
                set_c3_chart(data);
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        }

        function set_c3_chart(data) {
            console.log(data.chart_c3);
            if ($("#site-stats-c3").length) {

                var plot = $.plot($("#site-stats-c3"), [
                        {data: $.parseJSON(data.chart_c3), label: "C3B"},
                        {data: $.parseJSON(data.chart_kpi), label: "KPI"},
                    ],
                    {
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
                    xaxis: {
                        mode: "time",
                        timeformat: "%d/%m",
                        ticks: 14
                    },

                    yaxes: [{
                        ticks: 10,
                        min: 0,
                    }],
                    grid: {
                        hoverable: true,
                        clickable: true,
                        tickColor: $chrt_border_color,
                        borderWidth: 0,
                        borderColor: $chrt_border_color,
                    },
                    colors: [$chrt_third, $chrt_main],
                });
            }
            /* end site stats */
            $("#site-stats-c3").UseTooltip();
        }

        function get_l8_chart(month) {

            if (month < 10) {
                month = "0" + month.toString();
            }
            else {
                month = month.toString();
            }
            var marketer_id = $('#marketer').val();
            var channel_id  = $('#channel').val();

            $.get("{{ route('ajax-getL8Chart') }}",
                {
                    month       : month,
                    marketer_id : marketer_id,
                    channel_id  : channel_id
                },
                function (data) {
                var obj = jQuery.parseJSON(data);
                set_l8_chart(obj);
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        }

        function set_l8_chart(data) {
            if ($("#site-stats-l8").length) {

                var plot = $.plot($("#site-stats-l8"), [{
                    data: data,
                    label: "L8"
                }], {
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
                    xaxis: {
                        mode: "time",
                        timeformat: "%d/%m",
                        ticks: 14
                    },

                    yaxes: [{
                        ticks: 10,
                        min: 0,
                    }],
                    grid: {
                        hoverable: true,
                        clickable: true,
                        tickColor: $chrt_border_color,
                        borderWidth: 0,
                        borderColor: $chrt_border_color,
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: "<b>%y L8</b> (%x)",
                        dateFormat: "%d/%m/%Y",
                        defaultTheme: false,
                        shifts: {
                            x: -50,
                            y: 20
                        }
                    },
                    colors: [$chrt_second],
                });

            }
            /* end site stats */
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

                        var tooltip = '';
                        if(item.series.label == 'C3B'){
                            tooltip = "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + y + "</strong>";
                        }
                        else if(item.series.label == 'KPI'){
                            tooltip = "<strong>" + item.series.label + "</strong><br>" + getDate(x) + " : <strong>" + y + "</strong>" + " (C3B)";
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

    </script>
    @include('components.script-jarviswidget')
@endsection
{{-- end 2018-04-17 LamVT [HEL-9] add dropdown for C3/L8 chart--}}