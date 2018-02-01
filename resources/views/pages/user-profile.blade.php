
@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">
        <!-- MAIN CONTENT -->
        <div id="content">
            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs]) @endcomponent
            <div class="well">
                <div class="row">
                    <div class="col-sm-2">
                        <img src="{{ asset('img/avatars/sunny-big.png') }}" alt="demo user">
                        <div class="padding-10">
                            <h4 class="font-md"><strong>{{ auth()->user()->username }}</strong>
                            <br>
                            <small class="text-danger">Marketer</small></h4>
                        </div>
                    </div>
                    <div class="col col-sm-10">
                        {{--<div class="row">
                            <div class="col col-sm-5">
                                <div><strong>Name</strong>: Admin</div>
                                <div><strong>Team</strong>: abc</div>
                            </div>
                            <div class="col col-sm-5">

                            </div>
                        </div>--}}
                        <table class="table table-striped table-profile table-forum">
                            <thead>
                            <tr>
                                <th>Rank</th>
                                <th class="text-center hidden-xs hidden-sm">Total C3</th>
                                <th class="text-center hidden-xs hidden-sm">Total L8</th>
                                <th class="text-center hidden-xs hidden-sm">Total Revenue</th>
                            </tr>
                            </thead>
                            <tbody>

                            <!-- TR -->
                            <tr>
                                <td>
                                    <a href="javascript:void(0);">{{ $user->rank }}</a>
                                </td>
                                <td class="text-center hidden-xs hidden-sm">
                                    <a href="javascript:void(0);">{{ number_format($profile['c3']) }}</a>
                                </td>
                                <td class="text-center hidden-xs hidden-sm">
                                    <a href="javascript:void(0);">{{ number_format($profile['l8']) }}</a>
                                </td>
                                <td class="text-center hidden-xs hidden-sm">
                                    <a href="javascript:void(0);">{{ number_format($profile['revenue']) . ' VND' }}</a>
                                </td>
                            </tr>
                            <!-- end TR -->

                            </tbody>
                        </table>
                    </div>
                </div>


            </div>

                <div class="row">

                    <!-- NEW WIDGET START -->
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <!-- Widget ID (each widget will need unique ID)-->
                        <div class="jarviswidget" id="wid-id-7" data-widget-editbutton="false">
                            <!-- widget options:
                            usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

                            data-widget-colorbutton="false"
                            data-widget-editbutton="false"
                            data-widget-togglebutton="false"
                            data-widget-deletebutton="false"
                            data-widget-fullscreenbutton="false"
                            data-widget-custombutton="false"
                            data-widget-collapsed="true"
                            data-widget-sortable="false"

                            -->
                            <header>
                                <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                                <h2>C3 - L8 Statistics</h2>

                            </header>

                            <!-- widget div-->
                            <div>

                                <!-- widget edit box -->
                                <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->

                                </div>
                                <!-- end widget edit box -->

                                <!-- widget content -->
                                <div class="widget-body no-padding">

                                    <div id="site-stats" class="chart has-legend"></div>

                                </div>
                                <!-- end widget content -->

                            </div>
                            <!-- end widget div -->

                        </div>
                        <!-- end widget -->

                    </article>
                    <!-- WIDGET END -->

                </div>
        </div>
    </div>
@endsection

