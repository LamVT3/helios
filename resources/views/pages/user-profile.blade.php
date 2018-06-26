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
                        @if(isset($user->avatar) && $user->avatar != '')
                            <img class="avatar_profile" src="{{  asset(config('constants.AVATARS_URL').$user->avatar) }}" alt="{{$user->username}}">
                        @else
                            <img class="avatar_profile" src="{{  asset(config('constants.AVATARS_URL_DEFAULT')) }}" alt="{{$user->username}}">
                        @endif
                        <div class="padding-10">
                            <h4 class="font-md"><strong>{{ $user->username }}</strong>
                            <br>
                            <small class="text-danger">Team: <strong>{{ $user->team_name or 'N/A'}}</strong></small></h4>
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
                                <th class="text-center">Total Revenue</th>
                            </tr>
                            </thead>
                            <tbody>

                            <!-- TR -->
                            <tr>
                                <td>
                                    <a href="javascript:void(0);">{{ $user->rank }}</a>
                                </td>
                                <td class="text-center hidden-xs hidden-sm">
                                    <a href="javascript:void(0);">{{ number_format($profile['total_c3']) }}</a>
                                </td>
                                <td class="text-center hidden-xs hidden-sm">
                                    <a href="javascript:void(0);">{{ number_format($profile['total_l8']) }}</a>
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0);">{{ number_format($profile['total_revenue']) . ' VND' }}</a>
                                </td>
                            </tr>
                            <!-- end TR -->

                            </tbody>
                        </table>
                        <button id="kpiSetting" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#kpiSettingModal">
                            <i class="fa fa-calendar-minus-o"></i> KPI Setting
                        </button>

                        <!-- KPI Setting Modal -->
                        <div class="modal fade" id="kpiSettingModal" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h3 class="modal-title">KPI Setting</h3>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-3 col-md-3">MKT Name : </div>
                                            <div class="col-sm-4 col-md-4">ABC</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3 col-md-3">Select month : </div>
                                            <div class="col-sm-4 col-md-4"><input type="text" id="kpiMonth" /></div>
                                        </div>
                                        <div class="row">
                                            <section class="col col-5">
                                                <table class="table">
                                                    <thead>
                                                        <tr class="font-medium orange">
                                                            <th>Day</th>
                                                            <th>KPI C3B</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="kpiTable">

                                                    </tbody>
                                                </table>
                                            </section>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button id="confirm_setting" type="button" class="btn btn-primary" data-dismiss="modal">Yes</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                <h2>C3 this month</h2>

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
                                <h2>L8 this month</h2>

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
    <script src="{{ asset('js/plugin/flot/jquery.flot.cust.min.js') }}"></script>
    <script src="{{ asset('js/plugin/flot/jquery.flot.resize.min.js') }}"></script>
    <script src="{{ asset('js/plugin/flot/jquery.flot.time.min.js') }}"></script>
    <script src="{{ asset('js/plugin/flot/jquery.flot.tooltip.min.js') }}"></script>
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
            // DO NOT REMOVE : GLOBAL FUNCTIONS!
            pageSetUp();

            /* site stats chart */

            if ($("#site-stats_c3").length) {

                var plot = $.plot($("#site-stats_c3"), [{
                    data : {{ $profile["chart_c3"] }},
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
                    colors : [$chrt_main],

                   /* yaxis : {
                        ticks : 10, // hiển thị số phần tử trục y
                        tickDecimals : 0
                    }*/
                });

            }

            /* end site stats */
            if ($("#site-stats_l8").length) {

                var plot = $.plot($("#site-stats_l8"), [
                 {
                    data : {{ $profile["chart_l8"] }},
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
                        content : "<b>%y L8</b> (%x)",
                        dateFormat : "%d/%m/%Y",
                        defaultTheme : false,
                        shifts: {
                            x: -50,
                            y: 20
                        }
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

            $("#kpiMonth").datepicker().datepicker("setDate", new Date());

            inputKpi();

            $("#kpiMonth").change(function () { inputKpi(); });

            $("#kpiSettingModal").on('click', 'button#confirm_setting', function (){
                var date = $("#kpiMonth").datepicker('getDate');
                var month = date.getMonth() + 1;
                var year =  date.getFullYear();
                var output = [];
                $("#kpiTable tr").each(function (rowIndex) {
                    output[rowIndex] = $(this).find("td").eq(1).find("input").val();
                });
                $.post('{{ route("kpi-user-store", ["userId" => $user->id]) }}', {data: output, month: month, year: year})
                .fail(function (e) {
                    console.log(e);
                    alert("Cannot connect to server. Please try again later.");
                });
            });

        });

        function inputKpi() {
            $.ajax({}).done(function () {
                var date = $("#kpiMonth").datepicker('getDate');
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year =  date.getFullYear();
                date = year + "-" + month + "-" + day;
                var dayInMonth = new Date(year, month, 0).getDate();
                $.get('{{ route("kpi-user-getting", ["userId" => $user->id]) }}', {date: date}, function (response) {
                    var data = "";
                    for (var i = 0; i < dayInMonth; i++) {
                        data += "<tr><td>"+(i+1)+"</td><td><input type='text'/></td></tr>";
                    }
                    document.getElementById("kpiTable").innerHTML = data;
                }).fail(function (e) {
                    console.log(e);
                    alert("Cannot connect to server. Please try again later.");
                });
            });
        }

        /* end flot charts */

    </script>


    @stop