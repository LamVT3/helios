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
                            <div class="widget-body">

                                <form id="search-form" class="smart-form" action="#">
                                    <div class="row">
                                        <div id="reportrange" class="pull-left"
                                             style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; margin: 10px 15px">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                            <span></span> <b class="caret"></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <section class="col col-3">
                                            <label class="label">Source</label>
                                            <label class="select"> <i class="icon-append fa fa-user"></i>
                                                <select name="source">
                                                    <option>All</option>
                                                    @foreach($sources as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                </select>
                                                <i></i>
                                            </label>
                                        </section>
                                        <section class="col col-3">
                                            <label class="label">Team</label>
                                            <label class="select"> <i class="icon-append fa fa-user"></i>
                                                <select name="team">
                                                    <option>All</option>
                                                    @foreach($teams as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <i></i>
                                            </label>
                                        </section>
                                        <section class="col col-3">
                                            <label class="label">Marketer</label>
                                            <label class="select"> <i class="icon-append fa fa-user"></i>
                                                <select name="marketer">
                                                    <option>All</option>
                                                    @foreach($marketers as $item)
                                                        <option value="{{ $item->id }}">{{ $item->username }}</option>
                                                    @endforeach
                                                </select>
                                                <i></i>
                                            </label>
                                        </section>
                                        <section class="col col-3">
                                            <label class="label">Campaign</label>
                                            <label class="select"> <i class="icon-append fa fa-user"></i>
                                                <select name="campaign">
                                                    <option>All</option>
                                                    @foreach($campaigns as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <i></i>
                                            </label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button class="btn btn-primary btn-sm" type="submit"  style="margin-right: 15px">
                                                <i class="fa fa-filter"></i>
                                                Filter
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <table id="table_ads" class="table "
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
                                        <th class="long">C1 Cost (VND)</th>
                                        <th>C2</th>
                                        <th class="long">C2 Cost (VND)</th>
                                        <th>C3</th>
                                        <th class="long">C3 Cost (VND)</th>
                                        <th>C3B</th>
                                        <th class="long">C3B Cost (VND)</th>
                                        <th>C3/C2 (%)</th>
                                        <th>L1</th>
                                        <th>L3</th>
                                        <th>L8</th>
                                        <th>L3/L1 (%)</th>
                                        <th>L8/L1 (%)</th>
                                        <th class="long">Spent (USD)</th>
                                        <th class="long">Revenue (USD)</th>
                                        <th>ME/RE (%)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr id="total">
                                        <td>{{ $source }}</td>
                                        <td>{{ $team }}</td>
                                        <td>{{ $marketer }}</td>
                                        <td>{{ $campaign }}</td>
                                        <td>All</td>
                                        <td>All</td>
                                        <td>{{ number_format($total['c1']) }}</td>
                                        <td>{{ number_format($total['c1_cost'], 2) }}</td>
                                        <td>{{ number_format($total['c2']) }}</td>
                                        <td>{{ number_format($total['c2_cost'], 2) }}</td>
                                        <td>{{ number_format($total['c3']) }}</td>
                                        <td>{{ number_format($total['c3_cost'], 2) }}</td>
                                        <td>{{ number_format($total['c3b']) }}</td>
                                        <td>{{ number_format($total['c3b_cost'], 2) }}</td>
                                        <td>{{ $total['c3_c2'] }}</td>
                                        <td>{{ $total['l1'] }}</td>
                                        <td>{{ $total['l3'] }}</td>
                                        <td>{{ $total['l8'] }}</td>
                                        <td>{{ $total['l3_l1'] }}</td>
                                        <td>{{ $total['l8_l1'] }}</td>
                                        <td>{{ number_format($total['spent'], 2) }}</td>
                                        <td>{{ number_format($total['revenue']) }}</td>
                                        <td>{{ $total['me_re'] }}</td>
                                    </tr>
                                    @foreach ($ads as $item)

                                        <tr id="ad-{{ $item->id }}">
                                            <td>{{ $item->source_name }}</td>
                                            <td>{{ $item->team_name }}</td>
                                            <td>{{ $item->creator_name }}</td>
                                            <td>{{ $item->campaign_name }}</td>
                                            <td>{{ $item->subcampaign_name }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ isset($results[$item->id]) ? number_format($results[$item->id]->c1) : 0 }}</td>
                                            <td>{{ $results[$item->id]->c1_cost or 0 }}</td>
                                            <td>{{ $results[$item->id]->c2 or 0}}</td>
                                            <td>{{ $results[$item->id]->c2_cost or 0 }}</td>
                                            <td>{{ $results[$item->id]->c3 or 0 }}</td>
                                            <td>{{ $results[$item->id]->c3_cost or 0 }}</td>
                                            <td>{{ $results[$item->id]->c3 or 0 }}</td>
                                            <td>{{ $results[$item->id]->c3b_cost or 0 }}</td>
                                            <td>{{ isset($results[$item->id]) && $results[$item->id]->c2 ? round($results[$item->id]->c3 / $results[$item->id]->c2, 4) * 100 : 'n/a' }}</td>
                                            <td>{{ $results[$item->id]->l1 or 0 }}</td>
                                            <td>{{ $results[$item->id]->l3 or 0 }}</td>
                                            <td>{{ $results[$item->id]->l8 or 0 }}</td>
                                            <td>{{ isset($results[$item->id]) && $results[$item->id]->l1 ? round($results[$item->id]->l3 / $results[$item->id]->l1, 4) * 100 : 'n/a' }}</td>
                                            <td>{{ isset($results[$item->id]) && $results[$item->id]->l1 ? round($results[$item->id]->l8 / $results[$item->id]->l1, 4) * 100 : 'n/a' }}</td>
                                            <td>{{ $results[$item->id]->spent or 0 }}</td>
                                            <td>{{ $results[$item->id]->revenue or 0 }}</td>
                                            <td>{{ isset($results[$item->id]) && $results[$item->id]->revenue ? round($results[$item->id]->spent / $results[$item->id]->revenue, 4) * 100 : 'n/a' }}</td>
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
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>

    <script type="text/javascript">

        // DO NOT REMOVE : GLOBAL FUNCTIONS!

        $(document).ready(function () {

            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#reportrange span').html(start.format('D/M/Y') + ' - ' + end.format('D/M/Y'));
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'right',
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    "This Week": [moment().startOf("week"), moment().endOf("week")],
                    "Last Week": [moment().subtract(1, "week").startOf("week"), moment().subtract(1, "week").endOf("week")],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

            /* BASIC ;*/
            var responsiveHelper_table_subcampaign = undefined;

            var breakpointDefinition = {
                tablet: 1024,
                phone: 480
            };

            $('#table_ads').dataTable({
                "sDom":
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
                }

            });


//        $('head').append('<link rel="stylesheet" href="{{ asset('js/plugin/selectize/css/selectize.bootstrap3.css') }}">');

            /* END BASIC */
        })

    </script>

@stop
