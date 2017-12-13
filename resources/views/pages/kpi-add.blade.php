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

                <div class="row">
                    <article class="col-sm-12 col-md-12">

                    @component('components.jarviswidget',
                    ['id' => 'chart', 'icon' => 'fa-line-chart', 'title' => "Add KPI"])
                        <!-- widget content -->
                            <div class="widget-body no-padding">
                                <form id="checkout-form" class="smart-form" novalidate="novalidate">

                                    <fieldset>
                                        <div class="row">
                                            <section class="col col-3">
                                                <label class="label"><strong>Time Type</strong></label>
                                                <label class="select">
                                                    <select name="time_type">
                                                        <option value="day">Day</option>
                                                        <option value="week">Week</option>
                                                        <option value="month" selected>Month</option>
                                                        <option value="year">Year</option>
                                                    </select> <i></i> </label>
                                            </section>

                                            <section class="col col-3">
                                                <label class="label"><strong>Time Value</strong></label>
                                                <label class="select">
                                                    <select name="time_value">
                                                        <option value="1">January</option>
                                                        <option value="1">February</option>
                                                        <option value="3">March</option>
                                                        <option value="4">April</option>
                                                        <option value="5">May</option>
                                                        <option value="6">June</option>
                                                        <option value="7">July</option>
                                                        <option value="8">August</option>
                                                        <option value="9">September</option>
                                                        <option value="10">October</option>
                                                        <option value="11" selected>November</option>
                                                        <option value="12">December</option>
                                                    </select> <i></i> </label>
                                            </section>

                                            <section class="col col-3">
                                                <label class="label"><strong>KPI Type</strong></label>
                                                <label class="select">
                                                    <select name="kpi_type">
                                                        <option value="c3b_cost">C3B Cost</option>
                                                        <option value="c3b" selected>C3B</option>
                                                        <option value="l3/c3bg">L3/C3Bg</option>
                                                    </select> <i></i> </label>
                                            </section>

                                            <section class="col col-3">
                                                <label class="label"><strong>KPI Value</strong></label>
                                                <label class="input">
                                                    <input type="text" name="kpi_value" placeholder="Kpi Value">
                                                </label>
                                            </section>
                                        </div>

                                    </fieldset>

                                    <footer>
                                        <button type="submit" class="btn btn-primary">
                                            Add KPI
                                        </button>
                                    </footer>
                                </form>

                            </div>
                        @endcomponent
                    </article>

                </div>

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