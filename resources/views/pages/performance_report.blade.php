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
                        ['id' => 1, 'icon' => 'fa-table', 'title' => 'Performance Report'])
                            <div class="widget-body">
                                <form id="form-performance-report" class="smart-form" action="#"
                                      url="#">
                                    <div class="row padding" id="">
                                        <section class="col-4">
                                            <div id="reportrange" class="pull-left"
                                                 style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; margin: 10px 15px">
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                                <span class="registered_date"></span> <b class="caret"></b>
                                            </div>
                                        </section>
                                    </div>
                                    <div class="row" id="performance-filter">
                                        <section class="col-12">
                                            <label class="label">Marketer</label>
                                            <input type="text" value="" name="marketer" placeholder="Select marketer...">
                                            <i></i>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button class="btn btn-primary btn-sm" type="button" id="filter-performance"
                                                    style="margin-right: 15px">
                                                <i class="fa fa-filter"></i>
                                                Filter
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <hr style="padding: 10px">
                                <div class="loading" style="display: none; padding-bottom: 50px">
                                    <div class="col-md-12 text-center">
                                        <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt=""
                                             style="width: 2%;"/>
                                    </div>
                                </div>

                                <div id="wrapper_performance">
                                    @include('pages.table_performance_report')
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
    <input type="hidden" name="filter-performance-report" value="{{route('filter-performance-report')}}">

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

    <script src="{{ asset('js/plugin/selectize/js/standalone/selectize.min.js')}}"></script>
    <script src="{{ asset('js/fixedTable/tableHeadFixer.js') }}"></script>

    <style>

        #performance-filter
        {
            padding: 0px 15px 0px 15px;
        }



    </style>


    <script type="text/javascript">
        $(document).ready(function () {

            var start = moment();
            var end = moment();

            function reportrange_span(start, end) {
                $('#reportrange span').html(start.format('D/M/Y') + '-' + end.format('D/M/Y'));
            }

            reportrange_span(start, end);

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'right',
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    "This Week":[moment().startOf("isoWeek"),moment().endOf("isoWeek")],
                    "Last Week": [moment().subtract(1, "week").startOf("isoWeek"), moment().subtract(1, "week").endOf("isoWeek")],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, reportrange_span);

            function date_range_span(start, end) {
                $('#c3range span').html(start.format('D/M/Y') + '-' + end.format('D/M/Y'));
            }

            date_range_span(start, end);

            $('#c3range').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'right',
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    "This Week":[moment().startOf("isoWeek"),moment().endOf("isoWeek")],
                    "Last Week": [moment().subtract(1, "week").startOf("isoWeek"), moment().subtract(1, "week").endOf("isoWeek")],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, date_range_span);

            $('input[name=marketer]').selectize({
                delimiter: ',',
                persist: false,
                valueField: '_id',
                labelField: 'username',
                searchField: ['username'],
                options: {!! $users !!}
            });

        });

        $(document).ready(function () {

            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                var registered_date = $('.registered_date').text();
                $('input[name="registered_date"]').val(registered_date);
            });

            {{--$("#table_inventory_report").tableHeadFixer({"left" : 2, 'foot': true, 'z-index': 1});--}}
            {{--var d = new Date();--}}
            {{--var current_month = d.getMonth() + 1;--}}
            {{--if(current_month < 10){--}}
                {{--current_month = '0' + current_month;--}}
            {{--}--}}
            {{--$('select[name=month]').val(current_month).trigger('change');;--}}

            {{--$('input[name=channel]').selectize({--}}
                {{--delimiter: ',',--}}
                {{--persist: false,--}}
                {{--valueField: 'name',--}}
                {{--labelField: 'name',--}}
                {{--searchField: ['name'],--}}
                {{--options: {!! $channel !!}--}}
            {{--});--}}

            {{--//filter();--}}

            {{--$('button#filter-inventory').click(function() {--}}
                {{--filter();--}}
            {{--});--}}

            {{--function filter() {--}}
                {{--$('div.loading').show();--}}
                {{--var url = $('input[name=filter-inventory-report]').val();--}}
                {{--var month = $('select[name=month]').val();--}}
                {{--var channel = $('input[name=channel]').val();--}}
                {{--$.ajax({--}}
                    {{--url: url,--}}
                    {{--type: 'GET',--}}
                    {{--data: {--}}
                        {{--month   : month,--}}
                        {{--channel : channel,--}}
                    {{--}--}}
                {{--}).done(function (response) {--}}
                    {{--$('#wrapper_inventory').html(response);--}}
                    {{--$("#table_inventory_report").tableHeadFixer({"left" : 2, 'foot': true, 'z-index': 1});--}}
                    {{--$('div.loading').hide();--}}
                {{--}).error(function (response) {--}}
                    {{--$('div.loading').hide();--}}
                {{--});--}}
            {{--}--}}

        })

    </script>
@endsection