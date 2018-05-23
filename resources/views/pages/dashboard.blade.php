@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

        @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
            <form action="/home" method="GET" class="form_search">
                {{ csrf_field()}}
                <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc;">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> <b class="caret"></b>
                </div>
            </form>

        @endcomponent

        <!-- widget grid -->
            <section id="widget-grid" class="">

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
                                    <span class="widget-unit">VND</span>
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
                                    <span class="widget-unit">Baht</span>
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
                    ['id' => 'c3_chart', 'icon' => 'fa-line-chart', 'title' => "C3 in " , 'dropdown' => "true"])
                        <!-- widget content -->
                            <div class="widget-body no-padding">

                                <div class="widget-body-toolbar bg-color-white smart-form" id="rev-toggles">

                                </div>
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

                                <div class="widget-body-toolbar bg-color-white smart-form">

                                </div>
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
                    ['id' => 0, 'icon' => 'fa-table', 'title' => "C3 Leaderboard"])

                        <!-- widget content -->
                            <div class="widget-body no-padding widget-c3-leaderboard">
                                <div class="alert alert-info no-margin fade in">
                                    <button class="btn btn-xs btn-default today" onclick="c3_leaderboard(this, 'today')">
                                        Today
                                    </button>
                                    <button class="btn btn-xs btn-default thisweek" onclick="c3_leaderboard(this, 'thisweek')">
                                        This Week
                                    </button>
                                    <button class="btn btn-xs btn-default thismonth" onclick="c3_leaderboard(this, 'thismonth')">
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
                                    <button class="btn btn-xs btn-default today" onclick="revenue_leaderboard(this, 'today')">
                                        Today
                                    </button>
                                    <button class="btn btn-xs btn-default" onclick="revenue_leaderboard(this, 'thisweek')">
                                        This Week
                                    </button>
                                    <button class="btn btn-xs btn-default" onclick="revenue_leaderboard(this, 'thismonth')">
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
                                    <button class="btn btn-xs btn-default today" onclick="spent_leaderboard(this, 'today')">
                                        Today
                                    </button>
                                    <button class="btn btn-xs btn-default" onclick="spent_leaderboard(this, 'thisweek')">
                                        This Week
                                    </button>
                                    <button class="btn btn-xs btn-default" onclick="spent_leaderboard(this, 'thismonth')">
                                        This Month
                                    </button>
                                </div>
                                <div class="spent_leaderboard" >
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
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

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

            pageSetUp();

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
                    "This Week":[moment().startOf("isoWeek"),moment().endOf("isoWeek")],
                    "Last Week":[moment().subtract(1,"week").startOf("isoWeek"),moment().subtract(1,"week").endOf("isoWeek")],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },


            },cb);
            cb(start, end);

            if ($("#site-stats-c3").length) {

                var plot = $.plot($("#site-stats-c3"), [{
                    data : {{ $dashboard["chart_c3"] }},
                    label : "C3"
                }], {
                    series : {
                        lines : {
                            show : true,
                            lineWidth : 1,
                            fill : true,
                            fillColor : {
                                colors : [{
                                    opacity : 0.1
                                }, {
                                    opacity : 0.15
                                }]
                            }
                        },
                        points : {
                            show : true
                        },
                        shadowSize : 0
                    },
                    xaxis : {
                        mode: "time",
                        timeformat: "%d/%m",
                        ticks : 14
                    },

                    yaxes : [{
                        ticks : 10,
                        min : 0,
                    }],
                    grid : {
                        hoverable : true,
                        clickable : true,
                        tickColor : $chrt_border_color,
                        borderWidth : 0,
                        borderColor : $chrt_border_color,
                    },
                    tooltip : true,
                    tooltipOpts : {
                        content : "<b>%y C3</b> (%x)",
                        dateFormat : "%d/%m/%Y",
                        defaultTheme : false,
                        shifts: {
                            x: -50,
                            y: 20
                        }
                    },
                    colors : [$chrt_main,$chrt_third],
                });

            }
            /* end site stats */

            if ($("#site-stats-l8").length) {

                var plot = $.plot($("#site-stats-l8"), [{
                    data : {{ $dashboard["chart_l8"] }},
                    label : "L8"
                }], {
                    series : {
                        lines : {
                            show : true,
                            lineWidth : 1,
                            fill : true,
                            fillColor : {
                                colors : [{
                                    opacity : 0.1
                                }, {
                                    opacity : 0.15
                                }]
                            }
                        },
                        points : {
                            show : true
                        },
                        shadowSize : 0
                    },
                    xaxis : {
                        mode: "time",
                        timeformat: "%d/%m",
                        ticks : 14
                    },

                    yaxes : [{
                        ticks : 10,
                        min : 0,
                    }],
                    grid : {
                        hoverable : true,
                        clickable : true,
                        tickColor : $chrt_border_color,
                        borderWidth : 0,
                        borderColor : $chrt_border_color,
                    },
                    tooltip : true,
                    tooltipOpts : {
                        content : "<b>%y L8</b> (%x)",
                        dateFormat : "%d/%m/%Y",
                        defaultTheme : false,
                        shifts: {
                            x: -50,
                            y: 20
                        }
                    },
                    colors : [$chrt_second],
                });

            }
            /* end site stats */

            $('.today').click();

        });

        // PAGE RELATED SCRIPTS
        function cb(start, end) {
            $('#reportrange span').html(start.format('D/M/Y') + '-' + end.format('D/M/Y'));

            $('.widget-c3 .widget-actual').html('...');
            $('.widget-c3-cost .widget-actual').html('...');
            $('.widget-budget .widget-actual').html('...');
            $('.widget-revenue .widget-actual').html('...');

            var startDate = start.format('YYYY-MM-DD');
            var endDate = end.format('YYYY-MM-DD');

            $.get("{{ route('ajax-dashboard') }}", {startDate: startDate, endDate: endDate}, function (data) {
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

        function c3_leaderboard(self, period){
            $('.c3_leaderboard').html('<h3>loading...</h3>');

            $('.widget-c3-leaderboard button').removeClass('active');
            $(self).addClass('active');

            $.get("{{ route('ajax-c3-leaderboard') }}", {period: period}, function (data) {
                $('.c3_leaderboard').html(data);
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        }

        function revenue_leaderboard(self, period){
            $('.revenue_leaderboard').html('<h3>loading...</h3>');

            $('.widget-revenue-leaderboard button').removeClass('active');
            $(self).addClass('active');

            $.get("{{ route('ajax-revenue-leaderboard') }}", {period: period}, function (data) {
                $('.revenue_leaderboard').html(data);
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        }

        function spent_leaderboard(self, period){
            $('.spent_leaderboard').html('<h3>loading...</h3>');

            $('.widget-spent-leaderboard button').removeClass('active');
            $(self).addClass('active');

            $.get("{{ route('ajax-spent-leaderboard') }}", {period: period}, function (data) {
                $('.spent_leaderboard').html(data);
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        }

        // 2018-04-17 LamVT [HEL-9] add dropdown for C3/L8 chart
        function get_c3_chart(month) {

            if(month < 10){
                month = "0" + month.toString();
            }
            else {
                month = month.toString();
            }

            $.get("{{ route('ajax-getC3Chart') }}", {month: month}, function (data) {
                var obj = jQuery.parseJSON(data);
                set_c3_chart(obj);
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        }

        function set_c3_chart(data) {
            if ($("#site-stats-c3").length) {

                var plot = $.plot($("#site-stats-c3"), [{
                    data : data,
                    label : "C3"
                }], {
                    series : {
                        lines : {
                            show : true,
                            lineWidth : 1,
                            fill : true,
                            fillColor : {
                                colors : [{
                                    opacity : 0.1
                                }, {
                                    opacity : 0.15
                                }]
                            }
                        },
                        points : {
                            show : true
                        },
                        shadowSize : 0
                    },
                    xaxis : {
                        mode: "time",
                        timeformat: "%d/%m",
                        ticks : 14
                    },

                    yaxes : [{
                        ticks : 10,
                        min : 0,
                    }],
                    grid : {
                        hoverable : true,
                        clickable : true,
                        tickColor : $chrt_border_color,
                        borderWidth : 0,
                        borderColor : $chrt_border_color,
                    },
                    tooltip : true,
                    tooltipOpts : {
                        content : "<b>%y C3</b> (%x)",
                        dateFormat : "%d/%m/%Y",
                        defaultTheme : false,
                        shifts: {
                            x: -50,
                            y: 20
                        }
                    },
                    colors : [$chrt_main,$chrt_third],
                });

            }
            /* end site stats */
        }

        function get_l8_chart(month) {

            if(month < 10){
                month = "0" + month.toString();
            }
            else {
                month = month.toString();
            }

            $.get("{{ route('ajax-getL8Chart') }}", {month: month}, function (data) {
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
                    data : data,
                    label : "L8"
                }], {
                    series : {
                        lines : {
                            show : true,
                            lineWidth : 1,
                            fill : true,
                            fillColor : {
                                colors : [{
                                    opacity : 0.1
                                }, {
                                    opacity : 0.15
                                }]
                            }
                        },
                        points : {
                            show : true
                        },
                        shadowSize : 0
                    },
                    xaxis : {
                        mode: "time",
                        timeformat: "%d/%m",
                        ticks : 14
                    },

                    yaxes : [{
                        ticks : 10,
                        min : 0,
                    }],
                    grid : {
                        hoverable : true,
                        clickable : true,
                        tickColor : $chrt_border_color,
                        borderWidth : 0,
                        borderColor : $chrt_border_color,
                    },
                    tooltip : true,
                    tooltipOpts : {
                        content : "<b>%y L8</b> (%x)",
                        dateFormat : "%d/%m/%Y",
                        defaultTheme : false,
                        shifts: {
                            x: -50,
                            y: 20
                        }
                    },
                    colors : [$chrt_second],
                });

            }
            /* end site stats */
        }

    </script>
    @include('components.script-jarviswidget')
@endsection
{{-- end 2018-04-17 LamVT [HEL-9] add dropdown for C3/L8 chart--}}