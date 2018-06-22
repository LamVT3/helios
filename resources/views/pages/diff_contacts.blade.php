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
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Diff Contacts'])
                            <div class="widget-body">
                                <div class="row">
                                    <div id="reportrange" class="pull-left"
                                         style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; margin: 10px 15px">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                        <span class="registered_date"></span> <b class="caret"></b>
                                    </div>
                                </div>
                                <hr>
                                <div class="row table-responsive">
                                    <div class="col-md-6">
                                        <h1>Helios vs MOL</h1>
                                        <div class="row">
                                            <article class="col-sm-12 col-md-12">
                                                <table id="table_mol_helios" class="display table table-striped table-bordered table-hover" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Age</th>
                                                        <th>Submit</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($helios_diff as $item)
                                                    <tr id="contacts-{{ $item->id }}">
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->email }}</td>
                                                    <td>{{ $item->phone }}</td>
                                                    <td>{{ $item->age }}</td>
                                                    <td>{{ $item->submit_time ? date('d-m-Y H:i:s', $item->submit_time/1000) : ""}}</td>
                                                    </tr>
                                                    @endforeach

                                                    </tbody>
                                                </table>
                                            </article>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h1>MOL vs Helios</h1>
                                        <div class="row">
                                            <article class="col-sm-12 col-md-12">
                                                <table id="table_helios_mol" class="display table table-striped table-bordered table-hover" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Age</th>
                                                        <th>Submit</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($mol_diff as $item)
                                                        <tr>
                                                            <td>{{ $item['name'] }}</td>
                                                            <td>{{ $item['email'] }}</td>
                                                            <td>{{ $item['phone'] }}</td>
                                                            <td>{{ $item['age'] != '20 - 30 ??' ? $item['age'] : 21 }}</td>
                                                            <td>{{ $item['datetime_submitted'] }}</td>
                                                        </tr>
                                                    @endforeach

                                                    </tbody>
                                                </table>
                                            </article>
                                        </div>
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
    <input type="hidden" name="page_size" value="{{$page_size}}">
    <!-- END MAIN PANEL -->

@endsection


@section('script')

    <!-- PAGE RELATED PLUGIN(S) -->
    <script src="{{ asset('js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
    <script src="{{ asset('js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
    <script src="{{ asset('js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>

    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>

    <script type="text/javascript">

        // DO NOT REMOVE : GLOBAL FUNCTIONS!

        $(document).ready(function () {
            var page_size   = $('input[name="page_size"]').val();
            /* BASIC ;*/
            var responsiveHelper_table_channel = undefined;

            var breakpointDefinition = {
                tablet: 1024,
                phone: 480
            };

            $('#table_helios_mol').dataTable({
                "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>" +
                "t" +
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
                "autoWidth": true,
                "preDrawCallback": function () {
                    // Initialize the responsive datatables helper once.
                    if (!responsiveHelper_table_channel) {
                        responsiveHelper_table_channel = new ResponsiveDatatablesHelper($('#table_helios_mol'), breakpointDefinition);
                    }
                },
                "rowCallback": function (nRow) {
                    responsiveHelper_table_channel.createExpandIcon(nRow);
                },
                "drawCallback": function (oSettings) {
                    responsiveHelper_table_channel.respond();
                },
                "order": [[4, "desc"]],
                "iDisplayLength": parseInt(page_size),
                'scrollY'       : '55vh',
                "scrollX"       : true,
                'scrollCollapse': true,
                "columnDefs": [
                    { "width": "20%", "targets": '_all' },
                ]
            });


            /* END BASIC */
        })

        $(document).ready(function () {
            var page_size   = $('input[name="page_size"]').val();
            /* BASIC ;*/
            var responsiveHelper_table_channel = undefined;

            var breakpointDefinition = {
                tablet: 1024,
                phone: 480
            };

            $('#table_mol_helios').dataTable({
                "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>" +
                "t" +
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
                "autoWidth": true,
                "preDrawCallback": function () {
                    // Initialize the responsive datatables helper once.
                    if (!responsiveHelper_table_channel) {
                        responsiveHelper_table_channel = new ResponsiveDatatablesHelper($('#table_mol_helios'), breakpointDefinition);
                    }
                },
                "rowCallback": function (nRow) {
                    responsiveHelper_table_channel.createExpandIcon(nRow);
                },
                "drawCallback": function (oSettings) {
                    responsiveHelper_table_channel.respond();
                },
                "order": [[4, "desc"]],
                "iDisplayLength": parseInt(page_size),
                'scrollY'       : '55vh',
                "scrollX"       : true,
                'scrollCollapse': true,
                "columns": [
                    { "width": "20%" },
                    { "width": "20%" },
                    { "width": "20%" },
                    { "width": "20%" },
                    { "width": "20%" },
                ]
            });



            /* END BASIC */
        })

    </script>
    @include('components.script-diff_contacts')
@stop
