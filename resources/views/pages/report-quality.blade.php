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

                        @component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Report'])
                            <div class="widget-body no-padding">
                                <table id="table_ads" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th>Team</th>
                                        <th>MKTer</th>
                                        <th>Campaign</th>
                                        <th>Subcampaign</th>
                                        <th>Ad</th>
                                        <th>C1</th>
                                        <th>C1 Cost</th>
                                        <th>C2</th>
                                        <th>C2 Cost</th>
                                        <th>C3</th>
                                        <th>C3 Cost</th>
                                        <th>C3B</th>
                                        <th>C3B Cost</th>
                                        <th>C3/C2</th>
                                        <th>L1</th>
                                        <th>L3</th>
                                        <th>L8</th>
                                        <th>L3/L1</th>
                                        <th>L8/L1</th>
                                        <th>Spent</th>
                                        <th>Revenue</th>
                                        <th>ME/RE</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($ads as $item)
                                        <tr id="ad-{{ $item->id }}">
                                            <td>{{ $item->source_name }}</td>
                                            <td>{{ $item->team_name }}</td>
                                            <td>{{ $item->creator_name }}</td>
                                            <td>{{ $item->campaign_name }}</td>
                                            <td>{{ $item->subcampaign_name }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>C3B</td>
                                            <td>C3B Cost</td>
                                            <td>C3/C2</td>
                                            <td>L1</td>
                                            <td>L3</td>
                                            <td>L8</td>
                                            <td>L3/L1</td>
                                            <td>L8/L1</td>
                                            <td>Spent</td>
                                            <td>Revenue</td>
                                            <td>ME/RE</td>
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

<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function () {

        /* BASIC ;*/
        var responsiveHelper_table_subcampaign = undefined;

        var breakpointDefinition = {
            tablet: 1024,
            phone: 480
        };

        $('#table_ads').dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>" +
            "<'tb-only't>" +
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
            "autoWidth": true,
            "preDrawCallback": function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_table_subcampaign) {
                    responsiveHelper_table_subcampaign = new ResponsiveDatatablesHelper($('#table_ads'), breakpointDefinition);
                }
            },
            "rowCallback": function (nRow) {
                responsiveHelper_table_subcampaign.createExpandIcon(nRow);
            },
            "drawCallback": function (oSettings) {
                responsiveHelper_table_subcampaign.respond();
            },
            "order": [[0, "desc"]]
        });



//        $('head').append('<link rel="stylesheet" href="{{ asset('js/plugin/selectize/css/selectize.bootstrap3.css') }}">');

        /* END BASIC */
    })

</script>
@include('components.script-ads')
@stop
