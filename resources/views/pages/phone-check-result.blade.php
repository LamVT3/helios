@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
            @endcomponent

            @include('layouts.errors')

            <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- row -->

                <div class="row">

                    <article class="col-sm-12 col-md-12">

                        @component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Results'])
                            <form id="search-form" class="smart-form" action="#"
                                  url="">
                                <div class="row">
                                    <section class="col col-sm-6 col-lg-3">
                                        <label class="label">Result</label>
                                        <select name="type" id="result" class="select2"
                                                style="width: 280px"
                                                data-url="">
                                            <option value="">All</option>
                                            <option value="1">OK</option>
                                            <option value="0">Fail</option>
                                        </select>
                                        <i></i>
                                    </section>
                                    <section class="col col-sm-6 col-lg-3">
                                        <label class="label">Time</label>
                                        <div id="reportrange" class="pull-left"
                                             style="background: #fff; cursor: pointer; padding: 8px; border: 1px solid #ccc;">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp
                                            <span class=""></span> <b class="caret"></b>
                                        </div>
                                        <div id="" class="pull-right"
                                             style="">
                                            <button id="filter" class="btn btn-primary btn-sm" type="button" style="float: right" >
                                                <i class="fa fa-search"></i>

                                            </button>
                                        </div>
                                        <i></i>
                                    </section>
                                </div>
                            </form>
                            <div class="loading" style="display: none; padding: 15px">
                                <div class="col-md-12 text-center">
                                    <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt="" style="width: 2%;"/>
                                </div>
                            </div>
                            <hr>
                            <div id="wrapper_report">
                                @include('pages.table_phone_check')
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
    <input type="hidden" name="page_size" value="{{$page_size}}">
    <input type="hidden" name="filter_url" value="{{route('phone_check_result_filter')}}">

    <!-- END MAIN PANEL -->


@endsection


@section('script')

<!-- PAGE RELATED PLUGIN(S) -->
<script src="{{ asset('js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>
<script src="{{ asset('js/plugin/selectize/js/standalone/selectize.min.js')}}"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>

<script type="text/javascript">

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
            opens: "left"
        }, cb);

        cb(start, end);

        init_table();

        function cb(start, end) {
            $('#reportrange span').html(start.format('D/M/Y') + '-' + end.format('D/M/Y'));
        }

        $('button#filter').click(function (e) {
            $('.loading').show();

            var url     = $('input[name="filter_url"]').val();
            var result  = $('#result').val();
            var registered_date = $('#reportrange span').html();

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    result: result,
                    registered_date: registered_date
                }
            }).done(function (response) {
                console.log(1111);
                $('#wrapper_report').html(response);
                init_table();
                $('.loading').hide();
            }).fail(function (response) {
                console.log(22222);
                $('.loading').hide();
            });
        })

        function init_table() {
            var page_size   = $('input[name="page_size"]').val();

            /* BASIC ;*/
            var responsiveHelper_table_team = undefined;

            var breakpointDefinition = {
                tablet: 1024,
                phone: 480
            };

            $('#table_phone_check').dataTable({
                "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 'C>r>" +
                "t" +
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 'i><'col-sm-6 col-xs-12'p>>",
                "autoWidth": true,
                "preDrawCallback": function () {
                    // Initialize the responsive datatables helper once.
                    if (!responsiveHelper_table_team) {
                        responsiveHelper_table_team = new ResponsiveDatatablesHelper($('#table_phone_check'), breakpointDefinition);
                    }
                },
                "rowCallback": function (nRow) {
                    responsiveHelper_table_team.createExpandIcon(nRow);
                },
                "drawCallback": function (oSettings) {
                    responsiveHelper_table_team.respond();
                },
                "order": [],
                "iDisplayLength": parseInt(page_size),
                'scrollY'       : '55vh',
                "scrollX"       : true,
                'scrollCollapse': true,
                "destroy": true,
            });
        }

    })

</script>
@stop
