@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

        @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs, 'currency' => true])
            <form action="/home" method="GET" class="form_search">
                {{ csrf_field()}}
                <div id="reportrange" class="pull-right"
                     style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc;">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> <b class="caret"></b>
                </div>

                @component('components.currency')
                @endcomponent
            </form>
        @endcomponent

        <!-- widget grid -->
            <section id="widget-grid" class="">
                <!-- row -->
                <div class="row">

                    <article class="col-sm-12 col-md-12">

                        @component('components.jarviswidget',
                        ['id' => 1, 'icon' => 'fa-table', 'title' => 'Report'])
                            <div class="widget-body">

                                <form id="search-form-channel-report" class="smart-form" action="#" url="{!! route('channel-report.filter') !!}">
                                    <div class="row">
                                        <div id="reportrange" class="pull-left"
                                             style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; margin: 10px 15px">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                            <span class="registered_date"></span> <b class="caret"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button class="btn btn-primary btn-sm" type="submit"
                                                    style="margin-right: 15px">
                                                <i class="fa fa-filter"></i>
                                                Filter
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div class="loading" style="display: none">
                                    <div class="col-md-12 text-center">
                                        <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt=""
                                             style="width: 2%;"/>
                                    </div>
                                </div>
                                <hr>

                                <div class="row" id="wrapper_report">
                                    <div class="col-sm-12">
                                        <article class="col-sm-12 col-md-12">
                                            <table class="table table-bordered table-hover"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>Channel</th>
                                                    <th>KPI</th>
                                                    <th>C3B</th>
                                                    <th>C3BG</th>
                                                    <th>C3BG/C3B (%)</th>
                                                    <th>L1</th>
                                                    <th>L3</th>
                                                    <th>L6</th>
                                                    <th>L8</th>
                                                    <th>L3/C3BG (%)</th>
                                                    <th>L8/L1 (%)</th>
                                                    <th>Revenue</th>
                                                    <th>Spent</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                {{--@foreach ($array_channel as $i)--}}
                                                    {{--<tr>--}}
                                                        {{--<td>{{$i}}</td>--}}
                                                        {{--<td style="color:{{($table['c3'][$i]) >= ($table['c3_week'][$i]) ? 'green' : 'red'}}">{{$table['c3'][$i]}}</td>--}}
                                                        {{--<td style="color:{{$table['c3b'][$i] >= $table['c3b_week'][$i] ? 'green' : 'red'}}">{{$table['c3b'][$i]}}</td>--}}
                                                        {{--<td style="color:{{$table['c3bg'][$i] >= $table['c3bg_week'][$i] ? 'green' : 'red'}}">{{$table['c3bg'][$i]}}</td>--}}
                                                        {{--<td>{{($table['c3b'][$i] != 0) ? round($table['c3bg'][$i] * 100 / $table['c3b'][$i] , 2) : 0}}</td>--}}
                                                        {{--<td>{{$table['l1'][$i]}}</td>--}}
                                                        {{--<td>{{$table['l3'][$i]}}</td>--}}
                                                        {{--<td>{{$table['l6'][$i]}}</td>--}}
                                                        {{--<td>{{$table['l8'][$i]}}</td>--}}
                                                        {{--<td>{{($table['c3bg'][$i] != 0) ? round($table['l3'][$i] * 100 / $table['c3bg'][$i] , 2) : 0}}</td>--}}
                                                        {{--<td>{{($table['l1'][$i] != 0) ? round($table['l8'][$i] * 100 / $table['l1'][$i] , 2) : 0}}</td>--}}
                                                        {{--<td></td>--}}
                                                        {{--<td></td>--}}
                                                    {{--</tr>--}}
                                                {{--@endforeach--}}
                                                <tr>
                                                    {{--<th>Total</th>--}}
                                                    {{--<th>{{array_sum($table['c3'])}}</th>--}}
                                                    {{--<th>{{array_sum($table['c3b'])}}</th>--}}
                                                    {{--<th>{{array_sum($table['c3bg'])}}</th>--}}
                                                    {{--<th>{{(array_sum($table['c3b']) != 0) ? round(array_sum($table['c3bg']) * 100 / array_sum($table['c3b']) , 2) : 0}}</th>--}}
                                                    {{--<th>{{array_sum($table['l1'])}}</th>--}}
                                                    {{--<th>{{array_sum($table['l3'])}}</th>--}}
                                                    {{--<th>{{array_sum($table['l6'])}}</th>--}}
                                                    {{--<th>{{array_sum($table['l8'])}}</th>--}}
                                                    {{--<th>{{(array_sum($table['c3bg']) != 0) ? round(array_sum($table['l3']) * 100 / array_sum($table['c3bg']) , 2) : 0}}</th>--}}
                                                    {{--<th>{{(array_sum($table['l1']) != 0) ? round(array_sum($table['l8']) * 100 / array_sum($table['l1']) , 2) : 0}}</th>--}}
                                                    {{--<td></td>--}}
                                                    {{--<td></td>--}}
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </article>
                                    </div>

                                    <div class="col-sm-12">
                                        <article class="col-sm-12 col-md-12">
                                        @component('components.jarviswidget',
                                        ['id' => 'c3bg', 'icon' => 'fa-line-chart', 'title' => "C3B", 'dropdown' => 'false'])
                                            <!-- widget content -->
                                                <div class="widget-body no-padding">
                                                    <div id="c3bg_chart" class="chart has-legend"></div>
                                                </div>
                                            @endcomponent
                                        </article>
                                        <article class="col-sm-12 col-md-12">
                                        @component('components.jarviswidget',
                                        ['id' => 'c3b', 'icon' => 'fa-line-chart', 'title' => "C3B Price ", 'dropdown' => 'false'])
                                            <!-- widget content -->
                                                <div class="widget-body no-padding">
                                                    <div id="c3b_chart" class="chart has-legend"></div>
                                                </div>
                                            @endcomponent
                                        </article>

                                        <article class="col-sm-12 col-md-12">
                                        @component('components.jarviswidget',
                                        ['id' => 'c3', 'icon' => 'fa-line-chart', 'title' => "C3B Quality", 'dropdown' => 'false'])
                                            <!-- widget content -->
                                                <div class="widget-body no-padding">
                                                    <div id="c3_chart" class="chart has-legend"></div>
                                                </div>
                                            @endcomponent
                                        </article>

                                    </div>

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
                    "This Week": [moment().startOf("isoWeek"), moment().endOf("isoWeek")],
                    "Last Week": [moment().subtract(1, "week").startOf("isoWeek"), moment().subtract(1, "week").endOf("isoWeek")],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },


            }, cb);
            cb(start, end);

            {{--if ($("#site-stats-c3").length) {--}}

                {{--var plot = $.plot($("#site-stats-c3"), [{--}}
                    {{--data: {{ $dashboard["chart_c3"] }},--}}
                    {{--label: "C3"--}}
                {{--}], {--}}
                    {{--series: {--}}
                        {{--lines: {--}}
                            {{--show: true,--}}
                            {{--lineWidth: 1,--}}
                            {{--fill: true,--}}
                            {{--fillColor: {--}}
                                {{--colors: [{--}}
                                    {{--opacity: 0.1--}}
                                {{--}, {--}}
                                    {{--opacity: 0.15--}}
                                {{--}]--}}
                            {{--}--}}
                        {{--},--}}
                        {{--points: {--}}
                            {{--show: true--}}
                        {{--},--}}
                        {{--shadowSize: 0--}}
                    {{--},--}}
                    {{--xaxis: {--}}
                        {{--mode: "time",--}}
                        {{--timeformat: "%d/%m",--}}
                        {{--ticks: 14--}}
                    {{--},--}}

                    {{--yaxes: [{--}}
                        {{--ticks: 10,--}}
                        {{--min: 0,--}}
                    {{--}],--}}
                    {{--grid: {--}}
                        {{--hoverable: true,--}}
                        {{--clickable: true,--}}
                        {{--tickColor: $chrt_border_color,--}}
                        {{--borderWidth: 0,--}}
                        {{--borderColor: $chrt_border_color,--}}
                    {{--},--}}
                    {{--tooltip: true,--}}
                    {{--tooltipOpts: {--}}
                        {{--content: "<b>%y C3</b> (%x)",--}}
                        {{--dateFormat: "%d/%m/%Y",--}}
                        {{--defaultTheme: false,--}}
                        {{--shifts: {--}}
                            {{--x: -50,--}}
                            {{--y: 20--}}
                        {{--}--}}
                    {{--},--}}
                    {{--colors: [$chrt_main, $chrt_third],--}}
                {{--});--}}

            {{--}--}}
            /* end site stats */

            {{--if ($("#site-stats-l8").length) {--}}

                {{--var plot = $.plot($("#site-stats-l8"), [{--}}
                    {{--data: {{ $dashboard["chart_l8"] }},--}}
                    {{--label: "L8"--}}
                {{--}], {--}}
                    {{--series: {--}}
                        {{--lines: {--}}
                            {{--show: true,--}}
                            {{--lineWidth: 1,--}}
                            {{--fill: true,--}}
                            {{--fillColor: {--}}
                                {{--colors: [{--}}
                                    {{--opacity: 0.1--}}
                                {{--}, {--}}
                                    {{--opacity: 0.15--}}
                                {{--}]--}}
                            {{--}--}}
                        {{--},--}}
                        {{--points: {--}}
                            {{--show: true--}}
                        {{--},--}}
                        {{--shadowSize: 0--}}
                    {{--},--}}
                    {{--xaxis: {--}}
                        {{--mode: "time",--}}
                        {{--timeformat: "%d/%m",--}}
                        {{--ticks: 14--}}
                    {{--},--}}

                    {{--yaxes: [{--}}
                        {{--ticks: 10,--}}
                        {{--min: 0,--}}
                    {{--}],--}}
                    {{--grid: {--}}
                        {{--hoverable: true,--}}
                        {{--clickable: true,--}}
                        {{--tickColor: $chrt_border_color,--}}
                        {{--borderWidth: 0,--}}
                        {{--borderColor: $chrt_border_color,--}}
                    {{--},--}}
                    {{--tooltip: true,--}}
                    {{--tooltipOpts: {--}}
                        {{--content: "<b>%y L8</b> (%x)",--}}
                        {{--dateFormat: "%d/%m/%Y",--}}
                        {{--defaultTheme: false,--}}
                        {{--shifts: {--}}
                            {{--x: -50,--}}
                            {{--y: 20--}}
                        {{--}--}}
                    {{--},--}}
                    {{--colors: [$chrt_second],--}}
                {{--});--}}

            {{--}--}}
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

            $.get("{{ route('ajax-dashboard') }}", {
                startDate: startDate,
                endDate: endDate,
                unit: unit
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
                    data: data,
                    label: "C3"
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
                        content: "<b>%y C3</b> (%x)",
                        dateFormat: "%d/%m/%Y",
                        defaultTheme: false,
                        shifts: {
                            x: -50,
                            y: 20
                        }
                    },
                    colors: [$chrt_main, $chrt_third],
                });

            }
            /* end site stats */
        }

        function get_l8_chart(month) {

            if (month < 10) {
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

    </script>
    @include('components.script-jarviswidget')
@endsection
{{-- end 2018-04-17 LamVT [HEL-9] add dropdown for C3/L8 chart--}}