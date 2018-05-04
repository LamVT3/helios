@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
                <a class="btn btn-primary btn-lg pull-right header-btn hidden-mobile"
                   data-item-type="Ad"
                   data-toggle="modal"
                   data-target="#addModal"><i
                            class="fa fa-plus fa-lg"></i> Create Ad</a>
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

                        @component('components.jarviswidget',
                                                    ['id' => 1, 'icon' => 'fa-table', 'title' => 'Ad' . $subcampaign->name])
                            <div class="widget-body no-padding">
                                <table id="table_ads" class="table table-striped table-bordered table-hover"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th>Team</th>
                                        <th>Name</th>
                                        <th>Medium</th>
                                        <th>Subcampaign</th>
                                        <th>Campaign</th>
                                        <th>Landing Page</th>
                                        <th>Shorten URL</th>
                                        <th>Link tracking</th>
                                        <th>MOL Tracking</th>
                                        <th>Creator</th>
                                        <th>Created at</th>
                                        {{--<th>Active?</th>--}}
                                        {{--<th>Action</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($ads as $item)
                                        <tr id="ad-{{ $item->id }}">
                                            <td>{{ $item->source_name }}</td>
                                            <td>{{ $item->team_name }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->medium }}</td>
                                            <td>{{ $item->subcampaign_name }}</td>
                                            <td>{{ $item->campaign_name }}</td>
                                            <td>{{ $item->landing_page_name }}</td>
                                            <td>
                                                <a class="copy btn btn-default btn-xs" data-id="{{ $item->id }}" href="javascript:void(0)"> <i class='fa fa-copy'></i></a>
                                                <a id="url-{{ $item->id }}" href="{{ $item->shorten_url }}" target="_blank">{{ $item->shorten_url }}</a>
                                            </td>
                                            <td id="tracking_link">
                                                <a id="tracking_link" href="{{ $item->tracking_link }}" target="_blank">{{ $item->tracking_link }}</a>
                                                <a class="btn btn-success btn-xs show_more" href="javascript:void(0)" target="_blank">More ></a>
                                                <a class="btn btn-danger btn-xs show_less" href="javascript:void(0)" target="_blank">< Less</a>
                                            </td>
                                            <td><a href="javascript:void(0)" target="_blank">{{ $item->mol_link_tracking }}</a></td>
                                            <td>{{ $item->creator_name }}</td>
                                            <td>{{ $item->created_at->toDateTimeString() }}</td>
                                            {{--<td>{{ $item->is_active ? "Yes" : 'No' }}</td>--}}
                                            {{--<td>--}}
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
                                            {{--</td>--}}
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

                @include('components.form-create-ads', ['type' => null])

                {{--<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                                <h3 class="modal-title"> Are you sure you want to delete this subcampaign?</h3>
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
<script src="{{ asset('js/plugin/selectize/js/standalone/selectize.min.js')}}"></script>

<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function () {
        var page_size   = $('input[name="page_size"]').val();

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
            "order": [],
            "iDisplayLength": page_size,
            'scrollY'       : '55vh',
            "scrollX"       : true,
            'scrollCollapse': true,
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

        allSubCampaigns = {!! $subcampaigns !!}

        $('input[name=subcampaign]').selectize({
            valueField: '_id',
            labelField: 'name',
            searchField: ['name'],
            options: allSubCampaigns,
            maxItems: 1
        });

        $('.copy').click(function() {

            // Select some text (you could also create a range)
            selectText("url-" + $(this).data("id"));

            // Use try & catch for unsupported browser
            try {

                // The important part (copy selected text)
                var ok = document.execCommand('copy');

                if (ok) $(this).text('Copied!');
                else    $(this).text('Unable to copy!');
            } catch (err) {
                console.log('Unsupported Browser!');
            }
        });

        // add read more toggle
        var initCnt = 100; //Intial characters to display

        $('a#tracking_link').each(function() {
            var trackingText = $(this).attr('href');

            if (trackingText.length > initCnt){
                var shortLink = trackingText.substr(0, initCnt) + '...';
                $(this).html(shortLink);
                $(this).siblings('a.show_more').show();
            }
        });

        $(".show_more").click(function(){
            var trackingLink = $(this).siblings('a#tracking_link').attr('href');
            $(this).siblings('a#tracking_link').html(trackingLink);
            $(this).siblings('a.show_less').show();
            $(this).hide();
        });

        $('.show_less').click(function() {
            var trackingLink    = $(this).siblings('a#tracking_link').attr('href');
            var shortLink       = trackingLink.substr(0, initCnt) + '...';
            $(this).siblings('a#tracking_link').html(shortLink);
            $(this).siblings('a.show_more').show();
            $(this).hide();
        });


    });


</script>
@include('components.script-campaign')
@stop
