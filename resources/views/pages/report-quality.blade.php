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

                        <form id="search-form-report" class="smart-form" action="#"
                              url="{!! route('report.filter') !!}">
                            <div class="row">
                                <div id="reportrange" class="pull-left"
                                     style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; margin: 10px 15px">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                    <span class="registered_date"></span> <b class="caret"></b>
                                </div>
                            </div>
                            <div class="row">
                                <section class="col col-2">
                                    <label class="label">Source</label>
                                    <select name="source_id" class="select2" style="width: 280px" id="source_id"
                                            data-url="{!! route('report.getReportSource') !!}">
                                        <option value="all">All</option>
                                        @foreach($sources as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <i></i>
                                </section>
                                <section class="col col-2">
                                    <label class="label">Team</label>
                                    <select name="team_id" class="select2" id="team_id" style="width: 280px">
                                        <option value="all">All</option>
                                        @foreach($teams as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <i></i>
                                </section>
                                <section class="col col-2">
                                    <label class="label">Marketer</label>
                                    <select name="marketer_id" id="marketer_id" class="select2" style="width: 280px">
                                        <option value="all">All</option>
                                        @foreach($marketers as $item)
                                        <option value="{{ $item->id }}">{{ $item->username }}</option>
                                        @endforeach
                                    </select>
                                    <i></i>
                                </section>
                                <section class="col col-2">
                                    <label class="label">Campaign</label>
                                    <select name="campaign_id" id="campaign_id" class="select2" style="width: 280px"
                                            data-url="">
                                        <option value="all">All</option>
                                        @foreach($campaigns as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <i></i>
                                </section>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-primary btn-sm" type="submit" style="margin-right: 15px">
                                        <i class="fa fa-filter"></i>
                                        Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div style="position: relative">
                            <form action="{{ route('report.export')}}" enctype="multipart/form-data">
                                <input type="hidden" name="source_id">
                                <input type="hidden" name="marketer_id">
                                <input type="hidden" name="campaign_id">
                                <input type="hidden" name="team_id">
                                <input type="hidden" name="registered_date">
                                <div style="position: absolute; right: 75px; bottom: 0px;">
                                    <button class="btn btn-success" type="submit"
                                            style="background-color: #3276b1;border-color: #2c699d;">Export Excel
                                    </button>
                                </div>
                            </form>
                        </div>
                        <hr>
                        <div class="wrapper_report">
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
<script src="{{ asset('js/reports/report.js') }}"></script>
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

</script>

</script>
@stop
