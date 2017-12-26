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

                        @component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Contact Details'])
                            <div class="widget-body">
                                <table id="table_contact_details" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <tbody>
                                        <tr><td>Name</td><td>{{ $contact->name }}</td></tr>
                                        <tr><td>Email</td><td>{{ $contact->email }}</td></tr>
                                        <tr><td>Phone</td><td>{{ $contact->phone }}</td></tr>
                                        <tr><td>Marketer</td><td>{{ $contact->marketer }}</td></tr>
                                        <tr><td>Campaign</td><td>{{ $contact->campaign_name }}</td></tr>
                                        <tr><td>Channel</td><td>{{ $contact->channel_name }}</td></tr>
                                        <tr><td>Ads</td><td>{{ $contact->ads_name }}</td></tr>
                                        <tr><td>Landing page</td><td>{{ $contact->landingpage_name }}</td></tr>
                                        <tr><td>Current Level</td><td>Level {{ $contact->current_level }}</td></tr>
                                        <tr><td>Is Transferred?</td><td>{{ $contact->is_transferred ? "Transferred" : "" }}</td></tr>
                                        <tr><td>Is Valid</td><td>{{ $contact->is_valid ? "Valid" : "Invalid" }}</td></tr>
                                        <tr><td>Invalid Reason</td><td>{{ $contact->invalid_reason }}</td></tr>
                                        <tr><td>Is Returned</td><td>{{ $contact->is_returned ? "Returned" : "No" }}</td></tr>
                                        <tr><td>Returnred Reason</td><td>{{ $contact->returned_reason }}</td></tr>
                                        <tr><td>L1 Date</td><td>{{ $contact->l1_time }}</td></tr>
                                        <tr><td>L2 Date</td><td>{{ $contact->l2_time }}</td></tr>
                                        <tr><td>L3 Date</td><td>{{ $contact->l3_time }}</td></tr>
                                        <tr><td>L4 Date</td><td>{{ $contact->l4_time }}</td></tr>
                                        <tr><td>L5 Date</td><td>{{ $contact->l5_time }}</td></tr>
                                        <tr><td>L6 Date</td><td>{{ $contact->l6_time }}</td></tr>
                                        <tr><td>L7 Date</td><td>{{ $contact->l7_time }}</td></tr>
                                        <tr><td>L8 Date</td><td>{{ $contact->l8_time }}</td></tr>
                                        <tr><td>Saleperson</td><td>{{ $contact->sale_person }}</td></tr>
                                    </tbody>
                                </table>

                                <h3>Call history</h3>

                                <table id="table_call_history" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Old Level</th>
                                        <th>New Level</th>
                                        <th>Comment</th>
                                        <th>Status</th>
                                        <th>Audio</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($contact->call_history as $item)
                                        <tr id="">
                                            <td>{{ $item["time"] }}</td>
                                            <td>{{ $item["old_level"] }}</td>
                                            <td>{{ $item["new_level"] }}</td>
                                            <td>{{ $item["comment"] }}</td>
                                            <td>{{ $item["status"] }}</td>
                                            <td><audio controls >
                                                    <source src="{{ $item["audio"] }}" type="audio/mpeg">
                                                    Your browser does not support the audio tag.
                                                </audio></td>
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
    })

</script>
@include('components.script-campaign')
@stop
