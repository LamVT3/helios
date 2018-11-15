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
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Phone Test'])
                            <div class="widget-body">
                                <form id="form-inventory-report" class="smart-form" action="#"
                                      url="#">
                                    <div class="row" id="">
                                        <section class="col col-lg-2 col-sm-4">
                                            <label class="label">Status</label>
                                            <select name="status" class="select2" style="width: 80%" id="status"
                                                    tabindex="1" autofocus
                                                    data-url="">
                                                <option value="">All</option>
                                                <option value="1">Pass</option>
                                                <option value="0">Fail</option>
                                            </select>
                                            <i></i>
                                        </section>
                                        <section class="col col-lg-3 col-sm-4">
                                            <label class="label">Date</label>
                                            <div id="reportrange" class="pull-left"
                                                 style="background: #fff; cursor: pointer; padding: 7px; border: 1px solid #ccc; min-width: 170px">
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                                <span class="registered_date"></span> <b class="caret"></b>
                                            </div>
                                        </section>
                                        <section class="col col-lg-2 col-sm-4">
                                            <button class="btn btn-primary btn-sm pull-left" type="button" id="filter-phone-test"
                                                    style="margin-top: 25px">
                                                <i class="fa fa-filter"></i>
                                                Filter
                                            </button>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <section class="col col-lg-5 col-sm-8">
                                            <label class="label">Phone</label>
                                            <input type="text" class="form-control" name="phone" id="phone">
                                        </section>
                                        <section class="col col-lg-2 col-sm-4">
                                            <button class="btn btn-success btn-sm pull-left" type="button" id="phone-test"
                                                    style="margin-top: 25px">
                                                <i class="fa fa-floppy-o"></i>
                                                Test
                                            </button>
                                        </section>
                                    </div>
                                </form>

                                <hr style="padding: 10px">
                                <div class="loading" style="display: none; padding-bottom: 50px">
                                    <div class="col-md-12 text-center">
                                        <img id="img_ajax_upload" src="{{ url('/img/loading/rolling.gif') }}" alt=""
                                             style="width: 2%;"/>
                                    </div>
                                </div>

                                <div id="wrapper">
                                    @include('pages.table_phone_test')
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
    <input type="hidden" name="page_size" value="{{$page_size}}">
    <input type="hidden" name="phone-test-create-url" value="{{route('phone_test_create')}}">
    <input type="hidden" name="phone-test-filter" value="{{route('phone_test_filter')}}">

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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>

<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function () {

        var page_size   = $('input[name="page_size"]').val();
        init_table();

        var start   = moment();
        var end     = moment();

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

        $('#filter-phone-test').click(function (e) {
            e.preventDefault();
            filter();
        });

        $('#phone-test').click(function (e) {
            e.preventDefault();

            var phone   = $('input[name=phone]').val();
            var url     = $('input[name=phone-test-create-url]').val();
            var data    = {};
            data.phone  = phone;

            if(phone.length < 1){
                return;
            }

            $.get(url, data, function (data) {
                console.log(data);
                filter();
                $('input[name=phone]').val('');
            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });

        });

        function init_table(){
            /* BASIC ;*/
            var responsiveHelper_table_team = undefined;

            var breakpointDefinition = {
                tablet: 1024,
                phone: 480
            };

            $('#table_phone_test').dataTable({
                "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 'C>r>" +
                "t" +
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 'i><'col-sm-6 col-xs-12'p>>",
                "autoWidth": true,
                "preDrawCallback": function () {
                    // Initialize the responsive datatables helper once.
                    if (!responsiveHelper_table_team) {
                        responsiveHelper_table_team = new ResponsiveDatatablesHelper($('#table_phone_test'), breakpointDefinition);
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

        function filter() {
            var url     = $('input[name=phone-test-filter]').val();
            var data    = {};
            data.date   = $('.registered_date').text();
            data.status = $('select[name=status]').val();

            $.get(url, data, function (data) {
                $('#wrapper').html(data);
                init_table();

            }).fail(
                function (err) {
                    alert('Cannot connect to server. Please try again later.');
                });
        }
    })

</script>
@stop
