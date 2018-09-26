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
                <fieldset style="background-color: white">
                    <legend>Filter
                        <a id="filter" href="javascript:void(0)"><i class="fa fa-angle-up fa-lg"></i></a>
                    </legend>
                <form class="smart-form" >
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
                            <div id="wrapper_currency" style="border: 1px solid #ccc; float: left; padding: 5px 0px 5px 10px; width: 98%;">
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
                            <i></i>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-sm-6 col-lg-3">
                            <div id="" class="pull-left"
                                 style="margin: 10px 0px 0px 0px; padding: 10px px 7px 10px;">
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
                                    <h4><i class="fa fa-lg fa-fw fa-child"></i><strong>C3B Total</strong></h4>
                                </div>
                                <div class="text text-align-left font-xs widget-caption">
                                    Actual / KPI
                                    <span class="widget-unit">C3B</span>
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
        .select2-container
        {
            width: auto; !important;
        }
        input#currency
        {
            margin-right: 5px;
        }
        #wrapper_currency label
        {
            margin-right: 15px;
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
                        select_channel += "<option value=" + item.id + "> " + item.name + " </option>";
                    });

                    $('#channel').html(select_channel);
                    $("#subcampaign_id").select2();
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