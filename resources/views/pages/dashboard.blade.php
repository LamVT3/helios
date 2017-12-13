@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

        @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
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
                                    6,200
                                </div>
                                <div class="text text-align-right font-sm widget-kpi">
                                    12,000
                                </div>
                                <ul class="links widget-progress">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-color-red" role="progressbar" style="width: 56%;vertical-align: top;line-height: unset;">56%</div>
                                    </div>
                                </ul>
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
                                    2.1
                                </div>
                                <div class="text text-align-right font-sm widget-kpi">
                                    2.3
                                </div>
                                <ul class="links widget-progress">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-color-red" role="progressbar" style="width: 90%;vertical-align: top;line-height: unset;">90%</div>
                                    </div>
                                </ul>
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
                                    1,203
                                </div>
                                <div class="text text-align-right font-sm widget-kpi">
                                    2,000
                                </div>
                                <ul class="links widget-progress">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-color-red" role="progressbar" style="width: 60%;vertical-align: top;line-height: unset;">60%</div>
                                    </div>
                                </ul>
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
                                    <span class="widget-unit">VND</span>
                                </div>
                                <div class="text text-align-right font-xl widget-actual">
                                    1,203,000,000
                                </div>
                                <div class="text text-align-right font-sm widget-kpi">
                                    2,000,000,000
                                </div>
                                <ul class="links widget-progress">
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-color-red" role="progressbar" style="width: 60%;vertical-align: top;line-height: unset;">60%</div>
                                    </div>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- end row -->

                <div class="row">
                    <article class="col-sm-12 col-md-12">

                    @component('components.jarviswidget',
                    ['id' => 'chart', 'icon' => 'fa-line-chart', 'title' => "Chart"])
                        <!-- widget content -->
                            <div class="widget-body no-padding">

                                <div class="widget-body-toolbar bg-color-white smart-form" id="rev-toggles">

                                    <div class="inline-group">

                                        <label for="gra-1" class="checkbox" style="color: #ff0c00; font-weight: bold">
                                            <input type="checkbox" name="gra-1" id="gra-1" checked="checked">
                                            <i></i> KPI </label>
                                        <label for="gra-2" class="checkbox" style="color: #00c01a; font-weight: bold">
                                            <input type="checkbox" name="gra-2" id="gra-2" checked="checked">
                                            <i></i> Real Achievement </label>
                                    </div>

                                </div>

                                <div class="padding-10">
                                    <div id="flotcontainer" class="chart-large has-legend-unique"></div>
                                </div>
                            </div>
                        @endcomponent
                    </article>

                </div>

                <!-- row -->

                <div class="row">

                    <article class="col-sm-12 col-md-12 col-lg-4">

                    @component('components.jarviswidget',
                    ['id' => 0, 'icon' => 'fa-table', 'title' => "Activities"])

                        <!-- widget content -->
                            <div class="widget-body no-padding">

                                <table id="table_activities" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <tbody>
                                    <tr>
                                        <td>Blue has updated her work.</td>
                                        <td>1 min ago</td>
                                    </tr>
                                    <tr>
                                        <td>Changji has updated her work.</td>
                                        <td>2 hrs ago</td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                            <!-- end widget content -->

                        @endcomponent


                    </article>

                    <article class="col-sm-12 col-md-12 col-lg-8">

                        @component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Newest C3'])
                            <div class="widget-body no-padding">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Created at</th>
                                    </tr>
                                    </thead>
                                    <tbody>


                                    </tbody>
                                </table>
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

    <script type="text/javascript">
        // DO NOT REMOVE : GLOBAL FUNCTIONS!
        $(document).ready(function () {

// TAB THREE GRAPH //
            /* TAB 3: Revenew  */

            $(function () {

                var customers = 0,
                    tours = 0,
                    hotels = 0,
                    cars = 0,
                    activities = 0,
                    toggles = $("#rev-toggles"), target = $("#flotcontainer");

                var data = [{
                    label: "Số lượng khách hàng",
                    data: customers,
                    color: '#3276B1',
                    lines: {
                        show: true,
                        lineWidth: 3
                    },
                    points: {
                        show: true
                    }
                }, {
                    label: "Booking tour",
                    data: tours,
                    color: '#ff000a',
                    lines: {
                        show: true,
                        lineWidth: 1
                    },
                    points: {
                        show: true
                    }
                }, {
                    label: "Booking khách sạn",
                    data: hotels,
                    color: '#00bf19',
                    lines: {
                        show: true,
                        lineWidth: 1
                    },
                    points: {
                        show: true
                    }
                }, {
                    label: "Booking thuê xe",
                    data: cars,
                    color: '#888888',
                    lines: {
                        show: true,
                        lineWidth: 1
                    },
                    points: {
                        show: true
                    }
                }, {
                    label: "Booking vé tham quan",
                    data: activities,
                    color: '#ff00a7',
                    lines: {
                        show: true,
                        lineWidth: 1
                    },
                    points: {
                        show: true
                    }
                }]

                var options = {
                    grid: {
                        hoverable: true
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: '%s tháng %x: %y',
                        dateFormat: '%m',
                        defaultTheme: false,
                        shifts: {
                            x: -50,
                            y: 20
                        }
                    },
                    xaxis: {
                        mode: "time",
                        timeformat: "%d/%m/%Y"
                    },
                    yaxes: {
                        tickFormatter: function (val, axis) {
                            return val;
                        },
                        max: 1200
                    }

                };

                plot2 = null;

                function plotNow() {
                    var d = [];
                    toggles.find(':checkbox').each(function () {
                        if ($(this).is(':checked')) {
                            d.push(data[$(this).attr("name").substr(4, 1)]);
                        }
                    });
                    if (d.length > 0) {
                        if (plot2) {
                            plot2.setData(d);
                            plot2.draw();
                        } else {
                            plot2 = $.plot(target, d, options);
                        }
                    }

                };

                toggles.find(':checkbox').on('change', function () {
                    plotNow();
                });
                plotNow()

            });

        });

    </script>

@endsection