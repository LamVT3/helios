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
                    ['id' => 'chart', 'icon' => 'fa-line-chart', 'title' => "Edit Policy"])
                        <!-- widget content -->
                            <div class="widget-body no-padding">
                                <form id="checkout-form" class="smart-form" novalidate="novalidate">
                                    <div class="table-responsive">

                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Rank</th>
                                                <th>Min Revenue (VND)</th>
                                                <th>Fix Salary (Baht)</th>
                                                <th>ME/RE (%)</th>
                                                <th>COM/REV (%)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @for($i = 0; $i < 8; $i++)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>
                                                    <label class="input">
                                                        <input type="text" name="min_rev[{{$i}}]" placeholder="">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="input">
                                                        <input type="text" name="fix_salary[{{$i}}]" placeholder="">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="input">
                                                        <input type="text" name="me_re[{{$i}}]" placeholder="">
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="input">
                                                        <input type="text" name="com_re[{{$i}}]" placeholder="">
                                                    </label>
                                                </td>
                                            </tr>
                                            @endfor
                                            </tbody>
                                        </table>

                                    </div>

                                    <footer>
                                        <button type="submit" class="btn btn-primary">
                                            Save Policy
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