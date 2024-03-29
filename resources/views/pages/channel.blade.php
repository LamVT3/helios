@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
                <a class="btn btn-primary btn-lg pull-right header-btn hidden-mobile"
                   data-toggle="modal"
                   data-target="#addModal"><i
                            class="fa fa-plus fa-lg"></i> Create Channel</a>
            @endcomponent

            @include('layouts.errors')

            <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- row -->

                <div class="row">

                    <article class="col-sm-12 col-md-12">

                        @component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Channel (' . $channel->count() . ')'])
                            <div class="widget-body no-padding">
                                <table id="table_channel" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Facebook Account ID</th>
                                        <th>Source</th>
                                        <th>Thank You page</th>
                                        <th>Creator</th>
                                        <th>Created at</th>
                                        <th>Active</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($channel as $item)
                                        <tr id="channel-{{ $item->id }}">
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->fb_id }}</td>
                                            <td>{{ $item->source_name }}</td>
                                            <td><a href="{{ $item->thankyou_page_url }}" target="_blank">{{ $item->thankyou_page_url }}</a></td>
                                            <td>{{ $item->creator_name }}</td>
                                            <td>{{ $item->created_at->toDateTimeString() }}</td>
                                            <td>{{ $item->is_active ? "Yes" : 'No' }}</td>
                                            <td>
                                                <a data-toggle="modal" class='btn btn-xs btn-default'
                                                   data-target="#addModal"
                                                   data-item-id="{{ $item->id }}"
                                                   data-original-title='Edit Row'><i
                                                            class='fa fa-pencil'></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        @endcomponent

                    </article>

                </div>

                <!-- end row -->

            </section>
            <!-- end widget grid -->

                @include('components.form-create-channel', ['type' => null])

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

        $('#table_channel').dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 'C>r>" +
            "t" +
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 'i><'col-sm-6 col-xs-12'p>>",
            "autoWidth": true,
            "preDrawCallback": function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_table_channel) {
                    responsiveHelper_table_channel = new ResponsiveDatatablesHelper($('#table_channel'), breakpointDefinition);
                }
            },
            "rowCallback": function (nRow) {
                responsiveHelper_table_channel.createExpandIcon(nRow);
            },
            "drawCallback": function (oSettings) {
                responsiveHelper_table_channel.respond();
            },
            "order": [[5, "desc"]],
            "iDisplayLength": parseInt(page_size),
            'scrollY'       : '55vh',
            "scrollX"       : true,
            'scrollCollapse': true,
        });



//        $('head').append('<link rel="stylesheet" href="{{ asset('js/plugin/selectize/css/selectize.bootstrap3.css') }}">');

        /* END BASIC */
    })

</script>
@include('components.script-channel')
@stop
