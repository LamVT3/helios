@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <style>
        .text-center th{
            text-align: center;
        }
    </style>
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

        @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

        @endcomponent

        <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- row -->

                <div class="row">

                    <article class="col-sm-12 col-md-12">

                        @component('components.jarviswidget',
                        ['id' => 1, 'icon' => 'fa-table', 'title' => 'Tracking'])
                            <div class="widget-body">

                                <form class="smart-form" method="post" action="{!! route('double-check.filter') !!}">
                                    {{csrf_field()}}
                                    {{--<div class="row">--}}
                                    {{--<div id="sub_reportrange" class="pull-left"--}}
                                    {{--style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; margin: 10px 15px">--}}
                                    {{--<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;--}}
                                    {{--<span class="registered_date"></span> <b class="caret"></b>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    <div class="row">
                                        <div id="reportrange" class="pull-left"
                                             style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; margin: 10px 15px">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                            <span class="submit_date">{{$submit_date}}</span> <b class="caret"></b>
                                            <input type="hidden" name="submit_date" value="{{$submit_date}}">
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

                                <div class="row">
                                    <div class="col-sm-12">
                                        <article class="col-sm-12 col-md-12">
                                            <table class="table table-bordered table-hover text-center"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>Ad Result</th>
                                                    <th>-</th>
                                                    <th>Contact</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{$table['ad_results']['c3']}}</td>
                                                        <th>C3</th>
                                                        <td>{{$table['contacts']['c3']}}</td>

                                                    </tr>
                                                    <tr>
                                                        <td>{{$table['ad_results']['l1']}}</td>
                                                        <th>L1</th>
                                                        <td>{{$table['contacts']['l1']}}</td>

                                                    </tr>
                                                    <tr>
                                                        <td>{{$table['ad_results']['l2']}}</td>
                                                        <th>L2</th>
                                                        <td>{{$table['contacts']['l2']}}</td>

                                                    </tr>
                                                    <tr>
                                                        <td>{{$table['ad_results']['l3']}}</td>
                                                        <th>L3</th>
                                                        <td>{{$table['contacts']['l3']}}</td>

                                                    </tr>
                                                    <tr>
                                                        <td>{{$table['ad_results']['l4']}}</td>
                                                        <th>L4</th>
                                                        <td>{{$table['contacts']['l4']}}</td>

                                                    </tr>
                                                    <tr>
                                                        <td>{{$table['ad_results']['l5']}}</td>
                                                        <th>L5</th>
                                                        <td>{{$table['contacts']['l5']}}</td>

                                                    </tr>
                                                    <tr>
                                                        <td>{{$table['ad_results']['l6']}}</td>
                                                        <th>L6</th>
                                                        <td>{{$table['contacts']['l6']}}</td>

                                                    </tr>
                                                    <tr>
                                                        <td>{{$table['ad_results']['l7']}}</td>
                                                        <th>L7</th>
                                                        <td>{{$table['contacts']['l7']}}</td>

                                                    </tr>
                                                    <tr>
                                                        <td>{{$table['ad_results']['l8']}}</td>
                                                        <th>L8</th>
                                                        <td>{{$table['contacts']['l8']}}</td>

                                                    </tr>
                                                    <tr>
                                                        <td>{{$table['ad_results']['spent']}}</td>
                                                        <th>Spent</th>
                                                        <td>{{$table['contacts']['spent']}}</td>

                                                    </tr>
                                                    <tr>
                                                        <td>{{$table['ad_results']['revenue']}}</td>
                                                        <th>Revenue</th>
                                                        <td>{{$table['contacts']['revenue']}}</td>

                                                    </tr>

                                                </tbody>
                                            </table>
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

    <script type="text/javascript">
        $(document).ready(function () {

            var start = moment();
            var end = moment();

            function reportrange_span(start, end) {
                $('#reportrange span').html(start.format('D/M/Y') + '-' + end.format('D/M/Y'));
                $('#reportrange input').val(start.format('D/M/Y') + '-' + end.format('D/M/Y'));
            }

            // reportrange_span(start, end);

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'right',
                useCurrent: false,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    "This Week": [moment().startOf("isoWeek"), moment().endOf("isoWeek")],
                    "Last Week": [moment().subtract(1, "week").startOf("isoWeek"), moment().subtract(1, "week").endOf("isoWeek")],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, reportrange_span);

        })
    </script>
    @include('components.script-jarviswidget')
@endsection