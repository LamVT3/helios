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
                            class="fa fa-plus fa-lg"></i> Create Landing Page</a>
            @endcomponent

            @include('layouts.errors')

            <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- row -->

                <div class="row">

                    <article class="col-sm-12 col-md-12">

                        {{--@component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-info', 'title' => 'Campaign'])
                            <div class="widget-body">
                                <b>Campaign Name:</b> {{ $campaign->name }} <br>
                                <b>Campaign Code:</b> {{ $campaign->code }} <br>
                                <b>Campaign Description:</b> {{ $campaign->description }} <br>
                                <b>Creator:</b> {{ $campaign->creator }} <br>
                                <b>Created at:</b> {{ $campaign->created_at->toDateTimeString() }}
                            </div>
                        @endcomponent--}}
                        <!-- 2018-04-04 lamvt add landing page count -->
                        @component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Landing Pages (' . $landing_pages->count() . ')'])
                        <!-- end 2018-04-04 -->
                            <div class="widget-body no-padding">
                                <table id="table_landing_pages" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Platform</th>
                                        <th>Description</th>
                                        <th>Url</th>
                                        <th>Creator</th>
                                        <th>Created at</th>
                                        <th>Active?</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($landing_pages as $item)
                                        <tr id="landing-page-{{ $item->id }}">
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->platform }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td><a href="{{ $item->url }}" target="_blank">{{ $item->url }}</a></td>
                                            <td>{{ $item->creator_name }}</td>
                                            <td>{{ $item->created_at->toDateTimeString() }}</td>
                                            <td>{{ $item->is_active ? "Yes" : 'No' }}</td>
                                            <td>
                                                {{--@permission('edit-review')--}}
                                                <a data-toggle="modal" class='btn btn-xs btn-default'
                                                   data-target="#addModal"
                                                   data-item-id="{{ $item->id }}"
                                                   data-original-title='Edit Row'><i
                                                            class='fa fa-pencil'></i></a>
                                                {{--<a data-toggle="modal" class='btn btn-xs btn-default'
                                                   data-target="#deleteModal"
                                                   data-item-id="{{ $item->id }}"
                                                   data-item-name="{{ $item->name }}"
                                                   data-original-title='Delete Row'><i
                                                            class='fa fa-times'></i></a>--}}
                                                {{--@endpermission--}}
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

                @include('components.form-create-landing-page', ['type' => null])

                {{--<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                                <h3 class="modal-title"> Are you sure you want to delete this channel?</h3>
                            </div>
                            <div class="modal-footer">
                                <form method="post" action="">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value=""/>
                                    <button type="submit" class="btn btn-danger">
                                        Delete
                                    </button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        Cancel
                                    </button>

                                </form>
                            </div>

                        </div><!-- /.modal-content -->

                    </div><!-- /.modal-dialog -->
                </div>--}}

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

        $('#table_landing_pages').dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>" +
            "t" +
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
            "autoWidth": true,
            "preDrawCallback": function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_table_channel) {
                    responsiveHelper_table_channel = new ResponsiveDatatablesHelper($('#table_landing_pages'), breakpointDefinition);
                }
            },
            "rowCallback": function (nRow) {
                responsiveHelper_table_channel.createExpandIcon(nRow);
            },
            "drawCallback": function (oSettings) {
                responsiveHelper_table_channel.respond();
            },
            "order": [[0, "desc"]],
            "iDisplayLength": page_size,
        });



//        $('head').append('<link rel="stylesheet" href="{{ asset('js/plugin/selectize/css/selectize.bootstrap3.css') }}">');

        /* END BASIC */
    })

</script>
@include('components.script-landing-page')
@stop
