<?php
$chart_c3 = $profile['chart_c3'];
$chart_l8 = $profile['chart_l8'];
$key_arr = [];
/*foreach($chart_c3 as $key_c3=> $value_c3){
    $key_arr_c3[] = $key_c3;
    $value_arr_c3[] = $value_c3;
}*/
//foreach($chart_l8 as $key_l8=> $value_l8){
//    $key_arr_l8[] = $key_l8;
//    $value_arr_l8[] = $value_l8;
//}
?>
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
                            <small class="text-danger">Team: {{ auth()->user()->team_name }}</small></h4>
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
                                <h2>Contact C3</h2>

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

                                    <div id="site-stats_c3" class="chart has-legend"></div>

                                </div>
                                <!-- end widget content -->

                            </div>
                            <!-- end widget div -->

                        </div>
                        <!-- end widget -->

                    </article>
                    <!-- WIDGET END -->

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
                                <h2>Contact L8</h2>

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
                                    <div id="site-stats_l8" class="chart has-legend"></div>
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
        var $chrt_grid_color = "#DDD";
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
            /* du lieu bieu do c3 */
            var key_arr_c3 = <?= json_encode($key_arr_c3)?>;
            var value_arr_c3 = <?= json_encode($value_arr_c3) ?>;
            var chart_c3 = [];
            for(var i=0; i< key_arr_c3.length;i ++){
                var temp_c3 = [parseFloat(key_arr_c3[i]), value_arr_c3[i]];
                chart_c3.push(temp_c3);
            }
            /* end c3 */
            /* du lieu bieu do l8 */
            var key_arr_l8 = <?= json_encode($key_arr_l8)?>;
            var value_arr_l8 = <?= json_encode($value_arr_l8) ?>;
            var chart_l8 = [];
            for(var i=0; i< key_arr_l8.length;i ++){
                var temp_l8 = [parseFloat(key_arr_l8[i]), value_arr_l8[i]];
                chart_l8.push(temp_l8);
            }

            /* end l8 */
            // DO NOT REMOVE : GLOBAL FUNCTIONS!
            pageSetUp();


            /* site stats chart */

            if ($("#site-stats_c3").length) {

                // var pageviews = [[2, 25], [3, 87], [4, 93], [5, 127], [6, 116], [7, 137], [8, 135], [9, 130], [10, 167], [11, 169], [12, 179], [13, 185], [14, 176], [15, 180], [16, 174], [17, 300], [18, 186], [19, 177], [20, 153], [21, 149], [22, 130], [23, 100], [24, 50],[25,175],[26,80]];
               var pageviews = chart_c3;
                // var visitors = [[2, 65], [4, 73], [5, 100], [6, 95], [7, 103], [8, 111], [9, 97], [10, 125], [11, 100], [12, 95], [13, 141], [14, 126], [15, 131], [16, 146], [17, 158], [18, 160], [19, 151], [20, 125], [21, 110], [22, 100], [23, 85], [24, 37],[25, 38],[26,15]];
                // var visitors = chart_l8;
                //console.log(pageviews)
                var plot = $.plot($("#site-stats_c3"), [{
                    data : pageviews,
                    label : "Contact C3"

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
                        timeformat: "%d/%m/%Y",
                        ticks : 12
                    },

                    yaxes : [{
                        min : 0,
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
                        content : "%s ngày mùng<b> %x</b> là <b>%y</b>",
                        dateFormat : "%y-%0m-%0d",
                        defaultTheme : false
                    },
                    colors : [$chrt_main],

                    yaxis : {
                        ticks : 15, // hiển thị số phần tử trục y
                        tickDecimals : 0
                    }
                });

            }

            /* end site stats */
            if ($("#site-stats_l8").length) {

                // var pageviews = [[2, 25], [3, 87], [4, 93], [5, 127], [6, 116], [7, 137], [8, 135], [9, 130], [10, 167], [11, 169], [12, 179], [13, 185], [14, 176], [15, 180], [16, 174], [17, 300], [18, 186], [19, 177], [20, 153], [21, 149], [22, 130], [23, 100], [24, 50],[25,175],[26,80]];
                // var pageviews = chart_c3;
                // var visitors = [[2, 65], [4, 73], [5, 100], [6, 95], [7, 103], [8, 111], [9, 97], [10, 125], [11, 100], [12, 95], [13, 141], [14, 126], [15, 131], [16, 146], [17, 158], [18, 160], [19, 151], [20, 125], [21, 110], [22, 100], [23, 85], [24, 37],[25, 38],[26,15]];
                var visitors = chart_l8;
                //console.log(pageviews)
                var plot = $.plot($("#site-stats_l8"), [
                 {
                    data : visitors,
                    label : "Contact L8"

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
                        timeformat: "%d/%m/%Y",
                        ticks : 12
                    },

                    yaxes : [{
                        min : 0,
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
                        content : "%s ngày mùng <b> %x</b> là <b>%y</b>",
                        dateFormat : "%y-%0m-%0d",
                        defaultTheme : false
                    },
                    colors : [$chrt_second],
                    // xaxis: {
                    //     ticks : 12, // hiểm thị số phần tử trục x
                    //     tickDecimals : 0
                    // },
                    yaxis : {
                        ticks : 15, // hiển thị số phần tử trục y
                        tickDecimals : 0
                    }
                });

            }


        });

        /* end flot charts */

    </script>


    @stop