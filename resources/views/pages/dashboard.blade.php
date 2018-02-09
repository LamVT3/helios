<?php
$chart_c3 = $dashboard['chart_c3'];
$chart_l8 = $dashboard['chart_l8'];
$key_arr = [];
foreach($chart_c3 as $key_c3=> $value_c3){
    $key_arr_c3[] = $key_c3;
    $value_arr_c3[] = $value_c3;
}
foreach($chart_l8 as $key_l8=> $value_l8){
    $key_arr_l8[] = $key_l8;
    $value_arr_l8[] = $value_l8;
}
?>
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
                <div>
                    <input type="hidden" id="startDate" name="startDate">
                    <input type="hidden" id="endDate" name="endDate">
                    <button type="submit" id="submit" type="hidden"></button>
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
                                    {{ $dashboard['c3'] }}
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
                                    {{ $dashboard['c3_cost'] ? $dashboard['c3_cost'] : 'n/a' }}
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
                                    {{ $dashboard['spent'] }}
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
                                    {{ $dashboard['revenue']}}
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
                                    <div id="site-stats" class="chart has-legend"></div>
                            </div>
                        @endcomponent
                    </article>

                </div>
                <!-- row -->
                <h1><i class="glyphicon glyphicon-calendar fa fa-line-chart"></i> <b>Leaderboard</b></h1>
                <div class="row">

                    <article class="col-sm-12 col-md-12 col-lg-4">

                    @component('components.jarviswidget',
                    ['id' => 0, 'icon' => 'fa-table', 'title' => "C3 Leaderboard"])

                        <!-- widget content -->
                            <div class="widget-body no-padding">
                                <div class="alert alert-info no-margin fade in">
                                    <button class="btn btn-xs btn-default active" onclick="c3_leaderboard('c3_today')">
                                        Today
                                    </button>
                                    <button class="btn btn-xs btn-default" onclick="c3_leaderboard('c3_thisweek')">
                                        This Week
                                    </button>
                                    <button class="btn btn-xs btn-default" onclick="c3_leaderboard('c3_thismonth')">
                                        This Month
                                    </button>
                                </div>
                                <div  id="c3_today" class="c3_leaderboard">
                                    <span onclick="this.parentElement.style.display='none'"> </span>
                                    <table  class="table table-striped table-bordered table-hover"
                                           width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Rank</th>
                                                <th>C3</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>1</th>
                                                <th>James</th>
                                                <td>level 4</td>
                                                <td>250</td>
                                            </tr>
                                            <tr>
                                                <th>2</th>
                                                <th>Eddie</th>
                                                <td>level 2</td>
                                                <td>150</td>
                                            </tr>
                                            <tr>
                                                <th>3</th>
                                                <th>Maii</th>
                                                <td>level 2</td>
                                                <td>113</td>
                                            </tr>
                                            <tr>
                                                <th>4</th>
                                                <th>Chanji</th>
                                                <td>level 2</td>
                                                <td>98</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="c3_thisweek" class="c3_leaderboard" style="display:none">
                                    <span onclick="this.parentElement.style.display='none'"> </span>
                                    <table  class="table table-striped table-bordered table-hover"
                                           width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>giang</th>
                                                <th>C3</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>1</th>
                                                <th>James</th>
                                                <td>level 4</td>
                                                <td>250</td>
                                            </tr>
                                            <tr>
                                                <th>2</th>
                                                <th>Eddie</th>
                                                <td>level 2</td>
                                                <td>150</td>
                                            </tr>
                                            <tr>
                                                <th>3</th>
                                                <th>Maii</th>
                                                <td>level 2</td>
                                                <td>113</td>
                                            </tr>
                                            <tr>
                                                <th>4</th>
                                                <th>Chanji</th>
                                                <td>level 2</td>
                                                <td>98</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div  id="c3_thismonth" class="c3_leaderboard" style="display:none">
                                    <span onclick="this.parentElement.style.display='none'"> </span>
                                    <table  class="table table-striped table-bordered table-hover"
                                           width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>giang</th>
                                                <th>Rank</th>
                                                <th>C3</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>1</th>
                                                <th>James</th>
                                                <td>level 4</td>
                                                <td>250</td>
                                            </tr>
                                            <tr>
                                                <th>2</th>
                                                <th>Eddie</th>
                                                <td>level 2</td>
                                                <td>150</td>
                                            </tr>
                                            <tr>
                                                <th>3</th>
                                                <th>Maii</th>
                                                <td>level 2</td>
                                                <td>113</td>
                                            </tr>
                                            <tr>
                                                <th>4</th>
                                                <th>Chanji</th>
                                                <td>level 2</td>
                                                <td>98</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <script>
                                function c3_leaderboard(detail_c3) {
                                    var i;
                                    var x = document.getElementsByClassName("c3_leaderboard");
                                    for (i = 0; i < x.length; i++) {
                                        x[i].style.display = "none";
                                    }
                                    document.getElementById(detail_c3).style.display = "block";
                                }
                            </script>
                            <!-- end widget content -->

                        @endcomponent


                    </article>

                    <article class="col-sm-12 col-md-12 col-lg-4">

                        @component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Revenue Leaderboard'])
                            <div class="widget-body no-padding">
                                <div class="alert alert-info no-margin fade in">
                                    <button class="btn btn-xs btn-default active" onclick="revenue_leaderboard('revenue_today')">
                                        Today
                                    </button>
                                    <button class="btn btn-xs btn-default" onclick="revenue_leaderboard('revenue_thisweek')">
                                        This Week
                                    </button>
                                    <button class="btn btn-xs btn-default" onclick="revenue_leaderboard('revenue_thismonth')">
                                        This Month
                                    </button>
                                </div>
                                <div id="revenue_today" class="revenue_leaderboard">
                                    <span onclick="this.parentElement.style.display='none'"> </span>
                                    <table id="dt_basic" class="table table-striped table-bordered table-hover"
                                           width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Rank</th>
                                                <th>Revenue(baht)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>1</th>
                                                <th>James</th>
                                                <td>level 4</td>
                                                <td>25,000</td>
                                            </tr>
                                            <tr>
                                                <th>2</th>
                                                <th>Eddie</th>
                                                <td>level 2</td>
                                                <td>15,000</td>
                                            </tr>
                                            <tr>
                                                <th>3</th>
                                                <th>Maii</th>
                                                <td>level 2</td>
                                                <td>11,300</td>
                                            </tr>
                                            <tr>
                                                <th>4</th>
                                                <th>Chanji</th>
                                                <td>level 2</td>
                                                <td>98,000</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                               <div id="revenue_thisweek" class="revenue_leaderboard" style="display:none">
                                   <span onclick="this.parentElement.style.display='none'"> </span>
                                   <table id="dt_basic" class="table table-striped table-bordered table-hover"
                                          width="100%">
                                       <thead>
                                           <tr>
                                               <th>#</th>
                                               <th>linh</th>
                                               <th>Rank</th>
                                               <th>Revenue(baht)</th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                           <tr>
                                               <th>1</th>
                                               <th>James</th>
                                               <td>level 4</td>
                                               <td>25,000</td>
                                           </tr>
                                           <tr>
                                               <th>2</th>
                                               <th>Eddie</th>
                                               <td>level 2</td>
                                               <td>15,000</td>
                                           </tr>
                                           <tr>
                                               <th>3</th>
                                               <th>Maii</th>
                                               <td>level 2</td>
                                               <td>11,300</td>
                                           </tr>
                                           <tr>
                                               <th>4</th>
                                               <th>Chanji</th>
                                               <td>level 2</td>
                                               <td>98,000</td>
                                           </tr>
                                       </tbody>
                                   </table>
                               </div>
                                <div id="revenue_thismonth" class="revenue_leaderboard" style="display:none">
                                    <span onclick="this.parentElement.style.display='none'"> </span>
                                    <table id="dt_basic" class="table table-striped table-bordered table-hover"
                                           width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>giang</th>
                                                <th>Rank</th>
                                                <th>Revenue(baht)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>1</th>
                                                <th>James</th>
                                                <td>level 4</td>
                                                <td>25,000</td>
                                            </tr>
                                            <tr>
                                                <th>2</th>
                                                <th>Eddie</th>
                                                <td>level 2</td>
                                                <td>15,000</td>
                                            </tr>
                                            <tr>
                                                <th>3</th>
                                                <th>Maii</th>
                                                <td>level 2</td>
                                                <td>11,300</td>
                                            </tr>
                                            <tr>
                                                <th>4</th>
                                                <th>Chanji</th>
                                                <td>level 2</td>
                                                <td>98,000</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <script>
                                    function revenue_leaderboard(detail_revenue) {
                                        var i;
                                        var x = document.getElementsByClassName("revenue_leaderboard");
                                        for (i = 0; i < x.length; i++) {
                                            x[i].style.display = "none";
                                        }
                                        document.getElementById(detail_revenue).style.display = "block";
                                    }
                                </script>
                            </div>
                        @endcomponent

                    </article>

                    <article class="col-sm-12 col-md-12 col-lg-4">

                        @component('components.jarviswidget',
                                                    ['id' => 2, 'icon' => 'fa-table', 'title' => 'ME/RE Leaderboard'])
                            <div class="widget-body no-padding">
                                <div class="alert alert-warning no-margin fade in">
                                    <button class="btn btn-xs btn-default active" onclick="mere_leaderboard('mere_today')">
                                        Today
                                    </button>
                                    <button class="btn btn-xs btn-default" onclick="mere_leaderboard('mere_thisweek')">
                                        This Week
                                    </button>
                                    <button class="btn btn-xs btn-default" onclick="mere_leaderboard('mere_thismonth')">
                                        This Month
                                    </button>
                                </div>
                                <div id="mere_today" class="mere_leaderboard" >
                                    <span onclick="this.parentElement.style.display='none'"> </span>
                                    <table id="dt_basic" class="table table-striped table-bordered table-hover"
                                           width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Rank</th>
                                                <th>Me/Re</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>1</th>
                                                <th>Eddie</th>
                                                <td>level 2</td>
                                                <td>35%</td>
                                            </tr>
                                            <tr>
                                                <th>1</th>
                                                <th>James</th>
                                                <td>level 4</td>
                                                <td>40%</td>
                                            </tr>
                                            <tr>
                                                <th>3</th>
                                                <th>Maii</th>
                                                <td>level 2</td>
                                                <td>43%</td>
                                            </tr>
                                            <tr>
                                                <th>4</th>
                                                <th>Chanji</th>
                                                <td>level 2</td>
                                                <td>56%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                               <div id="mere_thisweek" class="mere_leaderboard" style="display:none">
                                    <span onclick="this.parentElement.style.display='none'"> </span>
                                    <table id="dt_basic" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Rank</th>
                                            <th>Me/Re</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>1</th>
                                            <th>Eddie</th>
                                            <td>level 2</td>
                                            <td>35%</td>
                                        </tr>
                                        <tr>
                                            <th>1</th>
                                            <th>James</th>
                                            <td>level 4</td>
                                            <td>40%</td>
                                        </tr>
                                        <tr>
                                            <th>3</th>
                                            <th>Maii</th>
                                            <td>level 2</td>
                                            <td>43%</td>
                                        </tr>
                                        <tr>
                                            <th>4</th>
                                            <th>Chanji</th>
                                            <td>level 2</td>
                                            <td>56%</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                                <div id="mere_thismonth" class="mere_leaderboard" style="display:none">
                                    <span onclick="this.parentElement.style.display='none'"> </span>
                                    <table id="dt_basic" class="table table-striped table-bordered table-hover"
                                           width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Rank</th>
                                                <th>Me/Re</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>1</th>
                                                <th>Eddie</th>
                                                <td>level 2</td>
                                                <td>35%</td>
                                            </tr>
                                            <tr>
                                                <th>1</th>
                                                <th>James</th>
                                                <td>level 4</td>
                                                <td>40%</td>
                                            </tr>
                                            <tr>
                                                <th>3</th>
                                                <th>Maii</th>
                                                <td>level 2</td>
                                                <td>43%</td>
                                            </tr>
                                            <tr>
                                                <th>4</th>
                                                <th>Chanji</th>
                                                <td>level 2</td>
                                                <td>56%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <script>
                                    function mere_leaderboard(detail_mere) {
                                        var i;
                                        var x = document.getElementsByClassName("mere_leaderboard");
                                        for (i = 0; i < x.length; i++) {
                                            x[i].style.display = "none";
                                        }
                                        document.getElementById(detail_mere).style.display = "block";
                                    }
                                </script>
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
{{--    <script src="{{ asset('js/plugin/flot/jquery.flot.resize.min.js') }}"></script>--}}
{{--    <script src="{{ asset('js/plugin/flot/jquery.flot.time.min.js') }}"></script>--}}
    <script src="{{ asset('js/plugin/flot/jquery.flot.tooltip.min.js') }}"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

    <script type="text/javascript">
        // DO NOT REMOVE : GLOBAL FUNCTIONS!
        $(document).ready(function () {

            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#reportrange span').html(start.format('D/M/Y') + ' - ' + end.format('D/M/Y'));
                $('#startDate').val(start.format('MMMM D, YYYY'));
                $('#endDate').val(end.format('MMMM D, YYYY'));

            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    "This Week":[moment().startOf("week"),moment().endOf("week")],
                    "Last Week":[moment().subtract(1,"week").startOf("week"),moment().subtract(1,"week").endOf("week")],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },


            }, cb);
            cb(start, end);
            // $( "#submit" ).click();


