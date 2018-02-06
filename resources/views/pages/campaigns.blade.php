@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
                <a class="btn btn-primary btn-lg pull-right header-btn hidden-mobile"
                   data-item-type="Campaign"
                   data-toggle="modal"
                   data-target="#addModal"><i
                            class="fa fa-plus fa-lg"></i> Create Campaign</a>
            @endcomponent

            @include('layouts.errors')

            <!-- widget grid -->
            <section id="widget-grid" class="">

                <!-- row -->

                <div class="row">

                    <article class="col-sm-12 col-md-12">

                        @component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Campaigns (' . $campaigns->count() . ')'])
                            <div class="widget-body no-padding">
                                <ul id="myTab1" class="nav nav-tabs bordered">
                                    <li {!! $active == 'campaigns' ? 'class="active"' : '' !!}>
                                        <a href="{{ route('campaigns') }}">Campaigns </a>
                                    </li>
                                    <li {!! $active == 'subcampaigns' ? 'class="active"' : '' !!}>
                                        <a href="{{ route('campaign-details', 'all') }}"> Subcampaigns </a>
                                    </li>
                                    <li {!! $active == 'ads' ? 'class="active"' : '' !!}>
                                        <a href="{{ route('subcampaign-details', 'all') }}"> Ads </a>
                                    </li>
                                </ul>
                                <table id="table_campaigns" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th>Team</th>
                                        <th>Name</th>
                                        <th>Creator</th>
                                        <th>Created at</th>
                                        <th>Active?</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($campaigns as $item)
                                        <tr id="campaign-{{ $item->id }}">
                                            <td>{{ $item->source_name }}</td>
                                            <td>{{ $item->team_name }}</td>
                                            <td><a href="{{ route("campaign-details", $item->id) }}">{{ $item->name }}</a></td>
                                            <td>{{ $item->creator_name }}</td>
                                            <td>{{ $item->created_at->toDateTimeString() }}</td>
                                            <td>{{ $item->is_active ? "Yes" : 'No' }}</td>
                                            <td>
                                                {{--@permission('edit-review')--}}
                                                {{--<a data-toggle="modal" class='btn btn-xs btn-default'
                                                   data-target="#addModal"
                                                   data-item-id="{{ $item->id }}"
                                                   data-original-title='Edit Row'><i
                                                            class='fa fa-pencil'></i></a>--}}
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

                @include('components.form-create-campaign', ['type' => null])

                {{--<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                                <h3 class="modal-title"> Are you sure you want to delete this campaign?</h3>
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

<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function () {

        /* BASIC ;*/
        var responsiveHelper_table_campaign = undefined;

        var breakpointDefinition = {
            tablet: 1024,
            phone: 480
        };

        $('#table_campaigns').dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>" +
            "t" +
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
            "autoWidth": true,
            "preDrawCallback": function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_table_campaign) {
                    responsiveHelper_table_campaign = new ResponsiveDatatablesHelper($('#table_campaigns'), breakpointDefinition);
                }
            },
            "rowCallback": function (nRow) {
                responsiveHelper_table_campaign.createExpandIcon(nRow);
            },
            "drawCallback": function (oSettings) {
                responsiveHelper_table_campaign.respond();
            },
            "order": [[0, "desc"]]
        });



//        $('head').append('<link rel="stylesheet" href="{{ asset('js/plugin/selectize/css/selectize.bootstrap3.css') }}">');

        /* END BASIC */

        allCampaigns = {!! $campaigns !!}

        $('input[name=campaign]').selectize({
            valueField: '_id',
            labelField: 'name',
            searchField: ['name'],
            options: allCampaigns,
            maxItems: 1
        });
    })

</script>
@include('components.script-campaign')
@stop