@section('script')

    <!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
    <script src="js/plugin/flot/jquery.flot.cust.min.js"></script>
    <script src="js/plugin/flot/jquery.flot.resize.min.js"></script>
    <script src="js/plugin/flot/jquery.flot.fillbetween.min.js"></script>
    <script src="js/plugin/flot/jquery.flot.orderBar.min.js"></script>
    <script src="js/plugin/flot/jquery.flot.pie.min.js"></script>
    <script src="js/plugin/flot/jquery.flot.time.min.js"></script>
    <script src="js/plugin/flot/jquery.flot.tooltip.min.js"></script>

    <script type="text/javascript">
        // PAGE RELATED SCRIPTS

        /* chart colors default */
        var $chrt_border_color = "#efefef";
        var $chrt_grid_color = "#DDD"
        var $chrt_main = "#E24913";
        /* red       */
        var $chrt_second = "#6595b4";
        /* blue      */
        var $chrt_third = "#FF9F01";
        /* orange    */
        var $chrt_fourth = "#7e9d3a";
        /* green     */
        var $chrt_fifth = "#BD362F";
        /* dark red  */
        var $chrt_mono = "#000";

        $(document).ready(function() {

            // DO NOT REMOVE : GLOBAL FUNCTIONS!
            pageSetUp();

            /* sales chart */


            /* bar-chart-h */
            if ($("#bar-chart-h").length) {
                //Display horizontal graph
                var d1_h = [];
                for (var i = 0; i <= 3; i += 1)
                    d1_h.push([parseInt(Math.random() * 30), i]);

                var d2_h = [];
                for (var i = 0; i <= 3; i += 1)
                    d2_h.push([parseInt(Math.random() * 30), i]);

                var d3_h = [];
                for (var i = 0; i <= 3; i += 1)
                    d3_h.push([parseInt(Math.random() * 30), i]);

                var ds_h = new Array();
                ds_h.push({
                    data : d1_h,
                    bars : {
                        horizontal : true,
                        show : true,
                        barWidth : 0.2,
                        order : 1,
                    }
                });
                ds_h.push({
                    data : d2_h,
                    bars : {
                        horizontal : true,
                        show : true,
                        barWidth : 0.2,
                        order : 2
                    }
                });
                ds_h.push({
                    data : d3_h,
                    bars : {
                        horizontal : true,
                        show : true,
                        barWidth : 0.2,
                        order : 3
                    }
                });

                // display graph
                $.plot($("#bar-chart-h"), ds_h, {
                    colors : [$chrt_second, $chrt_fourth, "#666", "#BBB"],
                    grid : {
                        show : true,
                        hoverable : true,
                        clickable : true,
                        tickColor : $chrt_border_color,
                        borderWidth : 0,
                        borderColor : $chrt_border_color,
                    },
                    legend : true,
                    tooltip : true,
                    tooltipOpts : {
                        content : "<b>%x</b> = <span>%y</span>",
                        defaultTheme : false
                    }
                });

            }

            /* end bar-chart-h


            /* site stats chart */

            if ($("#site-stats").length) {

                var pageviews = [[1, 75], [3, 87], [4, 93], [5, 127], [6, 116], [7, 137], [8, 135], [9, 130], [10, 167], [11, 169], [12, 179], [13, 185], [14, 176], [15, 180], [16, 174], [17, 193], [18, 186], [19, 177], [20, 153], [21, 149], [22, 130], [23, 100], [24, 50]];
                var visitors = [[1, 65], [3, 50], [4, 73], [5, 100], [6, 95], [7, 103], [8, 111], [9, 97], [10, 125], [11, 100], [12, 95], [13, 141], [14, 126], [15, 131], [16, 146], [17, 158], [18, 160], [19, 151], [20, 125], [21, 110], [22, 100], [23, 85], [24, 37]];
                //console.log(pageviews)
                var plot = $.plot($("#site-stats"), [{
                    data : pageviews,
                    label : "Your pageviews"
                }, {
                    data : visitors,
                    label : "Site visitors"
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
                        mode : "time",
                        tickLength : 10
                    },

                    yaxes : [{
                        min : 20,
                        tickLength : 5
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
                        content : "%s for <b>%x:00 hrs</b> was %y",
                        dateFormat : "%y-%0m-%0d",
                        defaultTheme : false
                    },
                    colors : [$chrt_main, $chrt_second],
                    xaxis : {
                        ticks : 15,
                        tickDecimals : 2
                    },
                    yaxis : {
                        ticks : 15,
                        tickDecimals : 0
                    },
                });

            }

            /* end site stats */

            /* updating chart */

            if ($('#updating-chart').length) {

                // For the demo we use generated data, but normally it would be coming from the server
                var data = [], totalPoints = 200;
                function getRandomData() {
                    if (data.length > 0)
                        data = data.slice(1);

                    // do a random walk
                    while (data.length < totalPoints) {
                        var prev = data.length > 0 ? data[data.length - 1] : 50;
                        var y = prev + Math.random() * 10 - 5;
                        if (y < 0)
                            y = 0;
                        if (y > 100)
                            y = 100;
                        data.push(y);
                    }

                    // zip the generated y values with the x values
                    var res = [];
                    for (var i = 0; i < data.length; ++i)
                        res.push([i, data[i]])
                    return res;
                }

                // setup control widget
                var updateInterval = 1000;
                $("#updating-chart").val(updateInterval).change(function() {
                    var v = $(this).val();
                    if (v && !isNaN(+v)) {
                        updateInterval = +v;
                        if (updateInterval < 1)
                            updateInterval = 1;
                        if (updateInterval > 2000)
                            updateInterval = 2000;
                        $(this).val("" + updateInterval);
                    }
                });

                // setup plot
                var options = {
                    yaxis : {
                        min : 0,
                        max : 100
                    },
                    xaxis : {
                        min : 0,
                        max : 100
                    },
                    colors : [$chrt_fourth],
                    series : {
                        lines : {
                            lineWidth : 1,
                            fill : true,
                            fillColor : {
                                colors : [{
                                    opacity : 0.4
                                }, {
                                    opacity : 0
                                }]
                            },
                            steps : false

                        }
                    }
                };
                var plot = $.plot($("#updating-chart"), [getRandomData()], options);

                function update() {
                    plot.setData([getRandomData()]);
                    // since the axes don't change, we don't need to call plot.setupGrid()
                    plot.draw();

                    setTimeout(update, updateInterval);
                }

                update();

            }

            /*end updating chart*/

        });

        /* end flot charts */

    </script><script type="text/javascript">
        // PAGE RELATED SCRIPTS

        /* chart colors default */
        var $chrt_border_color = "#efefef";
        var $chrt_grid_color = "#DDD"
        var $chrt_main = "#E24913";
        /* red       */
        var $chrt_second = "#6595b4";
        /* blue      */
        var $chrt_third = "#FF9F01";
        /* orange    */
        var $chrt_fourth = "#7e9d3a";
        /* green     */
        var $chrt_fifth = "#BD362F";
        /* dark red  */
        var $chrt_mono = "#000";

        $(document).ready(function() {

            // DO NOT REMOVE : GLOBAL FUNCTIONS!
            pageSetUp();

            /* site stats chart */

            if ($("#site-stats").length) {

                var pageviews = [[1, 75], [3, 87], [4, 93], [5, 127], [6, 116], [7, 137], [8, 135], [9, 130], [10, 167], [11, 169], [12, 179], [13, 185], [14, 176], [15, 180], [16, 174], [17, 193], [18, 186], [19, 177], [20, 153], [21, 149], [22, 130], [23, 100], [24, 50]];
                var visitors = [[1, 65], [3, 50], [4, 73], [5, 100], [6, 95], [7, 103], [8, 111], [9, 97], [10, 125], [11, 100], [12, 95], [13, 141], [14, 126], [15, 131], [16, 146], [17, 158], [18, 160], [19, 151], [20, 125], [21, 110], [22, 100], [23, 85], [24, 37]];
                //console.log(pageviews)
                var plot = $.plot($("#site-stats"), [{
                    data : pageviews,
                    label : "Your pageviews"
                }, {
                    data : visitors,
                    label : "Site visitors"
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
                        mode : "time",
                        tickLength : 10
                    },

                    yaxes : [{
                        min : 20,
                        tickLength : 5
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
                        content : "%s for <b>%x:00 hrs</b> was %y",
                        dateFormat : "%y-%0m-%0d",
                        defaultTheme : false
                    },
                    colors : [$chrt_main, $chrt_second],
                    xaxis : {
                        ticks : 15,
                        tickDecimals : 2
                    },
                    yaxis : {
                        ticks : 15,
                        tickDecimals : 0
                    },
                });

            }

            /* end site stats */

            /* updating chart */

            if ($('#updating-chart').length) {

                // For the demo we use generated data, but normally it would be coming from the server
                var data = [], totalPoints = 200;
                function getRandomData() {
                    if (data.length > 0)
                        data = data.slice(1);

                    // do a random walk
                    while (data.length < totalPoints) {
                        var prev = data.length > 0 ? data[data.length - 1] : 50;
                        var y = prev + Math.random() * 10 - 5;
                        if (y < 0)
                            y = 0;
                        if (y > 100)
                            y = 100;
                        data.push(y);
                    }

                    // zip the generated y values with the x values
                    var res = [];
                    for (var i = 0; i < data.length; ++i)
                        res.push([i, data[i]])
                    return res;
                }

                // setup control widget
                var updateInterval = 1000;
                $("#updating-chart").val(updateInterval).change(function() {
                    var v = $(this).val();
                    if (v && !isNaN(+v)) {
                        updateInterval = +v;
                        if (updateInterval < 1)
                            updateInterval = 1;
                        if (updateInterval > 2000)
                            updateInterval = 2000;
                        $(this).val("" + updateInterval);
                    }
                });

                // setup plot
                var options = {
                    yaxis : {
                        min : 0,
                        max : 100
                    },
                    xaxis : {
                        min : 0,
                        max : 100
                    },
                    colors : [$chrt_fourth],
                    series : {
                        lines : {
                            lineWidth : 1,
                            fill : true,
                            fillColor : {
                                colors : [{
                                    opacity : 0.4
                                }, {
                                    opacity : 0
                                }]
                            },
                            steps : false

                        }
                    }
                };
                var plot = $.plot($("#updating-chart"), [getRandomData()], options);

                function update() {
                    plot.setData([getRandomData()]);
                    // since the axes don't change, we don't need to call plot.setupGrid()
                    plot.draw();

                    setTimeout(update, updateInterval);
                }

                update();

            }

            /*end updating chart*/

        });

        /* end flot charts */

    </script>

    @stop