// TAB THREE GRAPH //
            /* TAB 3: Revenew  */

            $(function () {

                var customers = [[2, 25], [3, 87], [4, 93], [5, 127], [6, 116], [7, 137], [8, 135], [9, 130], [10, 167], [11, 169], [12, 179], [13, 185], [14, 176], [15, 180], [16, 174], [17, 300], [18, 186], [19, 177], [20, 153], [21, 149], [22, 130]],
                    tours = 10,
                    hotels = 8,
                    cars = 30,
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

            /* end c3 */
            /* du lieu bieu do l8 */

            /* du lieu bieu do c3 */
            var key_arr_c3 = <?= json_encode($key_arr_c3)?>;
            var value_arr_c3 = <?= json_encode($value_arr_c3) ?>;
            var chart_c3 = [];
            for(var i=0; i< key_arr_c3.length;i ++){
                var temp_c3 = [key_arr_c3[i], value_arr_c3[i]];
                chart_c3.push(temp_c3);
            }
            /* end c3 */
            /* du lieu bieu do l8 */
            var key_arr_l8 = <?= json_encode($key_arr_l8)?>;
            var value_arr_l8 = <?= json_encode($value_arr_l8) ?>;
            var chart_l8 = [];
            for(var i=0; i< key_arr_l8.length;i ++){
                var temp_l8 = [key_arr_l8[i], value_arr_l8[i]];
                chart_l8.push(temp_l8);
            }

            /* end l8 */
            /* end l8 */
            // DO NOT REMOVE : GLOBAL FUNCTIONS!
            pageSetUp();


            /* site stats chart */

            if ($("#site-stats").length) {

                // var pageviews = [[2, 25], [3, 87], [4, 93], [5, 127], [6, 116], [7, 137], [8, 135], [9, 130], [10, 167], [11, 169], [12, 179], [13, 185], [14, 176], [15, 180], [16, 174], [17, 300], [18, 186], [19, 177], [20, 153], [21, 149], [22, 130], [23, 100], [24, 50],[25,175],[26,80]];
                var pageviews = chart_c3;
                // var visitors = [[2, 65], [4, 73], [5, 100], [6, 95], [7, 103], [8, 111], [9, 97], [10, 125], [11, 100], [12, 95], [13, 141], [14, 126], [15, 131], [16, 146], [17, 158], [18, 160], [19, 151], [20, 125], [21, 110], [22, 100], [23, 85], [24, 37],[25, 38],[26,15]];
                var visitors = chart_l8;
                //console.log(pageviews)
                var plot = $.plot($("#site-stats"), [{
                    data : pageviews,
                    label : "Contact C3"
                },{
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
                        mode : "time",
                        tickLength : 10
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
                    colors : [$chrt_main,$chrt_third],
                    xaxis : {
                        ticks : 12, // hiểm thị số phần tử trục x
                        tickDecimals : 0
                    },
                    yaxis : {
                        ticks : 15, // hiển thị số phần tử trục y
                        tickDecimals : 0
                    }
                });

            }
            /* end site stats */
        });

        /* end flot charts */

    </script>
@endsection